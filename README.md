# TechBuild — E-Commerce Platform for PC Parts

TechBuild is a full-stack e-commerce web application built with the **TALL Stack** (Tailwind CSS, Alpine.js, Laravel, Livewire), designed specifically for selling PC components in the **Kurdistan Region**. It features an interactive **PC Builder** with real-time hardware compatibility checking, integrated **Wayl.io** payment gateway support, and a fully **bilingual interface** (Kurdish / Arabic / English).

## Tech Stack

| Layer       | Technology                  |
|-------------|-----------------------------|
| Backend     | Laravel 12                  |
| Frontend    | Livewire 3.7, Alpine.js     |
| Styling     | Tailwind CSS 4              |
| Build Tool  | Vite 5                      |
| Database    | SQLite (dev) / MySQL (prod) |

## Requirements

- PHP ^8.2
- Composer
- Node.js 18+
- npm

## Installation

```bash
git clone <repository-url>
cd PC-main
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Environment Variables

| Variable              | Description                              |
|-----------------------|------------------------------------------|
| `DB_CONNECTION`       | Database driver (`sqlite` or `mysql`)    |
| `WAYL_API_KEY`        | Wayl.io payment gateway API key          |
| `WAYL_WEBHOOK_SECRET` | Secret for HMAC SHA-256 webhook signing  |
| `WAYL_BASE_URL`       | Wayl.io API base URL                     |

## Features

- **Product Catalog** — Browsable, searchable, and filterable catalog of PC components and peripherals
- **Shopping Cart** — Session-based cart with quantity management and coupon support
- **PC Builder with Compatibility Checking** — Interactive drag-and-drop builder that validates CPU–motherboard socket, RAM type, PSU wattage, GPU clearance, and more in real time
- **Wayl.io Payment Gateway** — Supports Card, ZainCash, FIB, and FastPay payment methods
- **HMAC SHA-256 Webhook Verification** — Secure, timing-safe signature verification for payment callbacks
- **Bilingual Interface** — Full Kurdish, Arabic, and English language support with RTL layout
- **Admin Dashboard** — Product, order, and user management with activity logging

## Running Tests

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
