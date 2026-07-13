# SmartHR User Manual

Welcome to the comprehensive guide for the **SmartHR SaaS Platform**. This manual is divided by user roles to help you navigate the system efficiently.

---

## 🚀 1. Super Admin (Website)
**Target Audience:** System owners and platform administrators.
**Platform:** Web (PC/Laptop)

### 1.1 Managing Companies
1.  **Login**: Access the admin portal at `/login`.
2.  **View Companies**: Go to the **Companies** menu to see all registered businesses.
3.  **Create Company**: Click **"Add Company"** to manually onboard a new client.
    - Set the **Latitude/Longitude**: This defines the office center for GPS geofencing.
    - Set the **Geofence Radius**: The allowed distance (in meters) for employee check-ins.
4.  **Edit Details**: Update company contact info or localization settings (Base Currency/Exchange Rate).

### 1.2 Subscription Approvals (Cambodian KHQR Flow)
1.  **View Subscriptions**: Go to the **Subscriptions** menu.
2.  **Verify Receipts**: Look for records with a 📄 **"View Receipt"** button. This shows the screenshot uploaded by the client.
3.  **Approve**: Once you verify the funds in your bank, click **Edit** on the subscription and change the status to **"Approved"**. This automatically activates the company's account.

---

## 🏢 2. Company Admin (Website)
**Target Audience:** HR Managers and Business Owners.
**Platform:** Web (PC/Laptop)

### 2.1 Registration & Payment
1.  **Sign Up**: Go to the **Register** page and enter your business details.
2.  **Payment**: After registration, you will be redirected to the **Payment Page**.
    - **Scan KHQR**: Use your ABA/Bakong app to scan the displayed KHQR code.
    - **Upload Proof**: Take a screenshot of your successful transaction and upload it in the "Receipt" field.
3.  **Wait for Activation**: Your dashboard will unlock once the Super Admin approves your payment.

### 2.2 HR Setup
1.  **Departments**: Create your organizational structure (e.g., IT, Accounting, Sales).
2.  **Employee Onboarding**:
    - Add employees individually.
    - Set their basic salary in **USD**.
3.  **Attendance QR**: Go to **Employees** and click the **QR Code** icon next to any employee. This QR code is what they will scan to check in. (You can print these or show them on a tablet at the office entrance).

### 2.3 Payroll & Localization
1.  **Exchange Rate**: Set your preferred USD/KHR exchange rate in your Company Settings.
2.  **Generate Payroll**: At the end of the month, go to **Payrolls** -> **Create New**.
3.  **Dual-Currency Display**: The system will automatically show the Net Salary in both **USD** and **Khmer Riel (៛)** on the dashboard and payslips.

---

## 📱 3. Employee (Mobile App)
**Target Audience:** General Staff.
**Platform:** Flutter Mobile App (iOS/Android)

### 3.1 Daily Attendance
1.  **Login**: Use the credentials provided by your HR manager.
2.  **Check-In/Out**:
    - Tap the **"Scan QR"** button.
    - Scan the QR code provided at your office.
3.  **GPS Verification**: The app will automatically capture your location. 
    - **Note**: If you are outside the office geofence (e.g., at home), your attendance will be flagged for HR review.

### 3.2 Leave & Self-Service
1.  **Request Leave**: Go to the "Leave" section, choose your dates, and upload a reason or photo (e.g., medical certificate).
2.  **View Payslips**: Access your monthly payslips directly in the app. They will show your salary in both USD and Riel.
3.  **Documents**: View company policies or personal contracts shared by HR in the "Documents" tab.
