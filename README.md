# Food Sharing Platform

A web application for reducing food waste by connecting food donors, buyers, and volunteers. Built with Laravel 12 and Tailwind CSS for the CSE470 course project.

## Features

- **Marketplace** — Browse and purchase discounted surplus food listings
- **Donations** — Donate or claim free food items
- **Reservations** — Reserve donated food items
- **Orders & Delivery** — Place orders and assign delivery tasks to volunteers
- **Volunteer Tasks** — Create and sign up for community food distribution tasks
- **User Dashboard** — Manage your listings, orders, donations, tasks, and deliveries
- **Profile Management** — Update personal info, address, and password
- **Authentication** — Register, login, forgot/reset password

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Blade templates, Tailwind CSS v4, Vite
- **Database:** MySQL / MariaDB

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL / MariaDB

## Local Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/RHYTHM1028/Food-Sharing-Platform-CSE470-Project.git
   cd Food-Sharing-Platform-CSE470-Project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JS dependencies and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Then edit `.env` and set your database credentials:
   ```
   DB_DATABASE=food_sharing_platform
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Import the database**
   Import `food_sharing_platform.sql` into your MySQL server via phpMyAdmin or:
   ```bash
   mysql -u root food_sharing_platform < food_sharing_platform.sql
   ```

6. **Link storage**
   ```bash
   php artisan storage:link
   ```

7. **Run the app**
   ```bash
   php artisan serve
   ```
   Visit [http://localhost:8000](http://localhost:8000)

## License

This project is for academic purposes — CSE470, BRAC University.
