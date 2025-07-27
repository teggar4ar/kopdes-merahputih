# Product Requirements Document (PRD) – “Koperasi Merah Putih” Web App v1.1

*(Version 1.1 – reflects significant r#### FR‑M2: Member Dashboard  
- Savings summ#### FR‑A3: Financial Transaction Management  
- Manual deposit entry: select member & saving type → amount → upload proof (JPG/PNG/PDF, max 2 MB)  
- Process withdrawal requests → mark as "Processed" → auto‑record transaction  
- General ledger view with robust filtering
- **Savings Type Rules:**
  - Simpanan Pokok: Monthly Rp 50,000 (track payment compliance)
  - Simpanan Wajib: One-time Rp 100,000 (verify not already paid)
  - Simpanan Sukarela: Any amount (allow withdrawals)with three types:
  - **Simpanan Pokok (Principal)**: Paid monthly at Rp 50,000
  - **Simpanan Wajib (Mandatory)**: One-time payment of Rp 100,000  
  - **Simpanan Sukarela (Voluntary)**: Variable amounts as desired
- Loan summary (total, paid, next installment date/amount) or "No active loan"  
- Latest 3 announcements  

#### FR‑M3: Savings Management  
- Transaction history table: Date | Type | Description | Debit/Credit | Balance  
- Filters: saving type & date range  
- Voluntary withdrawal request form (amount + note)
- **Business Rules:**
  - Simpanan Pokok: Monthly Rp 50,000 payments
  - Simpanan Wajib: One-time Rp 100,000 payment (cannot be withdrawn)
  - Simpanan Sukarela: Flexible deposits and withdrawalsts updates)*

---

## 1. Introduction

### 1.1 Vision  
Create a transparent, efficient, and accessible digital platform for the “Koperasi Merah Putih” cooperative. This web application will serve as a central hub for all member activities and financial transactions, specifically for residents of Desa Tajurhalang.

### 1.2 Problem Statement  
The cooperative’s current paper‑based processes present significant challenges:

- **Lack of Transparency**  
  Members lack real‑time visibility into savings balances, loan statuses, and overall cooperative health, leading to potential trust deficits.

- **Operational Inefficiency**  
  Administrative tasks—member registration, transaction recording, loan processing, report generation—are time‑consuming, labor‑intensive, and prone to human error.

- **Limited Accessibility**  
  Members must visit the cooperative office during business hours for almost every service, which is impractical and limits engagement.

### 1.3 Solution  
A secure, role‑based web application serving as the system of record (not a payment gateway). All financial transactions recorded reflect actual fund movements in the cooperative’s official bank account. The app will provide:

- **Member Self‑Service Portal** for viewing financial data and submitting requests
- **Administrator Dashboard** for efficient management of all cooperative operations

---

## 2. Goals & Success Metrics

| **Goal**                                             | **Success Metrics**                                                                                                                                              |
|------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Increase Member Engagement & Trust**               | - ≥ 70 % of members log in at least once per month post‑launch.<br>- 50 % reduction in member inquiries (calls/visits) about balances and transaction history in 3 months. |
| **Improve Administrative Efficiency**                | - 70 % reduction in time to verify new members.<br>- 60 % reduction in end‑to‑end loan processing time (submission to decision).<br>- 100 % digital recording of all savings and loan transactions (eliminate manual ledgers). |

---

## 3. Personas & User Roles

### 3.1 Member  
**Who:** Verified residents of Desa Tajurhalang  
**Can:**  
- View their own data  
- Submit loan or withdrawal requests  
- Update profile fields (phone, address)  

**Cannot:**  
- View other members’ data  
- Approve transactions  
- Access the admin panel  

**UI:** Laravel Blade templates with Tailwind CSS  
**Onboarding:** Public registration form → manual admin approval

---

### 3.2 Administrator  
**Who:** Cooperative officers managing daily operations  
**Can:**  
- Manage member lifecycle (approve/reject registrations)  
- Record financial transactions (deposits, withdrawals)  
- Process loans  
- Publish announcements  

**Cannot:**  
- View supervisor‑level audit logs  

