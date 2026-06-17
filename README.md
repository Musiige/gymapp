# 🏋️‍♂️ Gym Management System (Gymapp)

A lightweight, automated Gym Management System designed to streamline client attendance, multi-role access control, membership tiers, and workout program delivery.

## 🚀 Step-by-Step Development Journey

### 📍 Phase 1: Local Infrastructure & Database Handshake
* **Goal:** Establish a secure, high-performance database layer tailored for lightweight local development hardware.
* **Implementation:** * Deployed a local MySQL service containerized within Laragon.
  * Structuralized the core database schema with relational indexes for membership tiers, attendance logs, and workout tracking.
  * Executed baseline migrations and isolated environment configurations via a secure `.env` lifecycle wrapper.

### 📍 Phase 2: Data Modeling & Business Relations
* **Goal:** Architect robust Eloquent relationships to seamlessly map business logic across a multi-entity gym network.
* **Implementation:**
  * Defined data models (`Membership`, `Subscription`, `Attendance`, `Workout`, `WorkoutAssignment`) with explicit integrity constraints.
  * Customized core user authentication entities to handle custom roles (Admin, Trainer, Client).

### 📍 Phase 3: Authentication Gateways & Multi-Role Frontends
* **Goal:** Secure entry systems while splitting the application experience cleanly based on system permissions.
* **Implementation:**
  * Injected **Laravel Breeze** to scaffold standard authentication pipelines.
  * Modified default controller layers (`RegisteredUserController`, `AuthenticatedSessionController`) to process custom field parameters.
  * Developed targeted dashboard frames for Admins, Trainers, and Clients alongside a custom QR landing route.
  * Compiled the style pipeline using a real-time **Vite** dynamic asset builder backed by **Tailwind CSS**.

---

## 💻 Tech Stack & Environment
* **Backend Framework:** Laravel
* **Database Management:** MySQL / HeidiSQL
* **Asset Pipeline & UI:** Tailwind CSS / Vite / Blade
* **Local Development Environment:** Laragon