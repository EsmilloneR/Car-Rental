<h1 align="center">🚗 CAR-RENTAL</h1>
<p align="center"><i>Drive Innovation, Rent Smarter, Experience Freedom</i></p>

<p align="center">
  <img src="https://img.shields.io/github/last-commit/EsmilloneR/Car-Rental?style=for-the-badge" alt="last commit"/>
  <img src="https://img.shields.io/badge/php-53.3%25-blue?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/languages-4-blue?style=for-the-badge"/>
</p>

---

### 🧰 Built with the tools and technologies:

<p align="center">
  <img src="https://img.shields.io/badge/JSON-000000?style=for-the-badge&logo=json&logoColor=white"/>
  <img src="https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white"/>
  <img src="https://img.shields.io/badge/Autoprefixer-DD3735?style=for-the-badge&logo=autoprefixer&logoColor=white"/>
  <img src="https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white"/>
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>
  <img src="https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white"/>
  <img src="https://img.shields.io/badge/Puppeteer-40B5A4?style=for-the-badge&logo=puppeteer&logoColor=white"/>
  <img src="https://img.shields.io/badge/XML-00599C?style=for-the-badge&logo=xml&logoColor=white"/>
  <img src="https://img.shields.io/badge/GitHub%20Actions-2088FF?style=for-the-badge&logo=githubactions&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white"/>
  <img src="https://img.shields.io/badge/Axios-5A29E4?style=for-the-badge&logo=axios&logoColor=white"/>
</p>

---

## 📑 Table of Contents
- [Overview](#overview)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## 🧭 Overview

**Car-Rental** is a comprehensive vehicle rental management platform built on **Laravel**, leveraging **Livewire**, **Vite**, and other modern tools for a seamless developer experience.  
It offers **real-time GPS tracking**, **automated workflows**, and a **modular architecture** to ensure scalability and maintainability.

### 💡 Why Car-Rental?

This project empowers developers to build scalable, real-time vehicle rental applications with ease.  
Key features include:

- 🌿 **Modular Resource Management:** Admin panels for managing users, vehicles, rentals, and manufacturers.  
- 📱 **Real-Time GPS & Notifications:** Live vehicle tracking and instant updates for better fleet control.  
- ⚙️ **Automated Rental Lifecycle:** Background jobs handle rentals, payments, and cleanup automatically.  
- 🔐 **Robust Security & Configuration:** Authentication, caching, and queue systems ready for production.  
- 🎨 **Rich UI Components:** Custom Blade components for dashboards, profiles, and interactive maps.

---

## 🚀 Getting Started

### 🧩 Prerequisites
Ensure you have the following installed:
- **PHP 8.2+**
- **Composer**
- **Node.js & npm**
- **MySQL or MariaDB**

---

### ⚙️ Installation

Clone the repository and install dependencies.

#### 1️⃣ Clone the repository
```bash
git clone https://github.com/EsmilloneR/Car-Rental.git
cd Car-Rental
```
### 2️⃣ Install backend dependencies
```bash
npm install
npm run dev
```

### 4️⃣ Copy the environment file
```bash
cp .env.example .env
```
### 5️⃣ Generate application key
```bash
php artisan key:generate
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
### 🤝 Contributing

Contributions are welcome!
If you’d like to improve or extend the project:

1. Fork the repository

2. Create a new branch (feature/new-feature)

3. Commit your changes

4. Push to your branch

5. Create a Pull Request 🚀