**UI:** Laravel Filament admin panel  
**Onboarding:** Accounts created via seeder (no public signup)

---

### 3.3 Supervisor  
**Who:** Oversight officers ensuring transparency  
**Can (Read‑Only):**  
- View all member data & transactions  
- Download system reports  
- View audit logs  

**UI:** Laravel Filament read‑only panel  
**Onboarding:** Accounts created via seeder

---

## 4. Features & Requirements

### 4.1 Core System Features  
- **C1. Authentication**  
  Secure login (email/password), logout, “Forgot Password” with Laravel Breeze. Enforce strong password policies; single sign‑in for all roles.

- **C2. Role‑Based Access Control (RBAC)**  
  Manage roles & permissions with `spatie/laravel-permission`. Protect all routes/actions per user role.

- **C3. Technology Stack**  
  - Member UI: Laravel Blade + Tailwind CSS  
  - Admin & Supervisor UI: Laravel Filament  
  - Authentication: Laravel Breeze  
  - RBAC: spatie/laravel-permission

---

### 4.2 Member Features

#### FR‑M1: Registration & Verification  
- **Form fields:** Full Name, NIK, Phone (WhatsApp), Address, Email, Password  
- **KTP Upload:** JPG/PNG, max 2 MB  
- **Workflow:**  
  1. User submits → status = `pending_verification`, role = `member`  
  2. Email notification to user; dashboard alert for admin  
  3. Admin approves or rejects (with reason)  
  4. On rejection → status = `rejected`; email with reason + resubmission link  

#### FR‑M2: Member Dashboard  
- Savings summary (Principal, Mandatory, Voluntary)  
- Loan summary (total, paid, next installment date/amount) or “No active loan”  
- Latest 3 announcements  

#### FR‑M3: Savings Management  
- Transaction history table: Date | Type | Description | Debit/Credit | Balance  
- Filters: saving type & date range  
- Voluntary withdrawal request form (amount + note)

#### FR‑M4: Loan Management  
- Loan application form: Amount, Term (3/6/12 months), Purpose  
- Interactive Livewire calculator (uses admin‑set annual interest rate)  
- Track application status: Pending, Approved, Rejected, Completed

#### FR‑M5: Profile Management  
- View full profile  
- Edit phone & address (Name & NIK read‑only)  
- Separate “Change Password” form (requires current password)

---

### 4.3 Administrator Features

#### FR‑A2: Member Management  
- View/search/filter all members  
- Verification queue: view KTP images → Approve/Reject (reason required) → email notification  
- Activate/deactivate member accounts  
- Admin‑created members: auto‑active + temporary password generation

#### FR‑A3: Financial Transaction Management  
- Manual deposit entry: select member & saving type → amount → upload proof (JPG/PNG/PDF, max 2 MB)  
- Process withdrawal requests → mark as “Processed” → auto‑record transaction  
- General ledger view with robust filtering

#### FR‑A5: Reporting  
- Date‑range filters  
- Generate & export: Cash Flow, Savings Ledger, Loan Ledger (PDF/Excel)

#### FR‑A7: System Settings  
- Configure annual loan interest rate (percentage) for global use

---

### 4.4 Supervisor Features

- **FR‑S1: Read‑Only Access** to all admin views & member data  
- **FR‑S2: Report Viewing & Download**  
- **FR‑S3: Audit Log Viewer**  
  - Immutable log entries: Timestamp | Admin Name | Action | Target  
  - Filter by Admin, Action Type, Date Range

---

## 5. Non‑Functional Requirements

- **Security:** Protect against CSRF, XSS, SQL Injection; store KTP images in non‑public directory.  
- **Performance:** Page load ≤ 3 seconds; optimize heavy queries (reports).  
- **Usability:** Clean, intuitive, and mobile‑responsive UI.  
- **Data Privacy:** Member data accessible only per role permissions.  
- **Backup & Recovery:** Daily automated off‑site database backups.

---

## 6. Out of Scope (v1.1)

- Direct Payment Gateway Integration  
- Advanced Business Intelligence  
- Automated Late‑Fee Calculation  
- In‑App Messaging/Chat  
