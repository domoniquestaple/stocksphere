# StockSphere – Inventory Management System

**StockSphere** is a lightweight inventory management system designed for small businesses like stores or restaurants. It helps track products, suppliers, purchase orders, and users through a clean and intuitive dashboard.

---

## 🚀 Features

- 📊 Dashboard overview for quick access  
- 📦 Product management (view, add, edit, delete)  
- 🤝 Supplier integration and assignment  
- 🛒 Purchase order creation and status tracking  
- 👤 User administration with role-based permissions  
- 🔐 Admin login and access control  
- 📄 Report generation (with FPDF)

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS (Bootstrap), JavaScript (jQuery)  
- **Backend:** PHP (procedural + PDO)  
- **Database:** MySQL  
- **PDF Export:** [FPDF](http://www.fpdf.org/)

---

## 📁 Project Structure

/
├── css/
│ └── main.css, product.css, ...
├── database/
│ └── all CRUD and connection scripts
├── partials/
│ └── app-sidebar.php, app-topnav.php, etc.
├── uploads/
│ └── product images
├── product-view.php, product-add.php, ...
├── supplier-view.php, ...
├── users-view.php, ...
├── dashboard.php
├── report.php
└── README.md


---

## 🧪 Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/domoniquestaple/StockSphere.git

2. Import the inventory SQL dump into your MySQL server.

3. Set up database credentials in database/db.php.

4. Run the project locally using PHP’s built-in server or XAMPP/MAMP:

php -S localhost:8000

5. Visit the login page:

http://localhost:8000/login.php

---

## 🔐 Default Admin Credentials
Email: admin@ims.com

Password: admin123 (or your defined password)

---

## ⚠️ Notes
Ensure the uploads/products folder is writable for image uploads.

Recommended: PHP 7.4+ and MySQL 5.7+.

All user actions are permission-based.

---

## 📄 License
MIT License — free to use and modify.

---

## 👤 Author
Built with ❤️ by Domonique Staple
