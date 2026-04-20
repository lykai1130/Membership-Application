# Membership Application

A Laravel-based membership management system developed as a technical assessment project.  
This application demonstrates handling complex relationships, referral systems, and reward logic within a structured MVC architecture.

---

## Overview

This system allows administrators to manage members, their addresses, documents, and referral relationships.  
It also includes a promotion-based reward system that calculates incentives based on referral achievements.

The project is built to showcase:
- Advanced Eloquent relationships
- Polymorphic associations
- Recursive referral hierarchy (tree structure)
- Reward system
- Data filtering and export functionality

---

## Core Features

### Member Management
- Register, update, and delete members
- Unique referral code generation
- Referral tracking between members

### Address Management
- Multiple addresses per member
- Address types

### Document Management (Polymorphic)
- Upload profile images and proof-of-address documents
- Documents linked to different models using polymorphic relationships

### Referral System
- Self-referencing relationship (Member → Member)
- Referral code usage during registration
- Display of referrer information
- Referral tree visualization with levels

### Referral Hierarchy
- Recursive traversal to display multi-level referrals
- Example:
  - A → B → C → D
  - Levels dynamically calculated relative to selected member

### Reward & Promotion System
- Configurable promotions with date range
- Tier-based rewards:
  - 10 referrals → USD 100
  - 50 referrals → USD 500
  - 100 referrals → USD 1000
  - Every 10 referrals beyond 100 → USD 150
- Tracks achieved rewards per member

### Scheduled Job (Automation)
- Daily processing using Laravel Scheduler
- Calculates referral counts during active promotions
- Inserts reward achievements automatically

### Reporting & Export
- Filter rewards by:
  - Date range
  - Member
  - Promotion
- Export reports (CSV/Excel)

---

## Key Technical Concepts

### 1. Polymorphic Relationships
Documents can belong to multiple models (Member, Address) using Laravel polymorphism.

### 2. Self-Referencing Relationship
Members can refer other members, forming a hierarchical structure.

### 3. Recursive Tree Traversal
Referral hierarchy is built dynamically using recursive logic.

### 4. Reward Layer
Reward calculation based on referral milestones and promotion rules.

### 5. File Handling
Secure upload and validation for:
- Profile images
- Proof-of-address documents

---

## Tech Stack

Backend: Laravel
Frontend: Blade
Database: SQLite

---

## ⚙️ Installation

```bash
git clone https://github.com/lykai1130/Membership-Application.git
cd Membership-Application

composer install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

## Run in different terminal
php artisan serve
php artisan schedule:work

## If avatar image broken
php artisan storage:link

## Still broken
php artisan storage:unlink
php artisan storage:link
php artisan optimize:clear
