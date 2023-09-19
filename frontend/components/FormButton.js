import React from 'react';
import { Text, TouchableOpacity, StyleSheet, Dimensions  } from 'react-native';
const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const FormButton = ({buttonTitle, ...rest}) => {
    return(
        <TouchableOpacity  style={styles.buttonContainer}  {...rest}>
            <Text style={styles.buttonText}>{buttonTitle}</Text>
        </TouchableOpacity>
    )
}

export default FormButton;

const styles= StyleSheet.create({
    buttonContainer:{
        marginTop:10,
        width:'100%',
        height:windowHeight/ 15,
        backgroundColor:'#656161',
        padding:10,
        alignItems:'center',
        borderRadius:3,
    },
    buttonText:{
        fontSize:20,
        fontWeight:'bold',
        color:'#fff',
        
    },

})