# Community Chamas Platform Friends Support Network(FSN) (Login • Dashboard • Account Settings)  

<p align="center">
  <a href="https://www.jminnovatechsolution.co.ke" target="_blank">
    <img src="https://www.jminnovatechsolution.co.ke/assets/img/fsn.png" width="300" alt="Friends Support Network Logo">
  </a>
</p>

## Overview

## FSN Authentication Support

### 1.Google-Registered Accounts
- Supports users registered via Google OAuth
- Allows conversion from Google login to password-based login
- Automatically disables Google-only restriction when password is set

#### 🔑 Google OAuth Setup Guide
To enable the Google Sign-in feature, follow these steps to configure your environment:

**1. Obtain Credentials from Google Cloud Console**
* Go to the [Google Cloud Console](https://console.cloud.google.com/).
* **Create a New Project** and navigate to **APIs & Services > Credentials**.
* Click **Create Credentials > OAuth client ID**.
* Select **Web application** as the application type.
* **Authorized redirect URIs:** Add `https://yoururl.com/auth/google/callback` (or your production URL).


**2. Update Environment Variables (`.env`)**
Copy the Client ID and Secret into your project's `.env` file:

## Installation & Setup

### 1. **Clone the repository**
```bash
git clone git@github.com:Jmuthoga/Authentication-Account-Management-System.git
cd -Authentication-Account-Management-System
```
### 2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run dev
   ```
### 3. **Configure .env**
  ```bash
    # App Configuration
    APP_NAME=Authentication
    APP_ENV=local
    APP_KEY=base64:YOUR_APP_KEY
    APP_DEBUG=true
    APP_URL=http://localhost:8000
    WEBSITE_NAME="Authentication & Account Management System"
    
    # Database
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    
    # Mail for notifications
    MAIL_MAILER=smtp
    MAIL_HOST=your_mail_host
    MAIL_PORT=your_mail_port
    MAIL_USERNAME=your_username
    MAIL_PASSWORD=your_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="info@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

    # Google OAuth Credentials
    GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
    GOOGLE_CLIENT_SECRET=your-client-secret
    GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```
### 4. **Run Migrations & Seeders**
 ```bash
   php artisan migrate --seed
```
### 5. **Serve the Application**
```bash
    php artisan serve
```

## Contributing
- Fork the repository, make your changes, and submit a pull request
- Follow PSR coding standards and keep commits clean

## Contact

<p>
  <a href="https://wa.me/254791446968" target="_blank">
    <img src="https://img.icons8.com/color/48/000000/whatsapp--v1.png" width="24" alt="WhatsApp"/> 
    Chat with us on WhatsApp
  </a>
</p>



## License

This project is licensed under the **MIT License**.  
See the [LICENSE](LICENSE) file for details.



