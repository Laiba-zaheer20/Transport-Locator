import React, { useState, useEffect } from 'react';
import { Image,Platform, Text, View, StyleSheet ,Dimensions, SafeAreaView } from 'react-native';
import Constants from 'expo-constants';
import MapView, { Marker, AnimatedRegion,Polyline,PROVIDER_GOOGLE } from 'react-native-maps';
import * as Location from 'expo-location';
import { render } from 'react-dom';
import PubNub from 'pubnub';
import MapViewDirections from 'react-native-maps-directions';
import AwesomeAlert from 'react-native-awesome-alerts';

var checked_in = false;

const { width, height } = Dimensions.get('window');

const ASPECT_RATIO = width / height;
const LATITUDE = 24.9065986;
const LONGITUDE =  67.1077832;
const LATITUDE_DELTA = 0.0922;
const LONGITUDE_DELTA = LATITUDE_DELTA * ASPECT_RATIO;
const GOOGLE_MAPS_APIKEY = 'AIzaSyDR33kps7PBQ2QwQJ3TfxPSCvy9w7dBaeY';


export default class map extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
      latitude: LATITUDE,
      longitude: LONGITUDE,
      error:null,
      errorMsg:"no",
      routeCoordinates: [],
      coordinate: new AnimatedRegion({
        latitude: LATITUDE,
        longitude: LONGITUDE,
        latitudeDelta: LATITUDE_DELTA,
        longitudeDelta: LONGITUDE_DELTA,
      }),
      go:[],
      pin: {
        latitude: 24.9180,
        longitude: 67.0971
      },
      prev:"non",
      region: {
        latitude: 24.8607,
        longitude: 67.0011,
        latitudeDelta: 0.0922,
        longitudeDelta: 0.0421
      },
      showAlert: false,
      is_loading: false,
      pins: [],
      name:this.props.route.params.responseJson.driver_info.records[0].name,
      rno:this.props.route.params.responseJson.driver_info.records[0].route_no,
    }

    this.pubnub = new PubNub({
      publishKey: 'pub-c-76d8000f-2880-44a1-b6cd-79cefe104609',
      subscribeKey: 'sub-c-21ad80b4-de71-11eb-b709-22f598fbfd18',
      uuid:"map",
});

const requestOptions = {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    "route_id": this.props.route.params.responseJson.driver_info.records[0].route_no
})
};

