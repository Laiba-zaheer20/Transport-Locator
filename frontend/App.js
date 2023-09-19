import 'react-native-gesture-handler';
import React ,{useEffect}from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';

import SignInScreen from './screens/SignInScreen';
import MapScreen from './screens/MapScreen';
import StudentMap from './screens/StudentMap';
import map from './screens/map';
// import Trackee from './screens/Trackee';
// import Tracker from './screens/Tracker';
import Track from './screens/Track';
import AsyncStorage from '@react-native-async-storage/async-storage';

const Stack = createStackNavigator();
import {useState} from "react";


const App = () => {

  

  
  return (
    <NavigationContainer>
      <Stack.Navigator 
      screenOptions={{headerShown:false}}
      >
     <Stack.Screen
          name="SignInScreen"
          component={SignInScreen}
          options={({ navigation }) => ({
            title: '',
            headerStyle: {
              backgroundColor: '#FDE101',
              shadowColor: '#FDE101',
              elevation: 0
            }
          })}
        />

        <Stack.Screen
          name="MapScreen"
          component={MapScreen}
          options={({ navigation }) => ({
            title: '',
            headerStyle: {
              elevation: 0
            }
          })}
        />

      <Stack.Screen
          name="map"
          component={map}
          options={({ navigation }) => ({
            title: '',
            headerStyle: {
              elevation: 0
            }
          })}
     />

        <Stack.Screen
          name="Track"
          component={Track}
          options={({ navigation }) => ({
            title: '',
            headerStyle: {
              elevation: 0
            }
          })}
        />


      </Stack.Navigator>
    </NavigationContainer>
  );
}
const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
});

export default App;