import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert, StyleSheet } from 'react-native';
import axios from 'axios';

const API_URL = "http://localhost:8000/index.php";  

const VerifyScreen = ({ navigation }) => {
    const [code, setCode] = useState('');

    // Function to handle verification
    const handleVerify = async () => {

        try {
            const response = await axios.post(
                `${API_URL}?action=verify`,
                { code },
                { withCredentials: true }
            );


            
            // Check if verification is successful
            if (!response.data.error) {
                Alert.alert("Success", "Code verified!");
                navigation.navigate("Dashboard");  // Navigate to the Dashboard screen
            } else {
                Alert.alert("Error", response.data.error || "Invalid code!");
            }
        } catch (error) {
            console.error(error);
            Alert.alert("Error", "Connection issue with the server.");
        }
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Enter the verification code</Text>
            
            <TextInput
                style={styles.input}
                placeholder="Enter 6-digit code"
                keyboardType="numeric"
                value={code}
                onChangeText={(text) => {
                    // Allow only numbers and limit to 6 characters
                    if (/^\d{0,6}$/.test(text)) {
                        setCode(text);
                    }
                }}
                placeholderTextColor="#888"
            />

            <TouchableOpacity style={styles.button} onPress={handleVerify} activeOpacity={0.8}>
                <Text style={styles.buttonText}>Verify</Text>
            </TouchableOpacity>
        </View>
    );
};

// Styles for the screen components
const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f9f9f9',
        padding: 20,
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        marginBottom: 20,
        color: '#333',
    },
    input: {
        width: '80%',
        height: 50,
        backgroundColor: '#fff',
        borderRadius: 10,
        paddingHorizontal: 15,
        marginBottom: 15,
        borderWidth: 1,
        borderColor: '#ccc',
        fontSize: 16,
        shadowColor: 'black',
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 3 },
        shadowRadius: 4,
        elevation: 3, // Shadow for Android
    },
    button: {
        backgroundColor: 'green',
        paddingVertical: 12,
        paddingHorizontal: 20,
        borderRadius: 10,
        width: '80%',
        alignItems: 'center',
        shadowColor: 'green',
        shadowOpacity: 0.3,
        shadowOffset: { width: 0, height: 4 },
        shadowRadius: 6,
        elevation: 4, // Shadow for Android
    },
    buttonText: {
        color: '#fff',
        fontSize: 18,
        fontWeight: 'bold',
        textTransform: 'uppercase',
        letterSpacing: 1,
    },
});

export default VerifyScreen;
