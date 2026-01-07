# Rental Item Usage & Damage Logger

A PHP and MySQL based rental management system that tracks item condition at checkout and return, detects damages automatically, and calculates charges fairly.

---

## Features

- Customer management
- Inventory management with maintenance mode
- Rental checkout with condition inspection
- Return flow with condition comparison
- Automatic damage detection and charges
- Customer risk profiling
- Admin damage override
- Item health timeline
- Centralized dashboard navigation

---

## Tech Stack

- PHP
- MySQL
- Bootstrap 5
- HTML5

---

## Project Structure

config/        → Database configuration  
partials/      → Shared header and footer   
*.php          → Feature-based pages  

---

## How to Run Locally

1. Install XAMPP
2. Place project in `htdocs`
3. Import database SQL
4. Update database credentials
5. Open `http://localhost/rental-item-logger/dashboard.php`

---

## Notes

- Inspection templates are category-based
- Same checklist is used at checkout and return
- Damage charges are calculated from deposit
- Designed as a modular monolith

---

## Author

Arunkumar
