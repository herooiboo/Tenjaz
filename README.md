
# Tenjaz Project

Welcome to the **Tenjaz Project**, a Laravel-based backend system that incorporates role-based pricing for products, user management, and authentication functionalities. This project is designed with a robust repository pattern, clean code principles, and scalability in mind.

---

## Prerequisites

Before starting, ensure your environment meets the following requirements:
- **Docker**: Installed and running on your system.

---

## Features

### 1. Role-Based Pricing
Users are categorized into three tiers:
- **Normal**: No discount on products.
- **Silver**: 10% discount on product prices.
- **Gold**: 15% discount on product prices.

Prices are dynamically adjusted based on the authenticated user's type.

### 2. API Endpoints
- **Authentication APIs**:
   - Login
   - Logout
- **User Management APIs**:
   - CRUD operations for users
- **Product Management APIs**:
   - CRUD operations for products
   - Fetch product details by slug

### 3. Clean Architecture
- Implements **Repository Design Pattern** for better code organization and reusability.
- Comprehensive inline code comments for clarity.

### 4. Dockerized Setup
- Pre-configured for seamless deployment using Docker.
- Containers for Laravel, MySQL, and Nginx.

---

## Setup Instructions

### 1. Clone the Repository

Clone the repository to your local environment:
```bash
git clone https://github.com/herooiboo/Tenjaz.git tenjaz
cd tenjaz
```

---

### 2. Environment Setup

Create the environment file:
```bash
cp .env.example .env
```

---

### 3. Start the Docker Containers

Build and start the application:
```bash
docker-compose up -d --build
```

This will create three containers:
- **App**: PHP 8.3 with Laravel
- **MySQL**: Database server
- **Nginx**: Web server

---

### 4. Install Dependencies

Enter the application container and install dependencies:
```bash
docker-compose exec app composer install
```

---

### 5. Database Setup

#### Run Migrations:
Initialize the database schema:
```bash
docker-compose exec app php artisan migrate
```

#### Seed the Database:
Seed the database with initial test data:
```bash
docker-compose exec app php artisan db:seed
```

---



### 6. API Documentation and Testing

#### Postman Collection:
Import the Postman collection file to explore and test API endpoints. The collection is located at:
```
Postman/collection.json
```

#### Environment Variables:
Create the following variables in Postman:
1. `APP_URL` = `http://localhost:1010/`
2. `APP_VERSION` = `v1`
3. `AUTH_TOKEN` = 

---

## Implementation Overview

### Backend Features

#### 1. Database Design
- **Users Table**:
   - Fields: `id`, `name`, `username`, `password`, `avatar`, `type`, `is_active`, `created_at`, `updated_at`.
- **Products Table**:
   - Fields: `id`, `name`, `description`, `image`, `price`, `slug`, `is_active`, `created_at`, `updated_at`.

#### 2. Models and Logic
- **Role-Based Pricing**:
   - Discounts are applied dynamically using a match statement:
     ```php
     match ($user->type) {
         UserTypeEnum::Gold => $price * (1 - 0.2),
         UserTypeEnum::Silver => $price * (1 - 0.1),
         UserTypeEnum::Normal => $price,
     };
     ```

- **Enums for User Roles**:
   - Enum: `UserTypeEnum` handles mapping of roles (`1 = Normal`, `2 = Silver`, `3 = Gold`) and provides utility methods.

#### 3. Authentication
- **Login API**:
   - Validates user credentials and generates tokens.
- **Logout API**:
   - Revokes tokens for the current session.

#### 4. API Endpoints
- Users API: Full CRUD operations for user management.
- Products API:
   - Full CRUD operations.
   - Fetch product by slug.
   - Pricing varies by user role.

---

## Challenges and Constraints

1. **Initial Idea**:
   - Planned to use a `user_tiers` table for dynamic tier management and discounts.
   - This design allowed admins to manage tiers without modifying code.

2. **Constraint**:
   - Required to use a predefined `type` field in the `users` table instead of a dynamic table.
   - While functional, this approach lacks scalability:
      - Adding new tiers requires modifying enums and application logic.
      - Admins cannot dynamically manage tiers and discounts.

---

## Techniques and Approaches

### 1. Repository Design Pattern
- Separates business logic from data access logic.
- Provides a clean abstraction layer between models and controllers.
- Example: `BaseRepository`, `UserRepository`, `ProductRepository`.

---

### 2. Data Transfer Objects (DTOs)
- Encapsulates data for specific use cases to avoid direct usage of raw model data.
- Improves type safety and readability.
- Examples:
    - `AuthCredentialsDTO` (for authentication input).
    - `AuthenticatedUserDTO` (for authenticated user response).

---

### 3. Enum-Based Role Management
- Uses enums to handle predefined user roles (`Normal`, `Silver`, `Gold`).
- Simplifies role management logic with enum methods.
- Example: `UserTypeEnum` provides:
    - Role-to-string mapping.
    - Discount percentage calculation.
    - Flexible conversions.

