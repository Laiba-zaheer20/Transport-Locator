import React from 'react';
import { StyleSheet, View, Text, Dimensions, Platform, SafeAreaView } from 'react-native';
import MapView, { Marker, AnimatedRegion } from 'react-native-maps';
import PubNub from 'pubnub';

import PubNubReact from 'pubnub-react';
const { width, height } = Dimensions.get('window');

const ASPECT_RATIO = width / height;
const LATITUDE = 37.78825;
const LONGITUDE = -122.4324;
const LATITUDE_DELTA = 0.0922;
const LONGITUDE_DELTA = LATITUDE_DELTA * ASPECT_RATIO;

// console.disableYellowBox = true;

class Tracker extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      latitude: LATITUDE,
      longitude: LONGITUDE,
        latitude1: LATITUDE,
      // coordinate: new AnimatedRegion({
      //   latitude: null,
      //   longitude: null,
      //   latitudeDelta: LATITUDE_DELTA,
      //   longitudeDelta: LONGITUDE_DELTA,
      // }),
    };

    // Replace "X" with your PubNub Keys
    this.pubnub = new PubNub({
      publishKey: 'pub-c-9a47be10-b3ff-48e9-873c-4c08c6799f73',
      subscribeKey: 'sub-c-5f86511c-dcb5-11eb-8c90-a639cde32e15',
    });
  }

  // code to receive messages sent in a channel
  componentDidMount() {
    this.subscribeToPubNub();
  }

  subscribeToPubNub = () => {
    
    this.pubnub.subscribe({
      channels: ['location'],
      withPresence: true,
    });
    this.pubnub.addListener('location', msg => {
      const { coordinate } = this.state;
      const { latitude, longitude } = msg.message;
      const newCoordinate = { latitude, longitude };
      latitude1=latitude;

      // if (Platform.OS === 'android') {
      //   if (this.marker) {
      //     this.marker._component.animateMarkerToCoordinate(newCoordinate, 500);
      //   }
      // } else {
      //   coordinate.timing(newCoordinate).start();
      // }
      // console.log(newCoordinate);

      
      this.setState({
        latitude,
        longitude,
        latitude1
      });
    });
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
          <Text>{this.state.latitude1}</Text>
        
          {/* <MapView
            style={styles.map}
            showUserLocation
            followUserLocation
            loadingEnabled
            ref={c => (this.mapView = c)}
            region={this.state.latitude ? this.getMapRegion() : null}
          > */}
            {/* <Marker.Animated
              ref={marker => {
                this.marker = marker;
              }}
              coordinate={this.state.coordinate}
            /> */}
          {/* </MapView> */}
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

export default Tracker;
