import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import HomeScreen from './src/Screens/HomeScreen';
import LoginScreen from './src/Screens/LoginScreen';
import VerifyScreen from './src/Screens/VerifyScreen';
import DashboardScreen from './src/Screens/DashboardScreen';

// Create a stack navigator instance
const Stack = createNativeStackNavigator();

const App = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Home">
        {/* Home screen: Navigation to the home page */}
        <Stack.Screen
          name="Home"
          component={HomeScreen}
          options={{ headerShown: false }} // Hide the header for this screen
        />
        
        {/* Login screen: Navigation to the login page */}
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{ headerShown: false }} 
        />
        
        {/* Verify screen: Navigation to the verification page */}
        <Stack.Screen
          name="VerifyCode"
          component={VerifyScreen}
          options={{ headerShown: false }} 
        />
        
        {/* Dashboard screen: Navigation to the dashboard page */}
        <Stack.Screen
          name="Dashboard"
          component={DashboardScreen}
          options={{ headerShown: false }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default App;