fetch("http://13.251.109.45/TransportLocator/apis/stops/stops_via_route.php", requestOptions)
  .then(response => response.json())
  .then((responseJson) => {
    console.log('getting data from fetch', responseJson)
    this.setState({
      is_loading: true,
      pins: responseJson
    })
  })
  .catch(error => console.log(error))
  }

  componentDidMount() {

    this.interval = setInterval(() => this._checkIn(), 5000);
  }
  
  componentWillUnmount() {
    clearInterval(this.interval);
  }

 
  hideAlert = () => {
    this.setState({
      showAlert: false
    });
  };

  async _checkIn(){

    const { coordinate } = this.state;
    const{ routeCoordinates} = this.state;

    if (Platform.OS === 'android' && !Constants.isDevice) {
      this.setState({
      errorMsg:'Oops, this will not work on Snack in an Android emulator. Try it on your device!'     
      })
      return;
      }
      
      let { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
      this.setState({
      errorMsg:'Permission to access location was denied'     
      })
      return;
      }
      
      let location = await Location.getCurrentPositionAsync({});
      const newCoordinate = {
            latitude:location.coords.latitude,
            longitude:location.coords.longitude
      };
      text1=this.state.rno;
      this.pubnub.publish({
            channel: text1,
            message: {
              latitude: location.coords.latitude,
              longitude:location.coords.longitude,
            },
      });
      
      this.setState({
            errorMsg:'no',
            latitude:location.coords.latitude,
            longitude:location.coords.longitude,
            coordinate:newCoordinate,
            routeCoordinates: routeCoordinates.concat([newCoordinate])
      })
          
          
          for (let i = 0; i < this.state.pins.records.length; i++) {
   
            var pinPointToGetDistance = {
              latitude: parseFloat(this.state.pins.records[i].location_latitude),
              longitude: parseFloat(this.state.pins.records[i].location_longitude)
            }
            var urlToFetchDistance = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='+this.state.latitude+','+this.state.longitude+'&destinations='+pinPointToGetDistance.latitude+','+pinPointToGetDistance.longitude+'&key=' +GOOGLE_MAPS_APIKEY ;
            fetch(urlToFetchDistance)
                    .then(res => {
            return res.json()
          })
          .then(res => {
            var num =res.rows[0].elements[0].distance.value;
            var stop_name =res.rows[0].elements[0].name;
            if(num <= 15.25 && stop_name != this.state.prev ){
                this.setState({
                  prev:stop_name,
                })   

                this.setState({
                  showAlert:true
                })
                setTimeout(this.hideAlert, 30000);
             
            }
         })
          .catch(error => {
                    console.log("Problem occurred");
          });
          }
        
  }

  getMapRegion = () => ({
    latitude: this.state.latitude,
    longitude: this.state.longitude,
    latitudeDelta: LATITUDE_DELTA,
    longitudeDelta: LONGITUDE_DELTA,
  });

  randomCoordinate() {
    const region = this.state.region;
    return {
      latitude:
        region.latitude + (Math.random() - 0.5) * (region.latitudeDelta / 2),
      longitude:
        region.longitude + (Math.random() - 0.5) * (region.longitudeDelta / 2),
    };
  }
  randomRegion() {
    return {
      ...this.state.region,
      ...this.randomCoordinate(),
    };
  }

  
  onRegionChange(region) {
    this.map.animateCamera({ center: this.randomCoordinate() });
  }
  randomCoordinate() {
    const region = this.state.region;
    return {
      latitude:
        region.latitude + (Math.random() - 0.5) * (region.latitudeDelta / 2),
      longitude:
        region.longitude + (Math.random() - 0.5) * (region.longitudeDelta / 2),
    };
  }

  render(){

    const { region } = this.state;
    const { pin } = this.state;
    var pinpoints = [];
    var direction = [];
    
    
    if (this.state.is_loading == true) {
      var n = this.state.pins.records[0];
      
      //Adding all pinpoints
      const waypts = [];

      for (let i = 0; i < this.state.pins.records.length; i++) {
        var pinpoint = {
          latitude: parseFloat(this.state.pins.records[i].location_latitude),
          longitude: parseFloat(this.state.pins.records[i].location_longitude)
        }

        pinpoints.push(
          <Marker key={i} title={this.state.pins.records[i].name} coordinate={pinpoint} />
        )
        //waypoints in direction
        waypts.push({
          latitude: parseFloat(this.state.pins.records[i].location_latitude),
          longitude: parseFloat(this.state.pins.records[i].location_longitude)
        }
        )
      }
      //fast university Co-ordinate  
      var pinpoint = {
        latitude: 24.8569,
        longitude:67.2647
      }
      pinpoints.push(
        <Marker key={100} title="Fast University" coordinate={pinpoint} />
      )

      direction.push(
        <MapViewDirections
          key={0}
          origin={this.state.coordinate}
          destination={pinpoint} // Fast University
          apikey='AIzaSyCNdYQ8g7wt2vK6rKBUBdZl-93oqPFkb1w'
          strokeWidth={3}
          strokeColor="#111111"
          waypoints={waypts}
        />
      )

    };

  let text = 'Waiting..';
  const {showAlert} = this.state;
    return (
      <SafeAreaView style={{ flex: 1 }}>
        <View style={styles.container}>
          <View style={styles.headerText}>
            <Text style={styles.headerTextStyle}>Name : {this.state.name}</Text>
            <Text style={styles.headerTextStyle}>Route Number: {this.state.rno}</Text>
          </View>
          <MapView style={styles.map} provider={PROVIDER_GOOGLE} initialRegion={this.getMapRegion()}>
            <Polyline coordinates={this.state.routeCoordinates} strokeColor="yellow" strokeWidth={5} />
            <Marker 
            image={require('../assets/bus.png')}
            coordinate={this.getMapRegion()} > 
            </Marker>
            {direction}
            {pinpoints}
          </MapView>

          <AwesomeAlert
          styles={{
          backgroundColor: "rgba(49,49,49,0.8)" ,
          }}
          color="#DD6B55"
          show={showAlert}
          showProgress={false}
          title="Stop Bus at nearest stop!!"
          titleStyle={{
            color:"#DD6B55",
            fontWeight: "bold",
          }}
          message="Wait for passengers for atleast 30 seconds"
          closeOnTouchOutside={false}
          closeOnHardwareBackPress={false}
          />
        </View>
      </SafeAreaView>
    );
}
}

    
const styles = StyleSheet.create({
  container: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'flex-end',
    alignItems: 'center',
    backgroundColor:'#656161'
  },
  paragraph: {
    fontSize: 18,
    textAlign: 'center',
  },
  map: {
    marginTop:50,
    width: Dimensions.get('window').width,
    height: '80%',
  },
  headerText:{
    paddingTop: 40, 
    flex: 1, 
    backgroundColor:'#656161', 
    width:Dimensions.get('window').width,
    alignItems:'center',
    // marginBottom:5,
    height:'30%'
  },
  headerTextStyle:{
    fontSize:15,
    color:'#fff',
  },
});
