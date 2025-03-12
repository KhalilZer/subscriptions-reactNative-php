import React, { useState, useEffect } from 'react';
import { View, Text, TouchableOpacity, StyleSheet, Alert, TextInput, Button, ScrollView, ActivityIndicator, KeyboardAvoidingView, Platform } from 'react-native';
import axios from 'axios';

// API URL to interact with backend
const API_URL = "http://localhost:8000/index.php";

const DashboardScreen = ({ navigation }) => {
    // State hooks to store user, subscriptions, edit mode, loading state, and user data
    const [user, setUser] = useState<any>(null);
    const [subscriptions, setSubscriptions] = useState<any[]>([]);
    const [isEditing, setIsEditing] = useState(false);
    const [userData, setUserData] = useState({
        name: '',
        email: '',
        phone: ''
    });
    const [loading, setLoading] = useState(true);

    // Function to check if the user is logged in (session validation)
    const checkUserSession = async () => {
        try {
            const response = await axios.get(`${API_URL}?action=checkLogin`);
            
            if (response.data.user) {
                // Set user data from API response
                setUser(response.data.user);
                setUserData({
                    name: response.data.user.name,
                    email: response.data.user.email,
                    phone: response.data.user.phone
                });
                // Fetch the subscriptions of the logged-in user
                getSubscriptions(response.data.user.id);
            } else {
                // Redirect to Login screen if the user is not logged in
                navigation.navigate('Login');
            }
        } catch (error) {
            // Alert in case of connection issues
            Alert.alert('Error', 'Server connection issue');  // Message displayed when there's a problem with the server connection
            navigation.navigate('Login');  // Navigate to Login screen if an error occurs
        } finally {
            setLoading(false); // Stop loading after API call is complete
        }
    };

    // Function to fetch subscriptions based on user ID
    const getSubscriptions = async (user_id: number) => {
        try {
            const response = await axios.get(`${API_URL}?action=getSubscriptionsByUser&client_id=${user_id}`);
            if (response.data && response.data.subscriptions) {
                setSubscriptions(response.data.subscriptions); // Set the subscriptions state
            } else {
                // Show alert if no subscriptions are found for the user
                Alert.alert('No Subscriptions', 'The user has no subscriptions.');  // Message displayed when there are no subscriptions for the user
            }
        } catch (error) {
            // Error handling for subscription fetching
            Alert.alert('Error', 'Connection issue while fetching subscriptions.');  // Message when there's a problem fetching subscriptions
        }
    };

    // Run checkUserSession() on component mount
    useEffect(() => {
        checkUserSession();
    }, []);

    // Function to handle saving the edited user info
    const handleSaveChanges = async () => {
        try {
            const response = await axios.post(`${API_URL}?action=updateUserInfo`, {
                name: userData.name,
                email: userData.email,
                phone: userData.phone
            });

            if (!response.data.error) {
                // Success: update user data and exit edit mode
                Alert.alert('Success', 'Your information has been updated');  // Message displayed when user info is successfully updated
                setIsEditing(false);
                setUser({
                    ...user,
                    name: userData.name,
                    email: userData.email,
                    phone: userData.phone
                });
            } else {
                // Error: failed to update user data
                Alert.alert('Error', 'Failed to update user information');  // Message displayed when there is an issue with the update
            }
        } catch (error) {
            // Error handling if there is a connection issue with the backend
            Alert.alert('Error', 'Server connection issue');  // Message displayed when there's a problem with server connection during save
        }
    };

    // Function to handle canceling changes
    const handleCancelChanges = () => {
        setIsEditing(false);  // Cancel the changes and exit edit mode
        setUserData({
            name: user.name,
            email: user.email,
            phone: user.phone
        }); // Reset user data to original values
    };

    // Function to handle logout
    const handleLogout = async () => {
        try {
            await axios.post(`${API_URL}?action=logout`);
            navigation.navigate('Login');  // Redirect user to the Login screen after successful logout
        } catch (error) {
            // Show alert in case of logout failure
            Alert.alert('Error', 'Error during logout');  // Message displayed when there's an issue during logout
        }
    };

    // Add Loading spinner while checking if user is logged in or not
    if (loading) {
        return (
            <View style={styles.loadingContainer}>
                <ActivityIndicator size="large" color="#3498db" />  
            </View>
        );
    }

    // If user is not logged in, navigate to Login page
    if (!user) {
        navigation.navigate('Login');
        return null;
    }

    return (
        <KeyboardAvoidingView
            style={{ flex: 1 }}
            behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        >
            <ScrollView contentContainerStyle={styles.container}>
                <TouchableOpacity onPress={handleLogout} style={styles.logoutButton}>
                    <Text style={styles.logoutButtonText}>Logout</Text>
                </TouchableOpacity>

                <Text style={styles.title}>Welcome, {user.name}</Text>

                <View style={styles.subscriptionsContainer}>
                    <Text style={styles.sectionTitle}>My Subscriptions</Text>
                    {subscriptions.length > 0 ? (
                        subscriptions.map((sub, index) => (
                            <View key={index} style={styles.subscriptionItem}>
                                <Text style={styles.productName}>{sub.product_name}</Text>
                                <Text style={styles.subscriptionText}>Price: <Text style={styles.price}>€{sub.price}</Text></Text>
                                <Text style={styles.subscriptionText}>Start Date: <Text style={styles.date}>{sub.start_date}</Text></Text>
                                <Text style={styles.subscriptionText}>Finish Date: <Text style={styles.date}>{sub.finish_date}</Text></Text>
                            </View>
                        ))
                    ) : (
                        <Text style={styles.noSubscriptionsText}>No subscriptions found.</Text> 
                    )}
                </View>

                <View style={styles.userDataContainer}>
                    <Text style={styles.sectionTitle}>User Info</Text>
                    {isEditing ? (
                        <>
                            <TextInput
                                style={styles.input}
                                placeholder="Name"
                                value={userData.name}
                                onChangeText={(text) => setUserData({ ...userData, name: text })}
                            />
                            <TextInput
                                style={styles.input}
                                placeholder="Email"
                                value={userData.email}
                                onChangeText={(text) => setUserData({ ...userData, email: text })}
                            />
                            <TextInput
                                style={styles.input}
                                placeholder="Phone"
                                value={userData.phone}
                                onChangeText={(text) => setUserData({ ...userData, phone: text })}
                            />
                            <Button title="Save Changes" onPress={handleSaveChanges} />
                            <Button title="Cancel Changes" color="red" onPress={handleCancelChanges} />
                        </>
                    ) : (
                        <>
                            <Text style={styles.userInfoText}>Name: {userData.name}</Text>
                            <Text style={styles.userInfoText}>Email: {userData.email}</Text>
                            <Text style={styles.userInfoText}>Phone: {userData.phone}</Text>
                            <TouchableOpacity onPress={() => setIsEditing(true)}>
                                <Text style={styles.editButton}>Edit</Text>
                            </TouchableOpacity>
                        </>
                    )}
                </View>
            </ScrollView>
        </KeyboardAvoidingView>
    );
};

