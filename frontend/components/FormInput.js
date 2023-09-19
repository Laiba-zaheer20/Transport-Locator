import { rest } from 'lodash';
import React from 'react';
import {View,TextInput, StyleSheet,Dimensions} from 'react-native';

import AntDesign from 'react-native-vector-icons/AntDesign';

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const FormInput = ({labelValue,placeholderText,iconType,...rest}) =>{
    return(
        <View style={styles.inputContainer}>
            <View style={styles.iconStyle}>
                <AntDesign name={iconType} size={25} color='#656161' />
            </View>
            <TextInput
                style={styles.input}
                value={labelValue}
                numberOfLines={1}
                placeholder={placeholderText}
                placeholderTextColor="#666"
                {...rest}
            />
        </View>
    )
}

export default FormInput;

const styles = StyleSheet.create({
    inputContainer:{
        marginTop:5,
        marginBottom:10,
        width: '100%',
        height:windowHeight / 15,
        borderBottomColor: '#7D7676',
        borderTopColor:'#FDE101',
        borderRightColor:'#FDE101',
        borderLeftColor:'#FDE101',

        borderRadius:3,
        borderWidth:1,
        flexDirection:'row',
        alignItems:'center',
        backgroundColor:'#FDE101',

    },
    iconStyle:{
        padding:10,
        height: '100%',
        justifyContent: 'center',
        alignItems:'center',
        borderRightColor: '#FDE101',
        borderRightWidth:1,
        width:50,
    },
    input:{
        padding:10,
        flex:1,
        fontSize:16,
        color:'#333',
        justifyContent:'center',
        alignItems:'center',
    },
    inputField:{
        padding:10,
        marginTop:5,
        marginBottom: 10,
        width:windowWidth / 1.5,
        height: windowHeight /15,
        fontSize: 16,
        borderRadius:8,
        borderWidth:1,
    }
})