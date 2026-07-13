# SmartHR SaaS - Installation Guide

## Backend Setup (Laravel 12)
1. **Prerequisites**: PHP 8.2+, MySQL, Composer.
2. **Installation**:
   ```bash
   composer install
   php artisan key:generate
   php artisan storage:link
   ```
3. **Database**:
   - Create a database named `smarthr`.
   - Update `.env` with DB credentials and `APP_URL`.
   ```bash
   php artisan migrate --seed
   ```
4. **Running**:
   ```bash
   php artisan serve
   ```
5. **Default Credentials**:
   - **Super Admin**: `superadmin@smarthr.com` / `password123`
   - **Company Admin**: `admin@demotech.com` / `password123`
   - **Employee (Mobile)**: `john.doe@demotech.com` / `password123`

## Mobile App Setup (Flutter)
1. **Prerequisites**: Flutter SDK, Android Studio/VS Code.
2. **Configuration**:
   - Open `mobile_app/lib/core/api_client.dart`.
   - Update `baseUrl` to your computer's IP or `10.0.2.2` for Android Emulator.
3. **Running**:
   ```bash
   cd mobile_app
   flutter pub get
   flutter run
   ```

## Features Implemented
- **Multi-Tenancy**: Automated data isolation per company.
- **Attendance**: QR Code and Face Recognition (placeholder).
- **HR Core**: Departments, Employees, Leaves, Payroll, Documents.
- **SaaS**: Company management and Subscription billing (KHQR placeholder).
- **Mobile**: Cross-platform app using Provider and Sanctum Auth.
