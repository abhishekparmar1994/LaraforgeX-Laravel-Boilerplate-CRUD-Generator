# 🚀 Deploying LaraforgeX (Laravel 13 MySQL) on Render Free Tier

This guide walks you through deploying **LaraforgeX** (Laravel 13 API Core) with **MySQL** to **Render.com** for free using a memory-optimized Docker setup.

---

## 🗂️ Table of Contents
1. [Free Tier Overview & Features](#1-free-tier-overview--features)
2. [Prerequisites](#2-prerequisites)
3. [Free MySQL Hosting Options](#3-free-mysql-hosting-options)
4. [Method 1: 1-Click Blueprint Deployment (Recommended)](#method-1-1-click-blueprint-deployment-recommended)
5. [Method 2: Manual Web Service Setup](#method-2-manual-web-service-setup)
6. [MySQL Environment Variables Reference](#6-mysql-environment-variables-reference)
7. [Troubleshooting & Cold Starts](#7-troubleshooting--cold-starts)

---

## 1. Free Tier Overview & Features

Render's Free Tier includes:
- **Free Web Service**: Docker runtime (`php:8.3-apache` with `pdo_mysql`), 512MB RAM, shared CPU.
- **SSL Certificate**: Automated, free custom domain HTTPS.
- **Sleep Behavior**: Inactive web services sleep after 15 minutes of non-use. The first request wakes the app up within 30–40 seconds (cold start).

---

## 2. Prerequisites

1. A **GitHub** account containing your LaraforgeX repository.
2. A free account on [Render.com](https://render.com).
3. Access to a **MySQL 8.0+** database (external free MySQL host or remote server).

---

## 3. Free MySQL Hosting Options

Since Render does not offer a free native MySQL service (Render natively offers PostgreSQL), you can pair your Render Laravel API with any external free or remote MySQL provider:

| Provider | Specs | Features |
| :--- | :--- | :--- |
| **[Aiven.io](https://aiven.io)** | MySQL 8.0 Free Tier | 5 GB storage, 1 GB RAM, SSL support |
| **[Clever Cloud](https://clever-cloud.com)** | MySQL Addon | 10 MB free tier, great for lightweight testing |
| **[Railway.app](https://railway.app)** | MySQL Service | $5 free trial credit monthly |
| **Remote / VPS / cPanel MySQL** | Custom Host | Connect to any accessible public MySQL IP or domain |

---

## 4. Method 1: 1-Click Blueprint Deployment (Recommended)

Render Blueprints auto-detect configuration from the [`render.yaml`](file:///c:/laragon/www/LaraforgeX/render.yaml) file in your repository root.

1. **Push code to GitHub**:
   Ensure your changes including `Dockerfile`, `entrypoint.sh`, and `render.yaml` are pushed to your repository.

2. **Open Render Dashboard**:
   - Go to [dashboard.render.com](https://dashboard.render.com).
   - Click **New +** top-right button -> Select **Blueprint**.

3. **Connect Repository**:
   - Select your `LaraforgeX` GitHub repository.

4. **Set MySQL Credentials**:
   Render will prompt for required sync variables:
   - `DB_HOST`: Your MySQL server host (e.g., `mysql-12345.aivencloud.com` or your VPS IP).
   - `DB_PORT`: `3306`
   - `DB_DATABASE`: Your MySQL database name.
   - `DB_USERNAME`: Database user.
   - `DB_PASSWORD`: Database password.

5. **Click Deploy**:
   Render builds the Docker image and auto-runs migrations on startup. Your API URL will be live at `https://laraforgex-api.onrender.com`.

---

## 5. Method 2: Manual Web Service Setup

1. Go to [Render Dashboard](https://dashboard.render.com) -> **New +** -> **Web Service**.
2. Choose **Build and deploy from a Git repository**.
3. Select your repository.
4. Fill in Service Configuration:
   - **Name**: `laraforgex-api`
   - **Region**: Select closest region (e.g., Singapore, Oregon, Frankfurt).
   - **Branch**: `main` (or `master`)
   - **Root Directory**: `apps/api`
   - **Runtime**: `Docker`
   - **Dockerfile Path**: `Dockerfile`
   - **Instance Type**: `Free` ($0/mo)

5. Add Environment Variables under **Environment** (see Section 6).

6. Click **Create Web Service**.

---

## 6. MySQL Environment Variables Reference

Add these key-value pairs in Render Web Service **Environment** settings:

```env
APP_NAME=LaraforgeX
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_APP_KEY
LOG_CHANNEL=stderr

# MySQL Database Configuration
DB_CONNECTION=mysql
DB_HOST=your-mysql-host.com
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Automatic Migrations on Startup
RUN_MIGRATIONS=true

# Driver Caching
SESSION_DRIVER=cookie
CACHE_STORE=file
```

> [!TIP]
> **Generating `APP_KEY`**:
> On your local machine, copy the `APP_KEY` from your local `.env` file or generate a new base64 key string to set in Render's `APP_KEY` environment variable.

---

## 7. Troubleshooting & Cold Starts

- **MySQL Connection Refused / Timeout**: Ensure your MySQL server accepts external remote connections and port `3306` is allowed in your MySQL server firewall/security group.
- **Application Logs**: Go to Render Dashboard -> **Logs** tab to view real-time stdout logs from Apache and Laravel.
- **Cold Starts**: Free web services sleep after 15 minutes of inactivity. Set up a free service like [UptimeRobot](https://uptimerobot.com) to ping `https://your-app.onrender.com` every 10 minutes to keep the container active.
