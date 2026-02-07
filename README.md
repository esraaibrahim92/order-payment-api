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

The payment system is designed using the **Strategy Pattern combined with a Gateway Registry**, allowing new payment gateways to be added with **minimal code changes** and without modifying existing business logic.

### Gateway Contract (Domain Layer)

All payment gateways implement a common contract defined in the domain layer:

```php
interface PaymentGatewayInterface
{
    public function method(): string;
    public function pay(Order $order): bool;
}
```

Each gateway:
- Defines the payment method it supports via `method()`
- Encapsulates its own payment logic in `pay()`

---

### Gateway Implementations (Infrastructure Layer)

Example gateway implementations:

```php
final class CreditCardGateway implements PaymentGatewayInterface
{
    public function method(): string
    {
        return 'credit_card';
    }

    public function pay(Order $order): bool
    {
        return true; // Simulated successful payment
    }
}
```

```php
final class PaypalGateway implements PaymentGatewayInterface
{
    public function method(): string
    {
        return 'paypal';
    }

    public function pay(Order $order): bool
    {
        return false; // Simulated failed payment
    }
}
```

---

### Gateway Registry (Application Layer)

Gateways are registered in a centralized **PaymentGatewayRegistry**, which resolves the appropriate gateway at runtime based on the `payment_method` provided in the request.

```php
$gateway = $registry->get($paymentMethod);
```

If an unsupported payment method is provided, the registry fails fast and prevents invalid payments from being processed.

---

### Adding a New Payment Gateway

To add a new payment gateway (for example, Apple Pay):

1. Create a new class implementing `PaymentGatewayInterface`
2. Implement the `method()` and `pay()` functions
3. Register the new gateway in the service container

```php
$this->app->singleton(PaymentGatewayRegistry::class, function ($app) {
    return new PaymentGatewayRegistry([
        $app->make(CreditCardGateway::class),
        $app->make(PaypalGateway::class),
        $app->make(ApplePayGateway::class), // New gateway
    ]);
});
```

✅ No changes are required in:
- Controllers  
- Use cases  
- Validation logic  

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

## Postman Collection
A Postman collection is included in the `/postman` directory to demonstrate and test all API endpoints.