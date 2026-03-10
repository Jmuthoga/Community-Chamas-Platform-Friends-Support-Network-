# Community Chamas Platform ChamaCare (Login • Dashboard • Account Settings)  

<p align="center">
  <a href="https://www.jminnovatechsolution.co.ke" target="_blank">
    <img src="https://www.jminnovatechsolution.co.ke/assets/img/fsn.png" width="300" alt="ChamaCare">
  </a>
</p>

## Overview

## Contributing

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

## System Modules
The ChamaCare sidebar reflects the modules in the system. Here’s an overview:

### 1.Dashboard
- Provides a quick overview of financials, members, contributions, loans, and announcements.

### 2.Members Management
- Members: View, add, edit, suspend, or delete members based on permissions.

### 3.Contributions
- Contribution Agreement: Define the rules for contributions.
- Make Payment: Submit monthly contributions via integrated payment methods.
- Payment History: View all personal contribution transactions.

### 4.Treasurer Desk
- Financial Overview: Dashboard for treasurers.
- Transactions: View all financial transactions in the chama.
- Expenses: Record and manage expenditures.
- Financial Reports: Generate detailed reports of financial activities.

### 5.Announcements
- All Announcements: View all notices for members.
- Create Announcement: Post new announcements.
- Events: View all upcoming events.
- Create Event: Schedule new events for the chama.

### 6.Loans Management
- Apply for Loan: Submit loan requests.
- My Loans: View loan status and history.
- Loan Approvals: Approve or reject loans (for admins).
- Loan Repayments: Record repayment transactions.
- Loan Calculator: Calculate repayment schedules.
- Loan Settings: Configure interest rates, limits, and policies.

### 7.Reports
- Contribution Reports: Overview of member contributions.
- Financial Reports: Detailed reports of income, expenses, and balances.
- Loan Reports: Track all loans issued and repayments.
- Loan Repayment Reports: Monitor repayment schedules and collections.
- Announcement & Event Reports: Analytics on communications.
- (Optional: Members Reports, Summary Reports, etc.)

### 8.Settings
- Website & General Settings: Update website info, style, socials, and contact details.
- Currency Settings: Add, update, or delete supported currencies.
- Roles & Permissions: Define user roles and assign permissions.
- Notification Settings: Configure email and SMS notifications.
- Invoice & Custom Settings: Customize invoices and system-specific configurations.

## Installation & Setup

### 1. **Clone the repository**
```bash
git clone git@github.com:Jmuthoga/Community-Chamas-Platform-Friends-Support-Network-.git
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
    APP_NAME="ChamaCare"
    APP_ENV=local
    APP_KEY=base64:YOUR_APP_KEY
    APP_DEBUG=true
    APP_URL=http://localhost:8000
    WEBSITE_NAME="ChamaCare"
    
    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug 

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

    # Africa's Talking Sandbox
    SMS_SANDBOX=true
    SMS_SENDER=Sandbox
    SMS_API_KEY=YOUR_API_KEY
    SMS_USERNAME=sandbox

    # MPESA Payments 
    MPESA_ENV=production
    MPESA_SHORTCODE=
    MPESA_CONSUMER_KEY=
    MPESA_CONSUMER_SECRET=
    MPESA_PASSKEY=
    MPESA_CALLBACK_URL=https://${APP_URL}/api/payment/callback
    MPESA_STK_CALLBACK_URL=https://${APP_URL}/api/payment/stk-callback
    MPESA_VALIDATION_URL=https://${APP_URL}/api/payment/validate

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



