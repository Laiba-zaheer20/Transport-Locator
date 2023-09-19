import React from 'react';
import { StyleSheet, View, Button, Text, Dimensions, Alert, Platform, SafeAreaView } from 'react-native';
import MapView, { Marker, AnimatedRegion, Polyline, PROVIDER_GOOGLE } from 'react-native-maps';
import * as Location from 'expo-location';
import Animated from 'react-native-reanimated';
import BottomSheet from 'reanimated-bottom-sheet';
import * as Notifications from 'expo-notifications';
import Constants from 'expo-constants';
// import {GoogleApiWrapper} from 'google-maps-react';
const GOOGLE_MAPS_APIKEY = 'AIzaSyDR33kps7PBQ2QwQJ3TfxPSCvy9w7dBaeY';
import GetDistanceButton from '../components/GetDistanceButton';
import PubNub from 'pubnub';
// import distance from 'google-distance-matrix';

// var distance = require('distance-matrix-api');
// distance.key(GOOGLE_MAPS_APIKEY);

// import PubNubReact from 'pubnub-react';

const { width, height } = Dimensions.get('window');
const ASPECT_RATIO = width / height;
const LATITUDE = 24.9065986;
const LONGITUDE = 67.1077832;
const LATITUDE_DELTA = 0.0922;
const LONGITUDE_DELTA = LATITUDE_DELTA * ASPECT_RATIO;

import { LogBox } from 'react-native';
import { googleGeocodeAsync } from 'expo-location/build/LocationGoogleGeocoding';
LogBox.ignoreLogs(['Warning: ...']); // Ignore log notification by message
LogBox.ignoreAllLogs();//Ignore all log notifications

Notifications.setNotificationHandler({
  handleNotification: async () => ({
    shouldShowAlert: true,
    shouldPlaySound: false,
    shouldSetBadge: false,
  }),
});


