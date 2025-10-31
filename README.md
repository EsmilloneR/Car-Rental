<h1 align="center">🚗 Twayne Garage Drive & Go: Car Rental</h1>
<p align="center"><i>Drive Innovation, Rent Smarter, Experience Freedom</i></p>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/EsmilloneR/Car-Rental?style=for-the-badge" alt="last commit"/>
  <img src="https://img.shields.io/badge/php-53.3%25-blue?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/languages-4-blue?style=for-the-badge"/>
</p>

---

### 🧰 Built with the tools and technologies:

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/Volt-5A0FC8?style=for-the-badge&logo=lightning&logoColor=white"/>
  <img src="https://img.shields.io/badge/Filament-1E1E2E?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/ESP32-000000?style=for-the-badge&logo=espressif&logoColor=white"/>
  <img src="https://img.shields.io/badge/NEO--M8N%20GPS-1E90FF?style=for-the-badge&logo=gps&logoColor=white"/>
  <img src="https://img.shields.io/badge/PayMongo-27AE60?style=for-the-badge&logo=money&logoColor=white"/>
  <img src="https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white"/>
  <img src="https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white"/>
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white"/>
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>
  <img src="https://img.shields.io/badge/Axios-5A29E4?style=for-the-badge&logo=axios&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white"/>
  <img src="https://img.shields.io/badge/Puppeteer-40B5A4?style=for-the-badge&logo=puppeteer&logoColor=white"/>
  <img src="https://img.shields.io/badge/GitHub%20Actions-2088FF?style=for-the-badge&logo=githubactions&logoColor=white"/>
</p>

---

## 📑 Table of Contents
- [Overview](#overview)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Testing](#testing)
- [Hardware Integration](#hardware-integration)
- [Online Payment Integration (PayMongo)](#online-payment-integration-paymongo)
- [Contributing](#contributing)
- [License](#license)

---

## 🧭 Overview

**Twayne Garage Drive & Go: Car Rental** is a smart, IoT-enhanced car rental management platform powered by **Laravel Livewire Volt**, **Filament v4**, and **PayMongo API** for seamless online payments.  
It integrates **ESP32 with NEO-M8N GPS** for real-time vehicle tracking and provides an interactive, modern dashboard for customers and administrators.

### 💡 Key Highlights

- 🌿 **Filament v4 Admin Panel:** Manage vehicles, users, rentals, and transactions effortlessly.  
- 📡 **IoT GPS Tracking:** Real-time updates from ESP32 + NEO-M8N displayed via Leaflet maps.  
- 💳 **PayMongo Payment Gateway:** Secure online payment processing for bookings and rentals.  
- ⚙️ **Automated Rental Lifecycle:** Background jobs for tracking, payments, and rental status.  
- 🎨 **Dynamic UI:** Built with Laravel Livewire Volt and Vite for reactive experiences.  
- 🔐 **Secure Authentication:** Laravel Breeze for login, registration, and user roles.

---

## 🚀 Getting Started

### 🧩 Prerequisites
Make sure you have the following installed:
- **PHP 8.2+**
- **Composer**
- **Node.js & npm**
- **MySQL or MariaDB**
- **ESP32 Development Board**
- **NEO-M8N GPS Module**
- **PayMongo API Keys**

---

### ⚙️ Installation

Build **Car-Rental** from the source and install dependencies.

<<<<<<< HEAD
#### 1️⃣ Clone the repository:
=======
### 1️⃣ Clone the repository:
>>>>>>> 847b506 (Part 6.9)
```bash
git clone https://github.com/EsmilloneR/Car-Rental.git
cd Car-Rental
```
### 2️⃣ Navigate into the project directory:
```bash
cd Car-Rental
```
### 3️⃣ Install backend dependencies:
```bash
composer install
```
### 4️⃣ Install frontend dependencies:
```bash
npm install
npm run dev
```
### 5️⃣ Copy the environment file:
```bash
cp .env.example .env
```
### 6️⃣ Configure environment:
Open .env and set your PayMongo API keys:
```bash
PAYMONGO_SECRET_KEY=sk_test_XXXXXXXXXXXXXXXXXXXX
PAYMONGO_PUBLIC_KEY=pk_test_XXXXXXXXXXXXXXXXXXXX
```
### 7️⃣ Generate application key:
```bash
php artisan key:generate
```
### 8️⃣ Run migrations and seed database:
```bash
php artisan migrate --seed
```
---
### ▶️ Usage
Start the local development server:
```bash
php artisan serve
```
Then visit http://localhost:8000

---
### 🧪 Testing
Run the test suite using:
```bash
php artisan test
```
You can also perform browser testing with Puppeteer for frontend validation.

---
## 🔌 Hardware Integration
### ⚙️ ESP32 + NEO-M8N Setup

This system integrates IoT features for live GPS tracking using:

- **ESP32 microcontroller** (UART communication)

- **NEO-M8N GPS module**

### 📡 Data Flow:

1. ESP32 reads GPS data (latitude & longitude) from the NEO-M8N module.
2. Sends it to the Laravel backend via HTTP POST or MQTT endpoint.
3. Laravel processes and updates the live map (Leaflet) in the Filament dashboard.

---
### 💳 Online Payment Integration (PayMongo)

**PayMongo** is used to handle secure online payments for car reservations and rentals.

💼 **Payment Flow**:

1. User selects a vehicle and rental duration.

2. System calculates the total cost and initiates a PayMongo Checkout Session.

3. The user completes the payment through PayMongo’s secure interface.

4. A webhook updates the transaction status in Laravel automatically (e.g., paid, failed, cancelled).

5. The system records the payment and activates the booking.

🔐 **Features**:

- Secure checkout hosted by PayMongo

- Supports cards, GCash, GrabPay, and Maya

- Webhook-based status updates

- Transaction logs stored in database

---
### 🤝 Contributing

Contributions are welcome!
If you’d like to improve or extend the project:

1. Fork the repository

2. Create a new branch (feature/new-feature)

3. Commit your changes

4. Push to your branch

5. Create a Pull Request 🚀
