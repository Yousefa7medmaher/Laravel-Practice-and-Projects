# 🔐 Laravel Authentication API using Sanctum

A secure and simple Authentication System built with Laravel Sanctum, providing user registration, login, and logout functionality through RESTful API endpoints.

## 🧰 Tech Stack

- **Backend Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL / SQLite (configurable via `.env`)
- **API Testing**: Postman / Thunder Client recommended

## 📦 API Endpoints

### 1. POST `/api/register` — Register a New User
Registers a new user and returns an authentication token.

#### Request Body (JSON):
```json
{
  "name": "Yousef",
  "email": "yousef@example.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

#### Success Response:
```json
{
  "user": {
    "id": 1,
    "name": "Yousef",
    "email": "yousef@example.com",
    "role": "student"
  },
  "access_token": "your_token_here",
  "token_type": "Bearer"
}
```

### 2. POST `/api/login` — Log In
Authenticates a user and returns an access token.

#### Request Body:
```json
{
  "email": "yousef@example.com",
  "password": "12345678"
}
```

#### Success Response:
```json
{
  "user": {
    "id": 1,
    "name": "Yousef",
    "email": "yousef@example.com",
    "role": "student"
  },
  "access_token": "your_token_here",
  "token_type": "Bearer"
}
```

### 3. POST `/api/logout` — Log Out (Requires Token)
Revokes the user's current token.

#### Headers:
```
Authorization: Bearer your_token_here
```

#### Success Response:
```json
{
  "message": "Logged out"
}
```

## 🔐 Protected Routes
All routes protected by Sanctum middleware require the Bearer Token in the Authorization header.

Example:
```
GET /api/profile
Authorization: Bearer your_token_here
```

## 🔧 Setup Instructions

### Clone the project
```bash
git clone https://github.com/your-repo.git
cd project-folder
```

### Install dependencies
```bash
composer install
```

### Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

### Database setup
```bash
php artisan migrate
```

### Install Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Run the application
```bash
php artisan serve
```

## 📂 Key Code Files
- `routes/api.php` — API Route definitions
- `App\Http\Controllers\AuthController.php` — Authentication logic
- `App\Models\User.php` — User model (uses HasApiTokens)

## 🛡️ Security Notes
- Passwords are securely hashed using Laravel's built-in Hash facade
- Sanctum supports issuing multiple tokens per user
- Tokens are revoked on logout
- Always use HTTPS in production environments