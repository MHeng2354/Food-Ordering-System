# 🍕 FoodHub - Malaysian Food Ordering System

A modern, responsive food ordering platform built with Laravel 8, designed to connect customers with authentic Malaysian cuisine. This application allows users to browse menus, place orders, manage shopping carts, and track their orders seamlessly.

## 🚀 Features

-   **User Authentication**: Secure login and registration system
-   **Menu Management**: Browse and filter Malaysian food items
-   **Shopping Cart**: Add, update, and remove items from cart
-   **Order Management**: Place orders and track order history
-   **Promotions System**: Special discounts and promotional offers
-   **Admin Panel**: Manage foods, orders, and users (admin role required)
-   **Responsive Design**: Mobile-friendly interface using Bootstrap 5
-   **Real-time Updates**: Dynamic cart and order status updates

## 🛠️ Tech Stack

-   **Backend**: Laravel 8 (PHP 7.3/8.0)
-   **Frontend**: Bootstrap 5, JavaScript, Vue.js 2
-   **Database**: MySQL
-   **Authentication**: Laravel Sanctum
-   **Styling**: SCSS, Bootstrap Icons
-   **Build Tool**: Laravel Mix (Webpack)

## 📋 Prerequisites

Before running this application, make sure you have the following installed:

-   **PHP** >= 7.3 or 8.0
-   **Composer** (PHP dependency manager)
-   **Node.js** >= 14.x and npm
-   **MySQL** or another supported database
-   **Git** (for cloning the repository)

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Assignment
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodhub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

Create the database and run migrations with seeders:

```bash
# Create database (if not exists)
# Run migrations
php artisan migrate --path=database\migrations\2026_04_07_114316_create_all_tables.php

# Seed the database with sample data
php artisan db:seed
```

### 7. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 🔐 Default Users

After seeding, you can use these default accounts:

### Admin User

-   **Email**: admin@example.com
-   **Password**: password123

## 🎯 Usage

### For Customers:

1. **Register/Login**: Create an account or log in
2. **Browse Menu**: Explore available Malaysian dishes
3. **Add to Cart**: Select items and customize orders
4. **Checkout**: Review cart and place orders
5. **Track Orders**: Monitor order status and history

### For Admins:

1. **Login** with admin credentials
2. **Manage Foods**: Add, edit, or remove menu items
3. **Manage Orders**: View and update order statuses
4. **Manage Users**: View admin accounts
5. **Manage Promotions**: Create and manage special offers

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

If you encounter any issues:

1. Check the Laravel [documentation](https://laravel.com/docs)
2. Review the [issues](https://github.com/your-repo/issues) page
3. Create a new issue with detailed information

## 🙏 Acknowledgments

-   Built for UECS2354 Laravel Assignment
-   Special thanks to the Laravel community
-   Icons by Bootstrap Icons
-   Images from Unsplash

---

**FoodHub** - Bringing authentic Malaysian flavors to your doorstep! 🇲🇾🍜

-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

> > > > > > > e7bb79c (initial commit)
