🧭 Inquiry Management System (Laravel 11 + Blade + Alpine.js)

A clean and modular Inquiry Management System built using Laravel, Blade, Tailwind, and Alpine.js, designed for efficient handling of company inquiries, customers, and industries — with dashboards, CSV import/export, and analytics.

Credentials for admin
admin@webinquries.lk
pw- prudenceweb@123 (default pw - password)

🚀 Features
🗂️ Core Modules

Inquiries CRUD — Create, Edit, Delete, View inquiries with fields:

Inquiry Date

Receiver

Requirement Type

Industry, Company, Customer

Process Level (Received, Quoted, Discussing, Settled, Dropped)

Amount, Contact Info, Additional Notes

Companies CRUD

Customers CRUD

Industries CRUD

Requirement Types CRUD

📊 Dashboard Analytics

Interactive dashboard showing:

Total inquiries, companies, customers

Inquiries by status

Received

Quoted

Discussing

Settled

Dropped

Date-range based inquiries graph (using Chart.js)

Adjustable date filters (From–To range) with Apply & Reset buttons

Automatically updates without page reload (via Blade data binding)

📈 CSV Import & Export

Import inquiries with customer email and contact number.

Export filtered inquiries to CSV directly from the Inquiry table view.

💡 Inquiry Process Levels

Standardized process_level statuses used throughout:

Label	Description
Received	Just received the inquiry
Quoted	Quotation sent
Discussing	Deal on Discussion
Settled	Deal finished
Dropped	Deal dropped
🧱 Tech Stack
Layer	Technology
Backend	Laravel 11 (PHP 8+)
Frontend	Blade Templates + Alpine.js + Tailwind CSS
Database	MySQL
Charts	Chart.js
Authentication	Laravel Fortify
Pagination	Laravel built-in pagination
CSV Handling	Native Laravel Response Stream
⚙️ Installation
1️⃣ Clone the repository
git clone https://github.com/yourusername/inquiry-management-system.git
cd inquiry-management-system

2️⃣ Install dependencies
composer install
npm install && npm run build

3️⃣ Configure .env

Set up your database and app configuration:

cp .env.example .env
php artisan key:generate


Then update:

DB_DATABASE=web_inquiry_v1
DB_USERNAME=root
DB_PASSWORD=

4️⃣ Run migrations & seed
php artisan migrate

5️⃣ Serve the app
php artisan serve


Access it at https://webinquiries.prudence.lk/

📊 Dashboard Overview
Controller

App\Http\Controllers\DashboardController

Handles:

index() → loads all metrics & chart data for the dashboard

stats() → (optional AJAX endpoint) returns data in JSON if needed

Metrics Displayed

Total Inquiries

Total Companies

Total Customers

Status breakdown (Received, Quoted, Discussing, Settled, Dropped)

Inquiries within date range

Line chart showing daily inquiries over time

Blade Layout Example

resources/views/dashboard.blade.php

Uses Tailwind for layout

Chart.js for visualizing daily inquiries

Blade for totals, date filters, and chart data injection

📄 Routes Summary
Route	Controller	Description
/	Redirects to Dashboard	Auth required
/dashboard	DashboardController@index	Dashboard view & chart
/dashboard/stats	DashboardController@stats	(Optional JSON endpoint)
/inquiries	InquiryController	Full CRUD
/companies	CompanyController	CRUD
/customers	CustomerController	CRUD
/industries	IndustryController	CRUD
/requirement-types	RequirementTypeController	CRUD
/settings/*	Volt/Fortify routes	Auth & profile settings
🧾 Inquiry Table Pagination

Pagination added via:

$inquiries = Inquiry::with(['customer','company.industries'])->latest()->paginate(10);


Blade pagination controls:

<div class="mt-1 mx-auto px-6 mb-4">
    {{ $inquiries->links() }}
</div>


Optional “Show All” link can be added:

<a href="{{ route('inquiries.index', ['show_all' => true]) }}">Show All</a>

🧩 CSV Import Fields

When importing inquiries via CSV:

Field	Description
inquiry_date	Date of inquiry
receiver_name	Person receiving inquiry
requirement_type	Type of requirement
industry_id	Industry (optional)
company_id	Company (optional)
customer_name	Customer name
customer_email	Customer email
contact_info	Customer contact number
more_info	Additional notes
amount	Estimated value
process_level	Inquiry status
🧰 Developer Notes

All models use Eloquent relationships:

Inquiry → belongsTo Company, Customer, Industry

Company → hasMany Inquiries

Customer → hasMany Inquiries

Validation handled in controller store() and update() methods.

Dynamic dropdowns (Company/Customer/Industry) implemented with Alpine.js searchable selects.

Both Create & Edit forms share the same dropdown behavior.

🧑‍💻 Maintainer

Author: Yomindu Tharaka
Role: PHP / Laravel Software Engineer
Stack: Laravel, React, Node, MySQL, Tailwind, IoT Systems

🏁 License

This project is open-sourced under the MIT license.﻿# website_inquiry_app