class Track extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
      latitude: LATITUDE,
      longitude: LONGITUDE,
      error: null,
      errorMsg: "no",
      latitude1: LATITUDE,
      longitude1: LONGITUDE,
      stopid: this.props.route.params.responseJson.passenger_info.records[0].stop_id,
      routeCoordinates: [],
      lat_tem: "",
      lng_tem: "",
      dd: "Loading",
      coordinate: new AnimatedRegion({
        latitude: LATITUDE,
        longitude: LONGITUDE,
        latitudeDelta: LATITUDE_DELTA,
        longitudeDelta: LONGITUDE_DELTA,
      }),
      name: this.props.route.params.responseJson.passenger_info.records[0].name,
      stop: this.props.route.params.responseJson.passenger_info.records[0].stop,
      rno: this.props.route.params.responseJson.passenger_info.records[0].route_no,
      expoPushToken: '',
      notification: false,

    };
    this.notificationListener = React.createRef();
    this.responseListener = React.createRef();

    var j = this.state.stopid;
    var h = "http://13.251.109.45/TransportLocator/apis/stops/read_one.php?id=" + j;
    fetch(h)
      .then(response => response.json())
      .then((responseJson) => {
        this.setState({
          lat_tem: responseJson.records[0].location_latitude,
          lng_tem: responseJson.records[0].location_longitude,
        })
      })
      .catch(error => console.log(error))

    this.pubnub = new PubNub({
      publishKey: 'pub-c-76d8000f-2880-44a1-b6cd-79cefe104609',
      subscribeKey: 'sub-c-21ad80b4-de71-11eb-b709-22f598fbfd18',
      uuid: "Track",
    });
  }


  componentDidMount() {
    this.subscribeToPubNub();

    registerForPushNotificationsAsync().then(token => setExpoPushToken(token));
    this.notificationListener.current = Notifications.addNotificationReceivedListener(notification => {
      this.setState({
        notification: notification
      })
    });

    this.responseListener.current = Notifications.addNotificationResponseReceivedListener(response => {
      console.log(response);
    });

    return () => {
      Notifications.removeNotificationSubscription(this.notificationListener.current);
      Notifications.removeNotificationSubscription(this.responseListener.current);
    };
  }


  subscribeToPubNub() {
    const { coordinate } = this.state;
    const { routeCoordinates } = this.state;

    this.pubnub.addListener({
      message: (pubnubMessage) => {

        console.log('New Message:', pubnubMessage.message);
        var arr = []
        for (let value of Object.values(pubnubMessage.message)) {
          arr.push(value);
        }

        latitude = arr[0];
        longitude = arr[1];

        const newCoordinate = {
          latitude: latitude,
          longitude: longitude
        };

        latitude1 = latitude;
        longitude1 = longitude;

        this.setState({
          latitude,
          longitude,
          latitude1,
          longitude1,
          routeCoordinates: routeCoordinates.concat([newCoordinate])
        });

        let travelMode = 'DRIVING';


        var urlToFetchDistance = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=' + this.state.lat_tem + ',' + this.state.lng_tem + '&destinations=' + newCoordinate.latitude + '%2C' + newCoordinate.longitude + '&key=' + GOOGLE_MAPS_APIKEY;
        fetch(urlToFetchDistance)
          .then(res => {
            return res.json()
          })
          .then(res => {
            var num = res.rows[0].elements[0].distance.value;
            var time = res.rows[0].elements[0].duration.value;

            if (time == 5) {
              schedulePushNotification(this.state.name, this.state.stop);
            }

            if (num <= 20) {
              this.setState({
                dd: "Point Reached",
              })
            }
            else {
              this.setState({
                dd: res.rows[0].elements[0].distance.text
              })
            }

          })
          .catch(error => {
            console.log("Problem occurred");
          });
      }
    });
    var text1 = this.state.rno;
    console.log(text1);
    this.pubnub.subscribe({ channels: [text1] });
  };


  getMapRegion = () => ({
    latitude: this.state.latitude1,
    longitude: this.state.longitude1,
    latitudeDelta: LATITUDE_DELTA,
    longitudeDelta: LONGITUDE_DELTA,
  });


  async getdistance(lat, long) {
    // if (Platform.OS === 'android' && !Constants.isDevice) {
    //   this.setState({
    //   errorMsg:'Oops, this will not work on Snack in an Android emulator. Try it on your device!'     
    //   })
    //   return;
    //   }

    let { status } = await Location.requestForegroundPermissionsAsync();
    if (status !== 'granted') {
      alert("ho nhi sakta");
      this.setState({
        errorMsg: 'Permission to access location was denied'
      })
      return;
    }
    let location = await Location.getCurrentPositionAsync({});
    const newCoordinate = {
      latitude: location.coords.latitude,
      longitude: location.coords.longitude
    };
    let origins = [`${newCoordinate.latitude},${newCoordinate.longitude}`];
    let destination = [`${lat},${long}`];
    let travelMode = 'DRIVING';


    var urlToFetchDistance = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=' + this.state.lat_tem + ',' + this.state.lng_tem + '&destinations=' + lat + '%2C' + long + '&key=' + GOOGLE_MAPS_APIKEY;

    fetch(urlToFetchDistance)
      .then(res => {
        return res.json()
      })
      .then(res => {
        // alert( res.rows[0].elements[0].distance.text);
        // alert(res.rows[0].elements[0].duration.text);
        this.setState({
          distance: res.rows[0].elements[0].distance.text,
          time: res.rows[0].elements[0].duration.text
        })
        // alert(this.state.distance);
        // alert(this.state.time);
        this.bs.current.snapTo(0);

        // Do your stuff here
      })
      .catch(error => {
        console.log("Problem occurred");
      });
    // distance.matrix(origins, destinations, function (err, distances) {
    //   if (err) {
    //       return console.log(err);
    //   }
    //   if(!distances) {
    //       return console.log('no distances');
    //   }
    //   alert(distances);
    // });

    // google.maps.DistanceMatrixService(origins,destination,travelMode,(res,status)=>{
    //   alert(distance);
    // })
  }

  bs = React.createRef();
  fail = new Animated.Value(1);


  renderInner = () => (
    <View style={styles.panel}>
      <View>
        <Text style={styles.panelTitle}>Time:{this.state.time}</Text>
        <Text style={styles.panelTitle}>Distance:{this.state.distance}</Text>
      </View>
      {/* <View style={styles.panelButtonCenter}>
          <TouchableOpacity style={styles.panelButton}>
            <Text style={styles.panelButtonTitle} onPress={()=>this.bs.current.snapTo(1)}>Close</Text>
          </TouchableOpacity>
          </View> */}

    </View>
  );
  // this.getdistance.bind(this, this.state.latitude,this.state.longitude)

  renderHeader = () => (
    <View style={styles.header}>
      <View style={styles.panelHeader}>
        <View style={styles.panelHandle}></View>
      </View>
    </View>
  );

  render() {
    const { expoPushToken } = this.state;


    return (
      // <SafeAreaView style={{ flex: 1 }}>
      //   <View style={styles.container}>
      //     <Text>{this.state.latitude1}</Text>
      //     <Text>{this.state.longitude1}</Text>
      //   </View>
      // </SafeAreaView>
      <SafeAreaView style={{ flex: 1 }}>
        <View style={styles.container}>
          <View style={styles.headerText}>
            <Text style={styles.headerTextStyle}>Distance: {this.state.dd}</Text>
            <Text style={styles.headerTextStyle}>Name: {this.state.name}</Text>
            <Text style={styles.headerTextStyle}>Stop: {this.state.stop}</Text>
            <Text style={styles.headerTextStyle}>Route Number: {this.state.rno}</Text>
          </View>
          <BottomSheet
            ref={this.bs}
            snapPoints={[429, 0]}
            renderContent={this.renderInner}
            renderHeader={this.renderHeader}
            initialSnap={1}
            callbackNode={this.fail}
            enabledGestureInteraction={true}

          />


          <MapView style={styles.map} provider={PROVIDER_GOOGLE} region={this.getMapRegion()} >
            <Polyline coordinates={this.state.routeCoordinates} strokeWidth={5} />
            <Marker coordinate={this.getMapRegion()} />
          </MapView>
          {/* 
<Button
        title="Press to schedule a notification"
        onPress={async () => {
          await schedulePushNotification();
        }}
      /> */}
          <GetDistanceButton
            onPress={this.getdistance.bind(this, this.state.latitude, this.state.longitude)}
            buttonTitle="Get Distance"
          />

        </View>
      </SafeAreaView>
    );
  }

}


