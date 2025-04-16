# Laravel Sanctum User Management API

A complete RESTful API solution for user management with token-based authentication using Laravel Sanctum. This project includes user registration, authentication, password reset with OTP verification via email, and full CRUD operations for user management.

## Features

- **User Authentication**
  - Registration with email verification
  - Login with token-based authentication using Laravel Sanctum
  - Secure logout functionality
  - User profile access

- **Password Management**
  - Forgot password flow with OTP (One-Time Password)
  - OTP verification via email
  - Secure password reset

- **User Management**
  - List all users (paginated)
  - Create new users
  - View user details
  - Update user information
  - Delete users

## API Endpoints

### Authentication

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| POST | `/api/register` | Register a new user | No |
| POST | `/api/login` | Login and get access token | No |
| POST | `/api/logout` | Logout and invalidate token | Yes |
| GET | `/api/user` | Get authenticated user data | Yes |

### Password Reset

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| POST | `/api/forgot-password` | Request password reset OTP | No |
| POST | `/api/verify-otp` | Verify OTP and get reset token | No |
| POST | `/api/reset-password` | Reset password using token | No |

### User Management

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| GET | `/api/users` | List all users (paginated) | Yes |
| POST | `/api/users` | Create a new user | Yes |
| GET | `/api/users/{id}` | Get specific user details | Yes |
| PUT/PATCH | `/api/users/{id}` | Update user information | Yes |
| DELETE | `/api/users/{id}` | Delete a user | Yes |

## Request & Response Examples

### Register

**Request:**
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-04-17T10:00:00.000000Z",
    "updated_at": "2025-04-17T10:00:00.000000Z"
  },
  "token": "1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
}
```

### Login

**Request:**
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-04-17T10:00:00.000000Z",
    "updated_at": "2025-04-17T10:00:00.000000Z"
  },
  "token": "1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
}
```

### Forgot Password

**Request:**
```http
POST /api/forgot-password
Content-Type: application/json

{
  "email": "john@example.com"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Password reset OTP has been sent to your email"
}
```

### Verify OTP

**Request:**
```http
POST /api/verify-otp
Content-Type: application/json

{
  "email": "john@example.com",
  "otp": "123456"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "OTP verified successfully",
  "reset_token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
}
```

### Reset Password

**Request:**
```http
POST /api/reset-password
Content-Type: application/json

{
  "email": "john@example.com",
  "reset_token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Password has been reset successfully"
}
```

## Installation

1. Clone this repository:
```bash
git clone https://github.com/Anthony-Kishan/sanctum-rest-api.git
cd sanctum-rest-api
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file and configure:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Configure mail settings for OTP emails:
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=your-smtp-port
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

7. Run migrations:
```bash
php artisan migrate
```

8. Install Sanctum:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Usage

### Making Authenticated Requests

To access protected endpoints, include the token in the `Authorization` header:

```
Authorization: Bearer 1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

### CORS Configuration

If you're accessing the API from a different domain, make sure to configure CORS in `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'supports_credentials' => true,
```

## Security Considerations

- OTPs expire after 30 minutes
- Password reset tokens are single-use
- All existing tokens are revoked after password reset
- Password must be at least 8 characters long

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
