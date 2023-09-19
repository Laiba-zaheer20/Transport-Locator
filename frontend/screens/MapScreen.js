import React, { Component } from 'react';
import MapView, { Callout, Marker, Circle, AnimatedRegion } from 'react-native-maps';
import { StyleSheet, Text, View, Dimensions, Image } from 'react-native';
import { GooglePlacesAutocomplete } from 'react-native-google-places-autocomplete';
import MapViewDirections from 'react-native-maps-directions';

const origin = {
  latitude: 24.9072,
  longitude: 67.1103
};

const destination = {
  latitude: 24.9044,
  longitude: 67.0776,
};

const GOOGLE_MAPS_APIKEY = 'AIzaSyCGeMbXAI0hrwPbJWc19ajLHPYz4VvHRuw';


class MapScreen extends React.Component {


  constructor() {
    super();
    this.state = {
      pin: {
        latitude: 24.9180,
        longitude: 67.0971
      },
      region: {
        latitude: 24.8607,
        longitude: 67.0011,
        latitudeDelta: 0.0922,
        longitudeDelta: 0.0421
      },
      is_loading: false,
      pins: []

    }


  }

  async componentDidMount() {
    // POST request using fetch with async/await


    const requestOptions = {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        "route_id": "24"
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


    // this._isMounted = true;

  }

  // componentWillUnmount() {
  //   this._isMounted = false;
  // }



  render() {
    const { region } = this.state;
    const { pin } = this.state;
    var payments = [];
    var checkit = [];
    
    if (this.state.is_loading == true) {
      var n = this.state.pins.records[0];


      for (let i = 0; i < this.state.pins.records.length; i++) {
        var tem = {
          latitude: parseFloat(this.state.pins.records[i].location_latitude),
          longitude: parseFloat(this.state.pins.records[i].location_longitude)
        }
        payments.push(
          <Marker key={i} title={this.state.pins.records[i].name} coordinate={tem} />
        )
      }

      const waypts = [];

      for (let i = 1; i < (this.state.pins.records.length - 1); i++) {

        waypts.push({
          latitude: parseFloat(this.state.pins.records[i].location_latitude),
          longitude: parseFloat(this.state.pins.records[i].location_longitude)
        }
        )

      }
      var tem3 = {
        latitude: parseFloat(this.state.pins.records[0].location_latitude),
        longitude: parseFloat(this.state.pins.records[0].location_longitude)
      }
      var tem4 = {
        latitude: parseFloat(this.state.pins.records[this.state.pins.records.length - 1].location_latitude),
        longitude: parseFloat(this.state.pins.records[this.state.pins.records.length - 1].location_longitude)
      }


      checkit.push(
        <MapViewDirections
          key={0}
          origin={tem3}
          destination={tem4}
          apikey='AIzaSyCNdYQ8g7wt2vK6rKBUBdZl-93oqPFkb1w'
          strokeWidth={2}
          strokeColor="#111111"
          waypoints={waypts}
        />
      )

    };




    return (
      <View style={{ marginTop: 50, flex: 1 }}>

        <GooglePlacesAutocomplete
          placeholder='Search'
          fetchDetails={true}
          GooglePlacesSearchQuery={{
            rankby: "distance"
          }}
          onPress={(data, details = null) => {
            // 'details' is provided when fetchDetails = true
            // console.log(data, details)
            setRegion({
              latitude: details.geometry.location.lat,
              longitude: details.geometry.location.lng,
              latitudeDelta: 0.0922,
              longitudeDelta: 0.0421
            })
          }}
          query={{
            key: 'AIzaSyB4SMeHzTkERLAUnQ5z8aUfrpR_KQ04Ln4',
            language: 'en',
            components: "country:pak",
            types: "establishment",
            radius: 30000,
            location: `${region.latitude}, ${region.longitude}`
          }}
          styles={{
            container: { flex: 0, position: "absolute", width: "100%", zIndex: 1 },
            listView: { backgroundColor: "#433B3B"}
          }}
        />

        <MapView style={styles.map}
          initialRegion={{
            latitude: 24.9072,
            longitude: 67.1103,
            latitudeDelta: 0.0622,
            longitudeDelta: 0.0121,
          }}
          provider="google"
        >
          {checkit}
          {payments}
        </MapView>
      </View >
    )

  };
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  map: {
    width: Dimensions.get('window').width,
    height: Dimensions.get('window').height,
  },
});


const getArticlesFromApi = async () => {

  postData('http://localhost/TransportLocator/apis/stops/stops_via_route.php', { route_id: 2 })
    .then(data => {
      console.log(data); // JSON data parsed by `data.json()` call
    });
};


export default MapScreen;



