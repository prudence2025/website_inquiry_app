# 🧭 Inquiry Management System

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php)
![Frontend](https://img.shields.io/badge/Alpine.js-Blade-0D9488?style=for-the-badge&logo=almalinux)
![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)

A clean and modular Inquiry Management System built with Laravel 11, Blade, and Alpine.js. Designed for efficient handling of company inquiries, customers, and industries—complete with a dynamic dashboard, CSV import/export, and analytics.



---

## 🚀 Key Features

### 🗂️ Core CRUD Modules
Full Create, Read, Update, and Delete functionality for all key business areas:
* **Inquiries:** Track inquiries with status, amount, receiver, and linked entities.
* **Companies:** Manage company profiles.
* **Customers:** Manage customer contact information and history.
* **Industries:** Categorize companies and inquiries.
* **Requirement Types:** Define the types of services or products requested.

### 📊 Dashboard & Analytics
A dynamic, single-page dashboard to visualize key metrics:
* At-a-glance totals for inquiries, companies, and customers.
* Donut chart breaking down inquiries by their current status.
* Interactive line chart (using **Chart.js**) showing inquiry volume over time.
* Date-range filters (From/To) that update all dashboard widgets instantly without a page reload.

### 📈 CSV Import & Export
* **Import:** Bulk-import new inquiries from a CSV file.
* **Export:** Export the current, filtered view of the inquiries table directly to a CSV.

### 💡 Standardized Inquiry Pipeline
Track an inquiry's lifecycle with standardized `process_level` statuses:

| Label | Description |
| :--- | :--- |
| **Received** | Just received the inquiry |
| **Quoted** | Quotation has been sent |
| **Discussing** | The deal is under discussion |
| **Settled** | The deal has been finalized and won |
| **Dropped** | The deal has been lost or cancelled |

---

## 🧱 Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | Laravel 12 (PHP 8.3+) |
| **Frontend** | Blade Templates, Alpine.js, Tailwind CSS |
| **Database** | MySQL |
| **Authentication** | Laravel Fortify |
| **Charts** | Chart.js |
| **Dev Tools** | Vite |

---

## ⚙️ Getting Started

Follow these steps to get the project up and running on your local machine.

### 1. Prerequisites
* PHP 8.3+
* Composer
* Node.js & npm
* A local database (e.g., MySQL)

### 2. Installation
1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/yourusername/inquiry-management-system.git](https://github.com/yourusername/inquiry-management-system.git)
    cd inquiry-management-system
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Set up your environment:**
    ```bash
    cp .env.example .env
    ```
    Inside your new `.env` file, add your database credentials:
    ```ini
    DB_DATABASE=web_inquiry_v1
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

4.  **Generate app key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run migrations and seed the database:**
    * The migration will create all necessary tables.
    * The seeder will create a default admin user and sample data.
    ```bash
    php artisan migrate --seed
    ```

6.  **Build frontend assets in order    :**
    ```bash
    php artisan livewire:publish --assets
    php artisan view:clear
    php artisan route:clear
    php artisan config:clear
    php artisan cache:clear
    php artisan optimize:clear
    npm run build
    php artisan optimize:clear
    php artisan optimize
    ```

7.  **Serve the application:**
    ```bash
    php artisan serve
    ```
    Your application will be running at `http://127.0.0.1:8000`.

---

## 🔑 Usage & Admin Credentials

After running the database seeder (`php artisan migrate --seed`), you can log in with the default administrator account:

* **Email:** `admin@webinquries.lk`
* **Password:** `password`

> **Note:** It is strongly recommended to change this password immediately after your first login.

---

## 🧰 Technical Notes

### CSV Import Fields
When preparing a CSV for import, ensure it has the following columns:

| Field | Description |
| :--- | :--- |
| `inquiry_date` | Date of inquiry (e.g., YYYY-MM-DD) |
| `receiver_name` | Person receiving the inquiry |
| `requirement_type` | Type of requirement |
| `industry_id` | Industry ID (optional) |
| `company_id` | Company ID (optional) |
| `customer_name` | Customer's full name |
| `customer_email` | Customer's email address |
| `contact_info` | Customer's contact number |
| `more_info` | Additional notes (optional) |
| `amount` | Estimated value of the deal |
| `process_level` | Inquiry status (e.g., Received, Quoted) |

### Eloquent Relationships
* `Inquiry` → `belongsTo` (Company, Customer, Industry)
* `Company` → `hasMany` (Inquiries)
* `Customer` → `hasMany` (Inquiries)

### Authentication
User authentication, registration, and password reset are handled by **Laravel Fortify**. Profile settings are managed by **Laravel Jetstream/Volt**.

---

## 🧑‍💻 Maintainer

* **Author:** Yomindu Tharaka
* **Role:**  Software Engineer
* **Stack:** Laravel, React, Node, MySQL, Tailwind, IoT Systems

---

## 🏁 License

This project is open-sourced under the **MIT License**.
