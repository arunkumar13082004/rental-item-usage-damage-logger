# Rental Item Usage & Damage Logger

A PHP and MySQL based rental management system that tracks item condition at checkout and return, automatically detects damages, calculates overdue penalties, and ensures fair billing.

This project was developed as part of the **Lumos Learning – Final Round Assignment**.

## Features

- Customer management
- Inventory management with maintenance mode
- Rental checkout with condition inspection
- Return flow with condition comparison
- Automatic damage detection
- Overdue penalty calculation
- Transparent charge breakdown
- Customer rental and damage history
- Admin damage review and override
- Item health timeline (inspection history)
- Automatic maintenance handling for damaged items
- Centralized dashboard navigation

## Tech Stack

- PHP (Server-side rendering)
- MySQL
- Bootstrap 5
- HTML5
- XAMPP (Apache + MySQL)

## Project Structure
```md
rental-item-logger/
│
├── config/
│   └── db.php                # Database connection
│
├── partials/
│   ├── header.php            # Common header
│   └── footer.php            # Common footer
│
├── docs/
│   └── database_schema.sql   # Database structure only
│
├── dashboard.php
├── customer_add.php
├── customer_profile.php
├── item_add.php
├── inventory.php
├── item_status.php
├── item_history.php
├── checkout.php
├── active_rentals.php
├── return.php
├── damage_review.php
├── inspection_templates.php
│
├── .env.example              # Environment variable template
├── README.md

```

---

## Prerequisites

Before running the project, ensure you have:

- XAMPP installed
- Apache and MySQL services running
- A modern web browser (Chrome recommended)

## How to Run the Project Locally

### Step 1: Clone or Download the Project

Using Git:

```bash
git clone https://github.com/arunkumar13082004/rental-item-usage-damage-logger.git
```

Or download the ZIP file and extract it.


---

### Step 2: Place Project in XAMPP Directory

Move the project folder to:

C:\xampp\htdocs\


Final path should be:

C:\xampp\htdocs\rental-item-logger


---

### Step 3: Create the Database

1. Open browser and go to:
http://localhost/phpmyadmin

2. Create a new database named:
rental_logger

3. Import the database schema from:
docs/database_schema.sql

### Step 4: Configure Database Connection

Database credentials are excluded from version control for security.

1. Refer to `.env.example`
2. Create a `.env` file (optional for local use)

Example:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=rental_logger
```

---

### Step 5: Run the Application

Open browser and navigate to:
http://localhost/rental-item-logger/dashboard.php

The dashboard will load if setup is successful.

## Application Flow Overview

1. Add customers
2. Add rental items
3. Perform rental checkout with condition inspection
4. View active rentals
5. Perform return inspection
6. Automatically detect damages
7. Calculate overdue penalties (if any)
8. Display transparent charge breakdown
9. Review and manage damage charges
10. View customer and item history

## Database Schema Overview

The application uses a relational MySQL database with the following core tables:
### 1. customers
Stores customer information.

- customer_id (Primary Key)
- name
- phone (unique)

### 2. items
Stores rentable inventory items.

- item_id (Primary Key)
- item_code (unique)
- name
- category
- deposit_amount
- daily_rate
- status (available / rented / maintenance)

### 3. rentals
Tracks rental transactions.

- rental_id (Primary Key)
- customer_id (Foreign Key)
- item_id (Foreign Key)
- start_date
- end_date
- rental_status (active / returned)
- returned_at

### 4. rental_inspections
Stores condition snapshots at checkout and return.

- inspection_id (Primary Key)
- rental_id (Foreign Key)
- inspection_type (checkout / return)
- condition_json
- inspected_at

### 5. damage_claims
Stores detected damage and calculated charges.

- claim_id (Primary Key)
- rental_id (Foreign Key)
- customer_id (Foreign Key)
- item_id (Foreign Key)
- damage_summary
- charge_amount

Note: Only database structure is committed. No real data is stored in the repository.


## Important Design Decisions

- Inspection data is stored as JSON snapshots to support flexible checklists.
- The same inspection checklist is used at checkout and return for accurate comparison.
- Overdue penalties are calculated as a percentage to avoid double billing.
- Damaged items are automatically moved to maintenance.
- Admins cannot modify the status of rented items.
- Rental, inspection, and billing data are stored separately for auditability.
- The project is implemented as a modular monolith for clarity and simplicity.



