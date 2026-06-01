# TechBuild — Premium E-Commerce Platform for PC Parts

TechBuild is a robust, production-ready, full-stack e-commerce web application built using the state-of-the-art **TALL Stack** (Tailwind CSS, Alpine.js, Laravel 12, Livewire 3). Designed specifically for PC builders, gamers, and hardware enthusiasts in the **Kurdistan Region**, it simplifies custom computer shopping by integrating physical engineering rules into a beautiful, high-performing digital shopping experience.

---

## 🌟 Key Highlights & Design Aesthetics

- **Vibrant & Harmonies Design** — Tailored modern UI with Curated HSL color systems, responsive layouts, sleek dark modes, micro-animations, and smooth gradients. 
- **Bilingual & Multilingual Support** — Fully translated into English, Kurdish, and Arabic with comprehensive RTL (Right-to-Left) layout transitions.
- **Dynamic Theme Store** — Interactive theme switcher offering HSL-tailored schemes such as *Ocean Blue*, *Forest Green*, *Deep Teal*, *Cosmic*, and *Sunset*.
- **Full-featured Client Portal & Admin Panel** — Separate user profiles, secure shopping carts, transaction monitoring, and employee dashboards for robust product catalog control and logistics.

---

## 🛠 Tech Stack

| Layer | Technology | Version / Details |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel | 12.x |
| **Frontend Stack** | Livewire & Volt | 3.x / Alpine.js integration |
| **Styling Engine** | Tailwind CSS | 4.x (via `@tailwindcss/vite`) |
| **Build & Bundle Tool**| Vite | 5.x |
| **Integrations** | Wayl.io Gateway | Custom HMAC Signed Payment Callback System |
| **Database** | SQLite (Dev) / MySQL (Prod) | Structured Schema Alignment with Activity Logs |

---

## ⚙️ Features in Detail

### 1. Interactive PC Builder with Real-time Compatibility
An smart, drag-and-drop hardware selector containing physical validation rules across core components:
- **Processor & Motherboard Socket Check**: Validates socket compatibility (e.g., LGA1700, AM5) instantly.
- **RAM Compatibility**: Evaluates matching memory slots, generation (DDR4 vs DDR5), and voltage levels.
- **PSU Wattage Calculator**: Aggregates component TDPs and recommends PSU margins.
- **Form Factor Verification**: Cross-references cases against ATX/Micro-ATX motherboards and GPU clearance limits.

### 2. Multi-spec Product Comparison System
An advanced comparison panel resembling modern tech-review tables (e.g., technical.city style) with:
- Intelligent value-normalization converting complex spec units (e.g., TB to GB, GHz to MHz).
- Automated comparison vectors selecting the best technical specifications with clear color-coded badges.
- Smart overall rating system identifying a definitive technical winner based on aggregated spec performance and price checks.
- Optional difference-highlighting toggles to focus on divergent characteristics.

### 3. Integrated Wayl.io Payment Gateway
Seamless support for card services, FIB, FastPay, and ZainCash utilizing timing-safe security controls:
- **Timing-Safe HMAC Verification**: Validates payment callback webhooks using cryptographic signature headers.
- **Safe Transaction Transitions**: Resolves and flags order fulfillment steps efficiently.

---

## 🚀 Installation & Configuration

### Prerequisites
Ensure your local development environment meets the following requirements:
- PHP ^8.2 (with JSON, SQLite, OpenSSL, and MBString extensions enabled)
- Composer ^2.0
- Node.js 18+ & npm

### Local Deployment
Follow these steps to spin up the application:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/sya010/PC
   cd PC
   ```

2. **Install composer and npm dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment Variables**:
   Copy `.env.example` to `.env` and configure your local SQLite/MySQL settings along with the verified production parameters listed below:
   ```bash
   cp .env.example .env
   ```

4. **Prepare Database & Key**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Compile Production Assets & Serve**:
   Use Vite to compile frontend assets and spin up the PHP web server:
   ```bash
   npx vite build
   php artisan serve
   ```

---

## 🔑 Production Credentials & API Keys

To integrate with the active payment sandbox and webhook verification systems, copy and paste the following values into your local `.env` configuration file:

```env
# Wayl.io E-Commerce API Key (Production Sandbox)
WAYL_API_KEY=9DXJSatoLgp99SCfkpoPIA==:xECzcnU2yD4AONUG9BtKXnNKCtw77dnULyZPwFf0akwm3EJaICPQmY4GX5nEE02Ihu/bddtnFo8kafzNxEU+G0G7w8pwupErBZN5j9QNNeJ48XpvOGAiVIB3/ZFdS0AK4szc3h8cpqN0CWfK9WBQ8HoUF1XM0VNajmf9YOg5FtE=

# Base Payment Endpoints
WAYL_BASE_URL=https://api.thewayl.com/api/v1

# Secure Timing-safe Webhook HMAC-SHA256 Token
WAYL_WEBHOOK_SECRET=MyPcShopSuperSecretPassword123!

# Gateway Network Timeout Settings (in seconds)
WAYL_TIMEOUT=30
```

---

## 🧪 Testing and Verification

TechBuild features a comprehensive automated testing suite encompassing **72 fully passing unit and integration test methods**. 

Run the automated tests to verify routing, auth middleware, cart persistence, compatibility engines, and signature webhook safety:
```bash
php artisan test
```

*All tests execute in a timing-safe, isolated SQLite testing database instance.*

---

## 📄 License
This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
