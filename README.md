# Ousodhaloy – Bangladesh Online Pharmacy (Laravel 11)

A complete, production-ready pharmacy e-commerce platform built with **Laravel 11**, Alpine.js, and vanilla CSS. Designed for the Bangladesh market with full integrations for Pathao courier, SSL Commerz payments, MimSMS SMS notifications, and OTP phone login.

---

## 🚀 Quick Start (5 minutes)

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Set up database (MySQL or SQLite)
# MySQL: create a database, update .env DB_* values
# SQLite: DB_CONNECTION=sqlite, DB_DATABASE=/absolute/path/to/database.sqlite

# 5. Run migrations + seed sample data
php artisan migrate --seed

# 6. Create storage symlink
php artisan storage:link

# 7. Install frontend dependencies & build assets
npm install && npm run build

# 8. Start the server
php artisan serve
```

Open **http://localhost:8000**

**Admin login:** admin@ousodhaloy.com / **admin123**

---

## ⚙️ Environment Variables

Copy `.env.example` to `.env` and fill in your keys:

| Variable | Description | Where to get |
|---|---|---|
| `SSLCOMMERZ_STORE_ID` | SSL Commerz store ID | [developer.sslcommerz.com](https://developer.sslcommerz.com) |
| `SSLCOMMERZ_STORE_PASSWD` | SSL Commerz password | Same |
| `PATHAO_CLIENT_ID` | Pathao API client ID | [merchant.pathao.com/api-information](https://merchant.pathao.com/api-information) |
| `PATHAO_CLIENT_SECRET` | Pathao API secret | Same |
| `PATHAO_USERNAME` | Your Pathao merchant email | Same |
| `PATHAO_PASSWORD` | Your Pathao merchant password | Same |
| `PATHAO_STORE_ID` | Pathao store ID | From Pathao dashboard |
| `MIMSMS_API_KEY` | MimSMS API key | [app.mimsms.com](https://app.mimsms.com) |
| `MIMSMS_SENDER_ID` | SMS sender name | MimSMS dashboard |

---

## 📦 Key Features

### 🛒 Storefront
| Feature | Details |
|---|---|
| **Live search** | Alpine.js debounced AJAX — shows product, brand, price, discount in real-time dropdown |
| **Hero slider** | Auto-plays, manually controllable, managed from admin |
| **Flash sale** | Countdown timer, flash price override, ends-at per product |
| **Category nav** | Sticky category bar — all 12 categories at a glance |
| **Product detail** | Image gallery, customisable content tabs, rating, related products, same-brand |
| **Cart** | Session-based, Ajax add-to-cart with cart count update, promo code field |
| **Checkout** | Guest + logged-in, prescription upload, division/district dropdowns (all 8 BD divisions) |
| **OTP login** | Phone-based, Bangla SMS via MimSMS, rate-limited, auto-register new users |
| **Order tracking** | Public tracking by order number + phone — no login needed |
| **Trust badges** | Fast delivery, secure payment |

### 🔧 Admin Panel
| Feature | Details |
|---|---|
| **Dashboard** | Revenue bar chart (30 days), order status breakdown, low stock alerts, auto-refresh 60s |
| **Product CRUD** | Rich text per tab, customisable tab labels, Ajax image upload, flash sale, all toggles |
| **Order management** | Filter by status/date/payment, bulk confirm/cancel/export, status timeline |
| **Pathao integration** | Push order to Pathao with one click, sync tracking status from Pathao API |
| **SMS logs** | Full log of every SMS sent with status, response, and purpose |
| **Promo codes** | Percent/fixed, min order, max discount, per-user limit, first-order-only, expiry |
| **Banners** | Hero, promo, sidebar, popup positions with date scheduling |
| **Delivery zones** | Division/district based charges with free delivery thresholds |
| **Settings** | All config editable in UI — no need to edit env for most things |
| **Prescriptions** | Customer uploads reviewed/approved by admin |
| **Reviews** | Moderation workflow, approve/reject, auto-updates product rating |

### 🔌 Integrations
| Integration | What it does |
|---|---|
| **Pathao Courier** | Create shipments, get consignment ID, sync tracking status (maps to local statuses) |
| **SSL Commerz** | bKash, Nagad, Rocket, Cards, Net Banking — IPN confirms orders automatically |
| **MimSMS** | Bangla SMS on: order placed, every status change, OTP, low stock alert |
| **Loyalty Points** | Earn 1 point per ৳10 spent; LoyaltyPoint table tracks history |
| **Excel Export** | Download filtered orders as `.xlsx` |
| **PDF Invoice** | DomPDF — downloadable from admin order detail |

---

## 📁 Project Structure

```
app/
├── Console/Commands/
│   └── SyncPathaoOrders.php     ← php artisan pathao:sync
├── Exports/
│   └── OrdersExport.php         ← Excel export
├── Http/
│   ├── Controllers/
│   │   ├── Admin/               ← Dashboard, Products, Orders, Settings, Categories, Brands...
│   │   ├── Auth/                ← Login, Register, OTP
│   │   └── Shop/                ← Home, Products, Cart, Checkout, Orders, Payments
│   └── Middleware/
│       └── IsManager.php        ← Protects /admin routes
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Models.php               ← Order, OrderItem, Category, Brand, PromoCode, Setting, etc.
│   └── ...
└── Services/
    ├── OrderService.php         ← Core business logic: create, updateStatus, validatePromo
    ├── PathaoService.php        ← Pathao API client with cached token
    ├── SslCommerzService.php    ← SSL Commerz init/validate/IPN
    └── SmsService.php           ← MimSMS with Bangla templates

