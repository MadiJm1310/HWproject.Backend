# 🚗 Driving Experience Management Web Application
##  Author:

**Madina Mammadova L2**  
Computer Science Student  
Université Française d’Azerbaïdjan (UFAZ)

## Link to the application: https://madina0mammadova.alwaysdata.net/HWproject.Backend/index.php

## 📌 Project Overview

This project is a **single-user web application** developed with **PHP and MySQL** to manage and analyze supervised driving experiences.  
The application allows users to record driving experiences, store them in a relational database, and visualize summaries and statistics through tables and charts.

No PHP or JavaScript frameworks are used. 

---

## ⚙️ Technologies Used

### Back-End
- PHP 8
- PDO (PHP Data Objects)
- MySQL (Alwaysdata hosting)
- SQL joins and aggregation functions
- Many-to-many relationships

### Front-End
- HTML5 (semantic structure)
- CSS3 (Flexbox layout, responsive design)
- Vanilla JavaScript
- Chart.js for statistics and graphs

### Tools
- Alwaysdata (remote hosting & phpMyAdmin)
- GitHub (version control)
- W3C-compliant HTML & CSS

---

## ✨ Main Features

### Driving Experience Form
- Date, start time, end time
- Distance in kilometers
- Weather condition
- Road condition
- Parking type
- Emergency types (multiple selection – many-to-many)
- Input validation and user feedback

### Database Design
- Normalized relational schema
- Separate tables for each driving variable
- Junction table for emergency types
- Optimized SQL queries using joins

### Summary & Statistics
- Summary table with readable values (not IDs)
- Total distance traveled
- Interactive charts:
  - Distance by weather (bar chart)
  - Road conditions distribution (pie chart)
  - Parking types distribution (pie chart)
  - Emergency types frequency (pie chart)

---

## 🗂️ Project Structure:
/
├── index.php                # Driving experience form

├── experience_save.php      # PDO insert + transaction

├── summary.php              # Summary table + statistics

├── includes/

│   ├── config.php           # Database parameters (password hidden)

│   ├── db.php               # PDO connection (singleton)

│   └── functions.php        # Validation & helpers

├── assets/

│   ├── css/

│   │   └── styles.css

│   └── js/

│       └── (Chart logic inline in summary.php)

├── sql/

│   └── schema.sql           # Database schema

└── README.md

---

## 🔐 Security & Best Practices

- PDO used for all database interactions
- Prepared statements prevent SQL injection
- Database credentials isolated in configuration file

---

## 📊 Educational Objectives

This project demonstrates:
- PHP–MySQL interaction using PDO
- Relational database modeling
- SQL joins and aggregations
- Many-to-many relationship handling
- Dynamic data visualization
- Responsive UI design
- Full-stack development without frameworks

---

## 🌐 Deployment

The application is hosted on **Alwaysdata** with a remote MySQL database.  
It is fully functional and accessible via the provided URL (no authentication required).

---
## ✅ Conclusion

This backend application demonstrates the use of PHP, PDO, MySQL, and JavaScript to build a complete, functional web application with secure database access and meaningful data visualization.