---

### 4. Match Statements
- Used for concise conditional logic.
- Example:
    - Calculating discounts based on the user’s role in pricing logic.

---

### 5. Middleware for Route Access Control
- **Custom Middleware**:
    - Example: `RejectAuthenticatedUsers` middleware restricts authenticated users from accessing specific routes.
- **Built-in Middleware**:
    - `auth:sanctum`: Ensures only authenticated users can access protected routes.
    - `guest:sanctum`: Ensures only non-authenticated users can access certain routes.

---

### 6. Response Macros
- Provides consistent API response structures across the application.
- Custom Macros:
    - `SuccessResponseMacro` for successful responses.
    - `ErrorResponseMacro` for error responses.

---

### 7. Service Layer
- Encapsulates business logic into reusable service classes.
- Examples:
    - `LoginService`: Handles user login and token generation.
    - `LogoutService`: Handles token revocation.
    - `ProductPricingService`: Manages pricing adjustments based on user roles.

---

### 8. Custom Exception Handling
- Centralized error handling using a custom exception handler (`CustomExceptionHandler`).
- Separate handlers for:
    - Validation exceptions (`ValidationExceptionHandler`).
    - Generic exceptions (`GenericExceptionHandler`).

---

### 9. Laravel Features
- **Factories**:
    - Used to generate test data for `User` and `Product` models.
    - Example: `UserFactory` and `ProductFactory`.
- **Migrations**:
    - Sets up database schema and relationships.
- **Seeders**:
    - Populates the database with initial or test data.

---

### 10. Storage Management
- Handles file uploads and retrieval.
- **Custom File Management Services**:
    - `ImageUploader`: Manages image uploads and optional WebP conversion.
    - `ImageLoader`: Dynamically serves images from storage.
- **Mockup Image Handling**:
    - Ensures default images exist and are copied to appropriate locations.

---

### 11. RESTful API Design
- **Resource Controllers**:
    - `UserController` and `ProductController` follow RESTful conventions.
- **Custom Routes**:
    - Example: Fetching a product by slug (`/products/s/{slug}`).

---

### 12. API Resource Wrappers
- Formats model data for API responses using Laravel's `JsonResource`.
- Examples:
    - `LoginResource`: Structures authenticated user response.
    - `ProductResource`: Structures product data with pricing logic.

---

### 13. Dockerized Environment
- Simplifies development and deployment with containerized services.
- Containers:
    - PHP-FPM (Laravel application).
    - MySQL (database).
    - Nginx (web server).

---

### 14. Validation Rules
- Centralized validation logic in Form Request classes.
- **Custom Validation Rules**:
    - Example: `ValidateEnumString` ensures valid enum values for fields like `type`.

---

### 15. Database Design Best Practices
- **Field Casting**:
    - Example: `type` field cast as an enum in the `User` model.
    - Example: `is_active` cast as a boolean.
- **Protected Attributes**:
    - Sensitive fields like `password` are hidden from serialization.

---

### 16. Testing and Debugging
- **Postman Collection**:
    - Pre-configured for testing APIs.
    - Includes environment variables (`APP_URL`, `APP_VERSION`).

---

### 17. Scalability Considerations
- Discusses future improvements (e.g., dynamic role management using a `user_tiers` table).
- Identifies rigid areas (e.g., enum-based roles) and proposes scalable solutions.

---
### 18. Service Providers
- **Purpose**: Centralizes the registration of application services, bindings, and macros.
- **Custom Providers**:
    - `ExceptionServiceProvider`:
        - Registers a custom exception handler (`CustomExceptionHandler`) to ensure consistent error handling across the app.
    - `ResponseMacroServiceProvider`:
        - Registers custom response macros (`success` and `error`) to provide a unified response format for API endpoints.
- **Benefits**:
    - Enhances maintainability by centralizing configurations.
    - Allows for easy extension and modification of application behavior.
---
### 19. Versioning
- **Purpose**: Ensures that APIs are backward-compatible and future-proof.
- **Implementation**:
  - All API routes are prefixed with a version identifier (`v1`).
  - All `Request`, `Resource` and `Controllers` even `Services` Are Versioned and wrapped in `v1` Directory
- **Benefits**:
  - Allows introducing breaking changes in new versions while maintaining existing functionality for older clients.

---
## Summary of Techniques:
1. Repository Pattern
2. Data Transfer Objects (DTOs)
3. Enum-Based Role Management
4. Match Statements
5. Middleware (Custom & Built-In)
6. Response Macros
7. Service Layer
8. Custom Exception Handling
9. Laravel Features (Factories, Migrations, Seeders)
10. Storage Management
11. RESTful API Design
12. API Resource Wrappers
13. Dockerized Environment
14. Validation Rules
15. Database Design Best Practices
16. Testing and Debugging (Postman)
17. Scalability Considerations
18. Service Providers
19. Versioning
