# Citizen Complaints and Engagement System (MVP)

This project is a **Minimum Viable Product (MVP)** of a Citizen Complaints and Engagement System built using PHP and Bootstrap. It is designed to allow citizens to easily submit complaints or feedback about public services, and for government agencies to efficiently respond and manage those complaints.

---

## 🚀 Features

### 👤 **User Functionality**
- Register an account.
- Log in to the system.
- Submit complaints and feedback via an online form.
- View the status of submitted complaints (e.g., Pending, Responded, Solved).

### 🛠️ **Admin Functionality**
- Login with **2-Factor Authentication (OTP via Email)**.
- View submitted complaints from users.
- Respond to complaints (read-only access to other admin responses).
- Cannot delete or edit responses from other admins.

### 👑 **Super Admin Functionality**
- Full system control (CRUD access over users and complaints).
- Add or delete admins.
- Respond to user complaints.
- Access an **AI-powered dashboard** that acts like a chatbot to execute database commands or system actions intelligently.

---

## 🛡️ Authentication

- **2FA for Admins**: After logging in with credentials, admins are required to enter a One-Time Password (OTP) sent to their registered email to access their dashboard.

---

## 📂 Roles and Permissions

| Role        | Submit Complaint | View Own Complaints | Respond to Complaints | Create Admins | Full CRUD | AI Chat Dashboard |
|-------------|------------------|----------------------|------------------------|---------------|-----------|-------------------|
| User        | ✅               | ✅                   | ❌                     | ❌            | ❌        | ❌                |
| Admin       | ❌               | ❌                   | ✅ (Own Only)          | ❌            | ❌        | ❌                |
| Super Admin | ✅               | ✅                   | ✅ (All)               | ✅            | ✅        | ✅                |

---

## 🧱 Tech Stack

- **Backend**: PHP
- **Frontend**: HTML5, CSS3 (custom & Bootstrap)
- **Database**: MySQL
- **Email & OTP**: Configured using Composer packages
- **AI Integration**: Optional chatbot for Super Admin (text-based command executor)

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/bienvenugashema/mvc-system.git
   cd yourproject
