
# 📚 Multi-Tenant Book Management System

[](https://laravel.com)
[](https://www.php.net)
[](https://opensource.org/licenses/MIT)

A professional-grade Library Management System built with a focus on **Clean Architecture** and **Scalable Multi-tenancy**. This project demonstrates how to manage multiple library branches (tenants) within a single application, ensuring strict data isolation and optimized performance.

## 🏗 Architectural Highlights

This project goes beyond basic CRUD, implementing enterprise-level design patterns:

  * **Multi-Tenant Isolation:** Every record (Books, Categories, Loans) is scoped to a specific `library_id` to ensure data security between different library branches.
  * **Service Layer Pattern:** Business logic is decoupled from Controllers into dedicated Services, making the codebase highly testable and maintainable.
  * **Event-Driven Architecture:** Utilizes Laravel Events and Listeners (e.g., `PasswordResetRequested`) for asynchronous workflows.
  * **Optimized Indexing:** Critical database columns like `ISBN`, `Title`, and `Author` are indexed for lightning-fast search results.

## ✨ Key Features

  * **Multi-Branch Management:** Supports multiple libraries under one dashboard.
  * **Comprehensive Book Tracking:** Manage ISBNs, publication years, and real-time availability tracking.
  * **Loan & History System:** Track book movements and historical data for every library tenant.
  * **Domain Logic:** Integrated logic for overdue checks, fine calculations, and damage reporting.
  * **API Ready:** Returns standardized JSON responses using Laravel API Resources.

## 🚀 Getting Started

### Prerequisites

  * PHP 8.2 or higher
  * Composer
  * MySQL/PostgreSQL

### Installation

1.  **Clone & Install:**

    ```bash
    git clone https://github.com/shashidas95/Book_management_project.git
    cd Book_management_project
    composer install
    ```

2.  **Environment Setup:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Database Configuration:**
    Configure your `.env` with your database credentials, then run the migration and the master seeder:

    ```bash
    php artisan migrate:fresh --seed --seeder=LibrarySystemSeeder
    ```

## 🛠 Tech Stack

  * **Framework:** Laravel 11
  * **Language:** PHP 8.2+
  * **Frontend:** Blade & Vite (for administrative views)
  * **Testing:** PHPUnit

-----


