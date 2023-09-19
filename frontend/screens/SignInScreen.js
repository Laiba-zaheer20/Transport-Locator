import React, { useState,useEffect } from 'react';

import { RefreshControl,SafeAreaView, ScrollView,View, Text, Button, StyleSheet, Image, TouchableOpacity } from 'react-native';
import FormInput from '../components/FormInput';
import FormButton from '../components/FormButton';
import MapScreen from './MapScreen';
import AsyncStorage from '@react-native-async-storage/async-storage';

const wait = (timeout) => {
  return new Promise(resolve => setTimeout(resolve, timeout));
}

const storeData = async (id,password) => {
  try {
    value={
      "login_id":id,
      "password":password
    };
    const jsonValue = JSON.stringify(value)
    console.log(jsonValue);
    await AsyncStorage.setItem('key', jsonValue)
  } catch (e) {
    console.log("nothappen");
  }
}
const getData = async (navigation) => {
  try {
    // await AsyncStorage.removeItem('key');
    const jsonValue = await AsyncStorage.getItem('key')
    if(jsonValue !== null) {
      console.log(JSON.parse(jsonValue));
      
      const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: jsonValue
      };
     
      fetch("http://13.251.109.45/TransportLocator/apis/login/login.php", requestOptions)
      .then(response => response.json())
      .then((responseJson) => {
        if(responseJson.message == "Incorrect data or service issue"){
          settext1("Enter valid id and password !")
                    setID("");
                    setPassword("");
    
        }
        else{
        console.log('getting data from fetch', responseJson)
        if(responseJson.portal == "passenger"){
         
          navigation.navigate(
            'Track',
            { responseJson },
          );
     }
     else{ 
       if(responseJson.portal == "driver"){
      navigation.navigate(
        'map',
        { responseJson },
      );
     }
    }}});
 }
  } catch(e) {
    console.log("DOWN");
  }
}

const SignInScreen = ({navigation}) => {
    const [id, setID] = useState();
    const [password, setPassword] = useState();
    const [text1,settext1]=useState();


    useEffect(()=>{
      getData(navigation);
    },[]);
    
    function handleClick(){
      //some code
      // const requestOptions = {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify({ 'route_id': '2' })
      // };
  var he = 0;
  
  const requestOptions = {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      "login_id":id,
      "password":password
      })
  };
 
  fetch("http://13.251.109.45/TransportLocator/apis/login/login.php", requestOptions)
  .then(response => response.json())
  .then((responseJson) => {
    if(responseJson.message == "Incorrect data or service issue"){
      settext1("Enter valid id and password !")
                setID("");
                setPassword("");

    }
    else{
    storeData(id,password);
    console.log('getting data from fetch', responseJson)
    if(responseJson.portal == "passenger"){
     
      navigation.navigate(
        'Track',
        { responseJson },
      );
 }
 else{ 
   if(responseJson.portal == "driver"){
  navigation.navigate(
    'map',
    { responseJson },
  );
 }
}}
    // this.setState({
    //   is_loading: true,
    //   pins: responseJson
    // })

  }).catch(error => console.log(error))

      // fetch("http://13.251.109.45/TransportLocator/apis/login/read.php")
      //   .then(response => response.json())
      //   .then((responseJson) => {
      //     console.log('getting data from fetch', responseJson)
      //       var e="";
      //       console.log(responseJson.records[0]['id']);
      //           for (var i = 0; i < responseJson.records.length; i++){
      //                      name1 = responseJson.records[i]['id'];
      //                      phone = responseJson.records[i]['password'];
      //                      email1 = responseJson.records[i]['initial'];
      //            if(  name1 == id &&  phone== password){
      //             console.log("HELLO")
      //             he=1;
      //             e=email1;
      //           }
      //           }
      //           if(he==1){
      //             if(e== 'P'){
      //               console.log("map")
      //             navigation.navigate("map")
      //             }
      //             else{
      //               console.log("track")
      //               navigation.navigate("Track")
      //             }
      //           }
      //           else{
      //           settext1("Enter valid id and password !")
      //           setID("");
      //           setPassword("");
      //           }
      //   })
      //   .catch(error => console.log(error))


     }

    
    return (
      <View style={styles.container}>
        <Image
          source={require('../assets/logo.png')}
          style={styles.logo}
        />
        <Text style={styles.text}>
          Login
        </Text>
        <FormInput
          labelValue = {id}
          onChangeText = {(userID) => setID(userID)}
          placeholderText="Point ID"
          iconType="user"
          autoCapitalize="none"
          autoCorrect={false}
        />

        <FormInput
          labelValue = {password}
          onChangeText = {(userPassword)=>setPassword(userPassword)}
          placeholderText="Password"
          iconType="lock"
          secureTextEntry = {true}
          
        />
        <Text 
        style={{
          fontSize: 20,
          color: '#f44336',
          fontWeight : 'bold'
        }}
        >{text1}</Text>
        <FormButton
        buttonTitle="Sign In"
        onPress={() => {
          handleClick();
       }}   
       
      //  onPress={
          
      //     () => navigation.navigate("MapScreen")}
      />


        <TouchableOpacity style={styles.forgotButton} onPress={()=>{}}>
          <Text style={styles.navButtonText} >Forgot Password?</Text>
        </TouchableOpacity>
            </View>
    );
  };

const styles = StyleSheet.create({
    container:{
      backgroundColor:'#FDE101',
      flex:1,
      justifyContent:'center',
      alignItems:'center',
      padding:20,
    },
    logo:{
      height:178,
      width:170,
      resizeMode:'cover',
    },
    text:{
     
      fontSize:50,
      marginBottom:10,
      color:'#656161',
    },
    navButton:{
      marginTop:15,
    },
    forgotButton:{
      marginVertical:35,
    },
    navButtonText:{
      fontSize:18,
      fontWeight:'500',
      color:'#656161',
      
    }
});

export default SignInScreen;

