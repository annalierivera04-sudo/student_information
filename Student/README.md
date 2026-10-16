# Student Information System (SIS)

A full-stack web application built for the Web Application Development Final Project.

## Tech Stack
- **Frontend:** HTML5, CSS3, Bootstrap 5.3.3, Vanilla JavaScript
- **Backend:** PHP (Procedural with MySQLi)
- **Database:** MySQL

## Features
- **User Authentication:** Login and Registration with `password_hash()` and secure sessions.
- **Role-Based Access Control:** Distinct Admin and Student portals.
- **Full CRUD Operations:** Create, Read, Update, and Delete students and courses.
- **Client-Side JavaScript:** Real-time search filters and dynamic modal binding.
- **Student Dashboard:** View grades, class schedules, and attendance.

## Test Credentials (for Grading)
- **Admin:**
  - Username: `admin`
  - Password: `admin123`
- **Student:**
  - Username: `student1`
  - Password: `student123`

## Installation
1. Import `/database/schema.sql` into phpMyAdmin.
2. Configure credentials in `config/database.php`.
3. Open `login.php` in your browser.