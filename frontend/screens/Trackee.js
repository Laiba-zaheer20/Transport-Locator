import React from 'react';
import { StyleSheet, View, Platform, Dimensions, SafeAreaView } from 'react-native';
import MapView, { Marker, AnimatedRegion } from 'react-native-maps';
import PubNub from 'pubnub';
import { PubNubProvider, usePubNub } from 'pubnub-react';

const { width, height } = Dimensions.get('window');

const ASPECT_RATIO = width / height;
const LATITUDE = 24.8342;
const LONGITUDE = 67.0681;
const LATITUDE_DELTA = 0.0922;
const LONGITUDE_DELTA = LATITUDE_DELTA * ASPECT_RATIO;

// const pubnub = new PubNub({
//   publishKey: 'pub-c-9a47be10-b3ff-48e9-873c-4c08c6799f73',
//   subscribeKey: 'sub-c-5f86511c-dcb5-11eb-8c90-a639cde32e15',
// });

export default class Trackee extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      latitude: LATITUDE,
      longitude: LONGITUDE,
      errorMsg:"",
      coordinate: new AnimatedRegion({
        latitude: LATITUDE,
        longitude: LONGITUDE,
        latitudeDelta: 0,
        longitudeDelta: 0,
        

      }),
    };
    // const pubnub=usePubNub();
    // Replace "X" with your PubNub Keys
    this.pubnub = new PubNub({
      publishKey: 'pub-c-9a47be10-b3ff-48e9-873c-4c08c6799f73',
      subscribeKey: 'sub-c-5f86511c-dcb5-11eb-8c90-a639cde32e15',
    });
    // this.pubnub.init(this);
  }

  componentDidMount() {
  
    if (Platform.OS === 'android' && !Constants.isDevice) {
      this.setState({
        ErrorMsg:'Oops, this will not work on Snack in an Android emulator. Try it on your device!'
      });
      return;
    }
    let { status } = Location.requestForegroundPermissionsAsync();
    if (status !== 'granted') {
    
      this.setState({
        ErrorMsg:'Permission to access location was denied'
      });
      return;
    }
  
    let location = Location.getCurrentPositionAsync({});
    
    this.setState({
      latitude: location.coords.latitude,
      longitude: location.coords.longitude,
    });

    this.watchLocation();
    
  }

  componentDidUpdate(prevProps, prevState) {
    if (this.props.latitude !== prevState.latitude) {
      this.pubnub.publish({
        message: {
          latitude: this.state.latitude,
          longitude: this.state.longitude,
        },
        channel: 'location',
      });
    }
  }

  componentWillUnmount() {
    navigator.geolocation.clearWatch(this.watchID);
  }

  watchLocation = () => {
    const { coordinate } = this.state;

    this.watchID = navigator.geolocation.getCurrentPosition(
      position => {
        const { latitude, longitude } = position.coords;

        const newCoordinate = {
          latitude,
          longitude,
        };

        if (Platform.OS === 'android') {
          if (this.marker) {
            this.marker._component.animateMarkerToCoordinate(newCoordinate, 500); // 500 is the duration to animate the marker
          }
        } else {
          coordinate.timing(newCoordinate).start();
        }

        this.setState({
          latitude,
          longitude,
        });
      },
      error => console.log(error),
      {
        enableHighAccuracy: true,
        timeout: 20000,
        maximumAge: 1000,
        distanceFilter: 30,
      }
    );
  };

  getMapRegion = () => ({
    latitude: this.state.latitude,
    longitude: this.state.longitude,
    latitudeDelta: LATITUDE_DELTA,
    longitudeDelta: LONGITUDE_DELTA,
  });

  render() {
    return (
      <SafeAreaView style={{ flex: 1 }}>
        <View style={styles.container}>
          <MapView style={styles.map} showUserLocation followUserLocation loadingEnabled region={this.getMapRegion()}>
            <Marker.Animated
              ref={marker => {
                this.marker = marker;
              }}
              coordinate={this.state.coordinate}
            />
          </MapView>
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
  },
  map: {
    ...StyleSheet.absoluteFillObject,
  },
});
