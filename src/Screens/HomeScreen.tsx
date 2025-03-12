import { useNavigation } from '@react-navigation/native';
import React from 'react';
import {
  StatusBar,
  StyleSheet,
  Text,
  View,
  TouchableOpacity,
  useColorScheme
} from 'react-native';
import { Colors } from 'react-native/Libraries/NewAppScreen';

function HomeScreen(): React.JSX.Element {
  const isDarkMode = useColorScheme() === 'dark';
  const backgroundStyle = {
    backgroundColor: isDarkMode ? Colors.darker : Colors.lighter,
  };
  const navigation = useNavigation();

  return (
    <View style={[styles.container, backgroundStyle]}>
      <StatusBar
        barStyle={isDarkMode ? 'light-content' : 'dark-content'}
        backgroundColor={backgroundStyle.backgroundColor}
      />
      <Text style={styles.welcomeText}>Welcome to the Khalil Zerzour test</Text>
      
      {/* Styled button for the Login page */}
      <TouchableOpacity
        style={styles.button}
        onPress={() => navigation.navigate("Login")}
      >
        <Text style={styles.buttonText}>Go to Login</Text>
      </TouchableOpacity>

      {/* Styled button for the Dashboard page */}
      <TouchableOpacity
        style={styles.button}
        onPress={() => navigation.navigate("Dashboard")}
      >
        <Text style={styles.buttonText}>Go to Dash</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center', 
    alignItems: 'center',
    padding: 20,
  },
  welcomeText: {
    fontSize: 22,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 20, 
  },
  button: {
    backgroundColor: 'blue', 
    paddingVertical: 12,    
    paddingHorizontal: 30,    
    borderRadius: 8,       
    marginBottom: 15,            
    elevation: 3,                
    shadowColor: '#000',  
    shadowOffset: { width: 0, height: 2},
    shadowOpacity: 0.2,   
    shadowRadius: 3,     
  },
  buttonText: {
    color: '#fff',    
    fontSize: 16,     
    fontWeight: 'bold',          
    textAlign: 'center',         
  },
});

export default HomeScreen;
