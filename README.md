# Perks.ge - Employee Benefits Platform

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Perks.ge

Perks.ge is an employee benefits platform that connects employees with exclusive discounts, rewards, and perks at hundreds of partner locations across Georgia. Built with Laravel and Filament.

### Key Features

- **Premium Offers**: Exclusive deals from top-rated partners
- **Category-Based Filtering**: Easy navigation through different service categories
- **Location-Based Search**: Find offers in Tbilisi, Batumi, Kutaisi, and more
- **Partner Management**: Comprehensive admin panel for managing partners and offers
- **Real-time Updates**: Dynamic offer countdown and availability tracking
- **Responsive Design**: Mobile-first approach with dark mode support

## Tech Stack

- **Framework**: Laravel 11.x
- **Admin Panel**: Filament 3.x
- **Frontend**: Tailwind CSS 3.x
- **Database**: MySQL
- **Icons**: Font Awesome 6.5.1
- **Language**: Georgian (KA)

## Installation

### Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd Perks
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perks
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations:
```bash
php artisan migrate
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Seed the database (optional):
```bash
php artisan db:seed
```

10. Start the development server:
```bash
php artisan serve
```

11. Access the application:
- Frontend: http://localhost:8000
- Admin Panel: http://localhost:8000/admin

## Project Structure

```
app/
├── Filament/
│   └── Resources/          # Admin panel resources
│       ├── CategoryResource.php
│       ├── PartnerResource.php
│       └── PremiumOfferResource.php
├── Http/
│   └── Controllers/
│       └── LandingPageController.php
├── Models/
│   ├── Category.php
│   ├── Partner.php
│   └── PremiumOffer.php
resources/
├── views/
│   ├── components/
│   │   └── landing/        # Reusable components
│   ├── layouts/
│   │   └── landing.blade.php
│   ├── offers/
│   │   ├── index.blade.php # Offers listing page
│   │   └── show.blade.php  # Offer detail page
│   └── welcome.blade.php   # Homepage
```

## Features

### For Employees

- Browse exclusive offers and discounts
- Filter by category, location, and search
- View detailed offer information
- Track offer expiration dates

### For Companies

- Manage employee benefits platform
- Add and manage partner relationships
- Track offer performance

### For Partners

- Create and manage exclusive offers
- Reach thousands of employees
- Build brand awareness

## Admin Panel

Access the Filament admin panel at `/admin` to manage:

- **Categories**: Create categories with icons and descriptions
- **Partners**: Manage partner companies and their details
- **Premium Offers**: Create and manage exclusive offers
- **Users**: User management and roles

## Development

### Database Models

- **Category**: Service categories with icons (restaurants, hotels, fitness, etc.)
- **Partner**: Business partners offering discounts
- **PremiumOffer**: Exclusive offers with discounts and time limits

### Key Routes

```php
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/offers', [LandingPageController::class, 'allOffers'])->name('offers.index');
Route::get('/offers/{offer}', [LandingPageController::class, 'showOffer'])->name('offers.show');
```

## Contributing

Thank you for considering contributing to Perks.ge! Please ensure all code follows Laravel best practices and PSR-12 coding standards.

## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to the development team. All security vulnerabilities will be promptly addressed.

## License

The Perks.ge platform is proprietary software. All rights reserved.

---

Built with Laravel - The PHP Framework for Web Artisans
