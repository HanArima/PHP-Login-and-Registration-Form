# PHP-Login-and-Registration-Form
A secure PHP registration and login system using MySQL. Includes user registration with validation, password hashing, login authentication, dashboard, and logout. Comes with full source code, folder structure, CSS styling, and SQL schema. Ideal for beginners learning user authentication.
This repository contains a complete, secure registration and login system built using **PHP**, **MySQL**, **HTML/CSS**, and **XAMPP**. It includes:

- User registration form with validation
- Secure password hashing
- Login system with authentication
- Dashboard for logged-in users
- Session-based logout functionality
- Structured folder layout and reusable PHP scripts

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- [XAMPP](https://www.apachefriends.org/index.html) (Includes Apache, PHP, MySQL, and phpMyAdmin)
- Basic knowledge of PHP, HTML, CSS, and MySQL
- Any text/code editor (e.g., VS Code, Sublime Text)


## Environment Setup

1. **Install XAMPP**  
   Download and install XAMPP from the official website. This will install Apache server, MySQL, and phpMyAdmin on your local machine.

2. **Start Apache and MySQL**  
   Open the **XAMPP Control Panel**, and click **Start** for both **Apache** and **MySQL**. Both indicators should turn green.

3. **Check phpMyAdmin**  
   Go to your browser and type:  
   `http://localhost/phpmyadmin`  
   This opens the MySQL interface.

## 📦 Installation Steps

1. **Download or Clone this Repository**
   ```bash
   git clone https://github.com/your-username/registration-login-system-php.git
Move Project to htdocs
Copy the project folder into the htdocs directory inside your XAMPP installation.
Example path: C:\xampp\htdocs\registration-login-system-php

#### Create the Database

Open http://localhost/phpmyadmin

Click New, name the database (e.g., user_details)

Import the included registered_users.sql file (if available)

Or create a table using this SQL:

CREATE TABLE registered_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    country VARCHAR(50),
    phone VARCHAR(15)
);
Run the Application
Open your browser and visit:
http://localhost/registration-login-system-php/Registration System/registration.php

#### Folder Structure

registration-login-system-php/
│
├── Registration System/
│   ├── registration.php
│   ├── config.php
│
├── Login System/
│   ├── login_page.php
│   ├── authentication.php
│   ├── dashboard.php
│   ├── logout.php
│
├── css/
│   └── style.css
│
├── images/
│   └── intellipaat_logo.png

#### Features
Secure password storage using password_hash()

Input validation with error handling

SQL injection protection using prepared statements

Session management with $_SESSION

Clean and responsive UI using CSS

#### Contributing
Feel free to fork this repo, make changes, and create a pull request. Contributions are welcome!

#### License
This project is open-source and available under the MIT License.