const styles = StyleSheet.create({
    container: {
        backgroundColor: '#f8f8f8',
        padding: 20,
        marginVertical: 40,
        paddingBottom: 30,
    },
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f8f8f8',
    },
    logoutButton: {
        backgroundColor: '#ff4d4d',
        paddingVertical: 10,
        paddingHorizontal: 20,
        borderRadius: 8,
        alignSelf: 'flex-end',
        marginBottom: 20,
    },
    logoutButtonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    },
    title: {
        fontSize: 28,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 30,
    },
    subscriptionsContainer: {
        marginBottom: 30,
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: '600',
        color: '#333',
        marginBottom: 10,
    },
    subscriptionItem: {
        backgroundColor: '#fff',
        padding: 20,
        borderRadius: 10,
        marginBottom: 15,
        shadowColor: '#000',
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 3 },
        shadowRadius: 5,
        elevation: 5,
        borderLeftWidth: 5,
        borderLeftColor: '#3498db',
    },
    productName: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#333',
    },
    subscriptionText: {
        fontSize: 16,
        color: '#666',
    },
    price: {
        fontWeight: 'bold',
        color: '#3498db',
    },
    date: {
        fontWeight: 'bold',
        color: '#7f8c8d',
    },
    noSubscriptionsText: {
        fontSize: 16,
        color: '#7f8c8d',
        fontStyle: 'italic',
    },
    userDataContainer: {
        backgroundColor: '#fff',
        padding: 20,
        borderRadius: 10,
        shadowColor: '#000',
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 3 },
        shadowRadius: 5,
        elevation: 5,
    },
    userInfoText: {
        fontSize: 16,
        color: '#333',
        marginBottom: 10,
    },
    editButton: {
        color: '#3498db',
        fontSize: 16,
        marginTop: 20,
        textAlign: 'center',
        fontWeight: 'bold',
    },
    input: {
        height: 40,
        borderColor: '#ccc',
        borderWidth: 1,
        borderRadius: 5,
        paddingLeft: 10,
        marginBottom: 15,
    },
});

export default DashboardScreen;
