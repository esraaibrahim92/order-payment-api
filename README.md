# Order Payment API

A RESTful Order & Payment Management API built with Laravel, following Clean Architecture principles, JWT authentication, and an extensible payment gateway design using the Strategy Pattern.

---

## 📌 Features Overview

- User registration & login (JWT authentication)
- Order management (create, update, delete, list with pagination)
- Payment processing with pluggable gateways
- Business rules enforcement:
  - Payments only for confirmed orders
  - Only one successful payment per order
  - Orders with payments cannot be deleted
- Pagination for all list endpoints
- Unit & feature tests (including payment gateway logic)

---

## 🧱 Architecture

The project follows **Clean Architecture** with clear separation of concerns:

```
app/
├── Domain/           # Business rules & entities
├── Application/      # Use cases
├── Infrastructure/   # DB, payment gateways, repositories
├── Http/Controllers/ # Thin HTTP controllers
```

---

## 🚀 Setup Instructions

### 1️⃣ Prerequisites
- Docker & Docker Compose
- PHP 8.1+
- Composer

### 2️⃣ Clone the Repository
```bash
git clone <repository-url>
cd order-payment-api
```

### 3️⃣ Environment Configuration
```bash
cp .env.example .env
```

Update `.env` with your database & JWT settings.

### 4️⃣ Start Containers
```bash
docker compose up -d
docker compose exec workspace bash
```

### 5️⃣ Install Dependencies
```bash
composer install
```

### 6️⃣ Generate Keys
```bash
php artisan key:generate
php artisan jwt:secret
```

### 7️⃣ Run Migrations
```bash
php artisan migrate
```

---

## 🔐 Authentication

- Register: `POST /api/auth/register`
- Login: `POST /api/auth/login`
- Use token:
```http
Authorization: Bearer <jwt_token>
```

---

## 📦 Orders API

- Create Order: `POST /api/orders`
- List Orders (Paginated):
```http
GET /api/orders?page=1&per_page=10
```
- Update Order: `PUT /api/orders/{id}`
- Delete Order: `DELETE /api/orders/{id}`

---

## 💳 Payments API

- Process Payment: `POST /api/payments`
- List Payments:
```http
GET /api/payments?page=1&per_page=10
```
- Order Payments:
```http
GET /api/orders/{id}/payments?page=1&per_page=5
```

---

## 🔌 Payment Gateway Extensibility

The system uses the **Strategy Pattern**.

### Gateway Contract
```php
interface PaymentGatewayInterface
{
    public function pay(Order $order): bool;
}
```

### Adding a New Gateway
1. Create a class implementing the interface
2. Bind it in the service container
3. No changes needed in controllers or use cases

Example:
```php
final class ApplePayGateway implements PaymentGatewayInterface
{
    public function pay(Order $order): bool
    {
        return true;
    }
}
```

---

## 🧪 Testing

Run all tests:
```bash
php artisan test
```

Run unit tests:
```bash
php artisan test --testsuite=Unit
```

Run feature tests:
```bash
php artisan test --testsuite=Feature
```

---

## ✅ Evaluation Criteria Compliance

- RESTful API design ✅
- JWT authentication ✅
- Validation & error handling ✅
- Clean code (PSR-12) ✅
- Strategy pattern for payments ✅
- Extensible gateways ✅
- Pagination ✅
- Unit & feature testing ✅