<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

# Stadium Management System

## Project Overview

This is a Web Security project developed as part of a university course, focusing on building a secure **Stadium Management System** using Laravel. The system manages stadium bookings, matches scheduling, multiple user, roles, authentication flows, and APIs for mobile interaction. The project highlights secure authentication, role-based access, and modern web development practices. All team members will work on all aspects of the project to ensure everyone gains comprehensive experience across the entire development process..

## Team Members

- Mohamed Wael 
- Eslaam Ibrahim 
- Ahmed Emad 
- Hanan
- Randa Emam

## Project Requirements

### Core Features
- Manage matches, stadium seats, and bookings.
- Role-based access for Admins, Managers, Organizers, Security, and Fans.

### Roles and Permissions
- 5+ roles: Admin, Stadium Manager, Organizer, Security, Fan.
- Spatie Laravel Permission for RBAC.
- Admin role editor.

### Authentication
- Login, register, verify email, reset password.
- Social login (Google, GitHub) via Laravel Socialite.

### Security
- Local SSL enabled.
- Secure RESTful APIs for mobile.
- Sanctum or token-based API protection.

### APIs
- REST APIs for booking, match data, and schedules.
- Public Postman collection for documentation.

### Extra Features
- Real-time booking status, notifications (TBD).

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Blade / Vue.js (optional)
- **Database:** MySQL (via XAMPP)
- **RBAC:** Spatie Laravel Permission
- **Social Login:** Laravel Socialite
- **Docs:** Postman
- **Dev Environment:** XAMPP + SSL
- **Version Control:** Git + GitHub

## Setup Guide

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js + npm
- XAMPP (Apache + MySQL)
- Git
- Postman

### Installation

```bash
git clone https://github.com/YourUsername/stadium-management-system.git
cd stadium-management-system
composer install
npm install
cp .env.example .env
php artisan key:generate
