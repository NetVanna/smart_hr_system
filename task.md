# SmartHR SaaS Roadmap - Professional Architecture

## 🚀 1. Core SaaS Infrastructure (Super Admin - Web)
*The platform owner control panel.*

- [x] **Platform Foundation**: Multi-tenant database, Laravel 12 setup, role-based access.
- [x] **SaaS Registration Workflow**: Company onboarding with Admin creation.
- [x] **Cambodia Localization**: Dual currency (USD/KHR), KHQR generation.
- [x] Khmer Translation for Holidays
- [x] **Subscription Billing**: Manual KHQR payment approval, recurring balances.
- [ ] **SaaS Analytics Dashboard**: MRR, churn rates, company growth charts.
- [ ] **Limits & Controls**: Manage employee counts and storage quotas per company.
- [ ] **Support Ticket System**: Centralized helpdesk for company admins.

## 🏢 2. Company Administration (HR & Management - Web)
*The comprehensive HR management suite.*

- [x] **Employee Management**: Digital IDs, department assignment, profile photos.
- [x] **Core Attendance**: QR Code generation, check-in/out logic.
- [x] **Leave Management**: Approval workflows, multi-tier requests.
- [x] **Payroll Engine**: Automated generation, USD/KHR payslips, PDF export.
- [ ] **Document Vault**: Mandatory contract storage, certificate management.
- [ ] **Asset Tracking**: Laptop/Device assignment and history.
- [ ] **Recruitment (ATS)**: Job postings, applicant tracking, interview scheduling.
- [ ] **Performance (KPIs)**: Monthly/Annual evaluations, training certification.

## 👷 3. Employee Experience (Mobile App - Flutter)
*Self-service portal for field and office staff.*

- [x] **Smart Attendance**: QR Scan with GPS validation & Geofencing.
- [x] **Dashboard**: Real-time stats, today's attendance, news feed.
- [x] **Self-Service (ESS)**: 
    - [x] Multi-Currency Digital Payslip viewer (USD/KHR).
    - [x] Leave Request with image attachments.
    - [ ] Personal Attendance Calendar history.
- [x] **Digital ID & Profile**: 
    - [x] Digital QR ID Card for building access.
    - [ ] Emergency contact & personal info editor.
- [x] **Social & Communication**:
    - [x] Company News & Announcement feed.
    - [x] Real-time Push Notifications (Firebase).

## 🧛 4. "Killer Features" (Premium Enhancements)
*Features that set SmartHR apart in the Cambodian market.*

- [x] **Telegram HR Bot Integration**:
    - [x] Real-time check-in/out alerts for staff.
    - [x] Manager daily summary: "3 Lates, 2 Absences today".
    - [x] Employee balance checks via bot command.
- [x] **AI Attendance Fraud Detection**:
    - [x] Face Spoofing/Liveness detection (Camera focus).
    - [x] Anti-Mock Location detection (Preventing fake GPS).
    - [x] Device Fingerprinting (One account = One device).
- [ ] **Advanced Analytics & Reporting**:
    - [ ] Productivity heatmaps based on local time.
    - [ ] Turnover rate predictive analytics.

---
*Note: [x] = Completed, [/] = In Progress, [ ] = To Do*
