# 📱 React Native & PHP Project with WhatsApp Verification Code in Login

This is a full-stack project using **React Native** for the frontend and **PHP** for the backend.

Welcome to your **React Native** project! This project is bootstrapped using [`@react-native-community/cli`](https://github.com/react-native-community/cli).

---

## 🚀 Getting Started

### 📌 Prerequisites
Before proceeding, make sure you have set up your environment by following the [React Native Setup Guide](https://reactnative.dev/docs/environment-setup).

### 1️⃣ Start Metro
Metro is the JavaScript build tool for React Native. Start it by running:
```sh
# Using npm
npm start

# OR using Yarn
yarn start
```

### 2️⃣ Build and Run the App
Open a new terminal and run one of the following commands:

#### Android 📱
```sh
npm run android  # OR  yarn android
```

#### iOS 🍏 (Mac only)
First, install CocoaPods dependencies (only on first setup or after native updates):
```sh
bundle install
bundle exec pod install
```
Then run:
```sh
npm run ios  # OR  yarn ios
```

### 3️⃣ Start the Backend & Database Setup 🗄️
To start the backend server and set up the database:
1. Navigate to the backend folder:
   ```sh
   cd backend
   ```
2. Install WhatsApp API SDK for Verification 📩:
   ```sh
   composer require whapi-cloud/whatsapp-api-sdk-php
   ```
   This will allow you to send verification codes via WhatsApp.
3. Start the MySQL server:
   ```sh
   mysql -u root -p
   ```
4. Start the PHP server:
   ```sh
   php -S localhost:8000 -t public
   ```
4. To create the database and tables, navigate to:
   ```sh
   cd backend/config
   ```
   and run:
   ```sh
   php setup_db.php
   ```
5. To load initial data, re-run:
   ```sh
   php backend/config
   ```
    and run:
   ```sh
   php LoadData.php
   ```
6. Start the PHP server:
   ```sh
   php -S localhost:8000 -t public
   ```

### 4️⃣ Modify Your App 🎨
Edit `App.tsx` in your preferred editor. Thanks to **Fast Refresh**, changes will be reflected instantly. To manually reload:
- **Android**: Press <kbd>R</kbd> twice or open the **Dev Menu** (<kbd>Ctrl</kbd> + <kbd>M</kbd> / <kbd>Cmd ⌘</kbd> + <kbd>M</kbd>).
- **iOS**: Press <kbd>R</kbd> in the iOS Simulator.

---

## 🎯 Next Steps
- Learn more about [React Native](https://reactnative.dev/docs/getting-started).
- If you want to integrate this project into an existing app, check out the [Integration Guide](https://reactnative.dev/docs/integration-with-existing-apps).
- If you run into issues, check the [Troubleshooting Guide](https://reactnative.dev/docs/troubleshooting).

---

## 🛠 Useful Commands
| Command             | Description                           |
|--------------------|----------------------------------|
| `npm start` / `yarn start` | Start Metro (JS bundler) |
| `npm run android` / `yarn android` | Run app on Android |
| `npm run ios` / `yarn ios` | Run app on iOS (Mac only) |
| `npm run lint` / `yarn lint` | Lint your code |
| `npm run test` / `yarn test` | Run tests |

---

## 📝 License
This project is licensed under the [MIT License](LICENSE).

Happy coding! 🚀

