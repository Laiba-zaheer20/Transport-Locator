import React from 'react';
import { Text, TouchableOpacity, StyleSheet, Dimensions  } from 'react-native';
const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const GetDistanceButton = ({buttonTitle, ...rest}) => {
    return(
        <TouchableOpacity  style={styles.buttonContainer}  {...rest}>
            <Text style={styles.buttonText}>{buttonTitle}</Text>
        </TouchableOpacity>
    )
}

export default GetDistanceButton;

const styles= StyleSheet.create({
    buttonContainer:{
        marginTop:10,
        width:'70%',
        height:windowHeight/ 15,
        backgroundColor:'#FDE101',
        marginBottom:50,
        padding:10,
        alignItems:'center',
        borderRadius:100,
        position:'absolute'
    },
    buttonText:{
        fontSize:20,
        fontWeight:'bold',
        color:'#656161',
        
    },
})