async function schedulePushNotification(name, stop) {
  await Notifications.scheduleNotificationAsync({
    content: {
      title: name + ",Your transport is 5 minutes away! 📬",
      body: 'come to your stop ASAP',
      data: { data: 'come to stop ASAP' },
    },
    trigger: { seconds: 2 },
  });
}

async function registerForPushNotificationsAsync() {
  let token;
  if (Constants.isDevice) {
    const { status: existingStatus } = await Notifications.getPermissionsAsync();
    let finalStatus = existingStatus;
    if (existingStatus !== 'granted') {
      const { status } = await Notifications.requestPermissionsAsync();
      finalStatus = status;
    }
    if (finalStatus !== 'granted') {
      alert('Failed to get push token for push notification!');
      return;
    }
    token = (await Notifications.getExpoPushTokenAsync()).data;
    console.log(token);
  } else {
    alert('Must use physical device for Push Notifications');
  }

  if (Platform.OS === 'android') {
    Notifications.setNotificationChannelAsync('default', {
      name: 'default',
      importance: Notifications.AndroidImportance.MAX,
      vibrationPattern: [0, 250, 250, 250],
      lightColor: '#FF231F7C',
    });
  }

  return token;
}


const styles = StyleSheet.create({
  container: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'flex-end',
    alignItems: 'center',
    backgroundColor: '#656161'
  },
  map: {
    width: Dimensions.get('window').width,
    height: '80%',
  },
  header: {
    backgroundColor: '#FDE101',
    shadowColor: '#333333',
    shadowOffset: { width: -1, height: -3 },
    shadowRadius: 2,
    shadowOpacity: 0.4,
    // elevation: 5,
    paddingTop: 20,
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
  },
  headerText: {
    marginTop: 40,
    flex: 1,
    backgroundColor: '#656161',
    width: Dimensions.get('window').width,
    alignItems: 'center',
    marginBottom: 1,
    paddingBottom: 20
  },
  headerTextStyle: {
    fontSize: 15,
    color: '#fff',
  },
  panelHeader: {
    alignItems: 'center',
  },
  panelHandle: {
    width: 40,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#00000040',
    marginBottom: 10,
  },
  panel: {
    padding: 20,
    backgroundColor: '#656161',
    paddingTop: 50,
  },
  panelButtonCenter: {
    alignItems: 'center'
  },
  panelTitle: {
    fontSize: 17,
    height: 35,
    color: '#fff'
  },
  panelButton: {
    padding: 13,
    borderRadius: 50,
    backgroundColor: '#FDE101',
    alignItems: 'center',
    marginVertical: 7,
    width: '50%'
  },
  panelButtonTitle: {
    fontSize: 17,
    fontWeight: 'bold',
    color: '#656161',
  },

});
// export default GoogleApiWrapper({
// apiKey:GOOGLE_MAPS_APIKEY 
// })(Track);
export default Track;