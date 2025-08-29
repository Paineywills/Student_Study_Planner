# 📚 Student Study Planner

A simple PHP & MySQL-based web app that helps students manage their tasks and study plans.  
Built with **PHP, MySQL, Bootstrap, and custom CSS**.

---

## 🚀 Features
- User authentication (signup, login, logout)
- Dashboard with quick stats:
  - Total tasks
  - Completed tasks
  - Pending tasks
- Task management:
  - Add new tasks
  - Edit existing tasks
  - Delete tasks
  - Mark tasks as completed
- Profile management (update name, email, password)
- Responsive design with Bootstrap + custom styling

---

## 🛠️ Installation Guide

### 1. Requirements
- [XAMPP](https://www.apachefriends.org/) (or WAMP/LAMP/MAMP)
- PHP 8+
- MySQL

### 2. Setup
1. Clone this repository:
   ```bash
   git clone https://github.com/Paineywills/Student_Study_Planner.git
2. Move the project folder into your XAMPP htdocs directory:
   C:/xampp/htdocs/Student_Study_Planner
3. Start Apache and MySQL from the XAMPP Control Panel.

### 3. Database Setup
1. Open phpMyAdmin in your browser:
   http://localhost/phpmyadmin
2. Create a new database named study_planner (or any name you prefer).
3. Import the provided study_planner.sql
 file (found in the project folder).
4. Update database credentials in config.php if needed:

### 4. Run the App
   Open in your browser: http://localhost/Student_Study_Planner/

```   
   📂 Project Structure

   Student_Study_Planner/
│── config.php                  # Database connection
│── login.php                   # Login page
│── register.php                # User registration
│── dashboard.php               # User dashboard
│── tasks.php                   # Manage tasks
│── add_task.php                # Add new task
│── edit_task.php               # Edit task
│── delete_task.php             # Delete task
│── profile.php                 # User profile
│── logout.php                  # Logout
│── css/
│    └── style.css              # Custom styles
│── js/
│    └── script.js              # JS (future use)
│── screenshots/                # App screenshots
│── student_study_planner.sql   # Database schema (to import in phpMyAdmin)
```
----

📌 Notes

Default login: create your own account using the Register page.

Runs locally using XAMPP/WAMP/LAMP — no online deployment included.

No license is provided (for personal use only).

----

## 👨‍💻 Author

Mark Adjei Tutu