database/
├── migrations/                  ← 4 migration files covering all tables
└── seeders/
    └── DatabaseSeeder.php       ← Admin user, categories, brands, sample products, zones, promos

resources/views/
├── layouts/
│   ├── shop.blade.php           ← Full storefront: topbar, sticky header, live search, footer
│   └── admin.blade.php          ← Admin panel: sidebar, nav badges, flash messages
├── shop/                        ← home, products (index+show), cart, checkout, orders, auth
├── admin/                       ← dashboard, products (index+form), orders (index+show+invoice), settings
└── auth/                        ← login, register, otp

config/
├── bd.php                       ← BD divisions, districts, payment methods, categories
└── services.php                 ← SSL Commerz, Pathao, MimSMS, bKash config

routes/
├── web.php                      ← All routes (shop, admin, auth, cart, payment)
└── console.php                  ← Scheduled: pathao:sync, clean OTPs, low-stock alerts
```

---

## 🗄️ Database Tables

| Table | Description |
|---|---|
| `users` | Customers + admin/manager roles, referral codes |
| `otps` | Phone OTP for login (6-digit, 5min expiry) |
| `addresses` | User saved addresses |
| `categories` | Hierarchical, with icon, sort order |
| `brands` | Manufacturers, country, logo |
| `products` | Full medicine profile — price, MRP, stock, tabs, flash sale, Rx flag |
| `product_reviews` | Moderated, rating auto-updates product |
| `orders` | Guest + registered, full address, Pathao fields, payment fields |
| `order_items` | Snapshot of product name/price at time of order |
| `order_status_histories` | Full audit trail |
| `promo_codes` | Percent/fixed, per-user, first-order, date-bounded |
| `promo_code_usages` | Usage log per user per order |
| `settings` | Key-value site config |
| `banners` | Hero/promo/sidebar with date scheduling |
| `delivery_zones` | Division/district based delivery charges |
| `flash_deals` | Timed flash sale campaigns |
| `loyalty_points` | Earn/redeem history per user |
| `sms_logs` | Every outbound SMS with status and response |
| `prescriptions` | Uploaded prescription files with review workflow |

---

## 🚀 Production Deployment

```bash
# Optimise for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set up cron for scheduled tasks (add to crontab)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# Run queue worker for background jobs
php artisan queue:work --daemon
```

**Server requirements:** PHP 8.2+, MySQL 8.0+ (or SQLite for dev), Nginx/Apache, Node 18+ (build step only)

---

Built with ❤️ for 🇧🇩 Bangladesh
