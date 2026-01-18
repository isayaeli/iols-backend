# Incident & Operations Log System – Backend

## Overview

This is the **backend API** for the Incident & Operations Log System. It is built with **Laravel** and exposes a **RESTful API** secured using **JWT authentication**.

The backend is responsible for:

* Authentication and authorization
* Role-based access control
* Incident lifecycle management
* Audit logging (incident updates)
* Data persistence

---

## Tech Stack

* **Laravel** – Backend framework
* **JWT Auth** – Stateless authentication
* **MySQL / MariaDB** – Database
* **REST API** – Frontend communication

---

## System Roles

| Role     | Description                                       |
| -------- | ------------------------------------------------- |
| Reporter | Can create incidents and view their own incidents |
| Operator | Can update incident status and add comments       |
| Admin    | Full access: manage users, assign incidents       |

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
├── Models/
├── Policies/
├── Services/
database/
├── migrations/
├── seeders/
routes/
├── api.php
```

---

## Prerequisites

* **PHP** >= 8.2
* **Composer**
* **MySQL / MariaDB**

Verify:

```bash
php -v
composer -v
```

---

## Setup Instructions

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Configuration

Copy the environment file:

```bash
cp .env.example .env
```

Update database credentials in `.env`:

```
DB_DATABASE=incident_logs
DB_USERNAME=root
DB_PASSWORD=
```

---

### 3. Generate Application Key

```bash
php artisan key:generate
```

---

### 4. Configure JWT

```bash
php artisan jwt:secret
```

---

### 5. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

---

### 6. Start Development Server

```bash
php artisan serve
```

Backend will be available at:

```
http://localhost:8000
```

---

## API Authentication

* Login endpoint returns a JWT token
* Token must be sent with every request:

```
Authorization: Bearer <token>
```

---

## Core API Endpoints

### Authentication

```
POST /api/auth/login
GET  /api/auth/me
POST /api/auth/logout
```

### Incidents

```
GET    /api/incidents
POST   /api/incidents
GET    /api/incidents/{id}
PUT    /api/incidents/{id}/status
```

---

## Incident Status Flow

```
open → investigating → resolved → closed
```

* Skipping steps is not allowed
* Every status change creates an audit record

---

## Security Considerations

* JWT-based stateless authentication
* Role-based middleware and policies
* Passwords stored using hashing
* Input validation on all endpoints

---

## Notes

* Backend is API-only (no Blade views)
* Designed for SPA consumption
* Clean migrations and service-layer business logic

---

## Author

Interview Submission – Incident & Operations Log System
