# 🚀 Railway Deployment Guide for School Health Management System

## ✅ Prerequisites (Already Completed)
- [x] GitHub repository: `schoolhealthmanagementsystem`
- [x] Code pushed to GitHub
- [x] `railway.json` created for PHP deployment
- [x] `config.php` updated for Railway environment variables

## 📋 Step-by-Step Deployment Guide

### Step 1: Prepare Your Code

**Goal:** Commit all your changes and push them to GitHub so Railway can deploy the latest version.

#### Prerequisites:
- Make sure Git is installed on your system
- If Git is not installed, download it from: https://git-scm.com/downloads

#### Steps:
1. **Open Terminal/Command Prompt** in your project folder (`C:\Users\Administrator\Documents\capss`)

2. **Check Git status:**
   ```bash
   git status
   ```
   *Expected output:* Shows modified files (railway.json, config.php, TODO.md)

3. **Stage all changes:**
   ```bash
   git add .
   ```
   *Expected output:* No error messages

4. **Commit the changes:**
   ```bash
   git commit -m "Prepare for Railway deployment"
   ```
   *Expected output:* Commit message and success confirmation

5. **Push to GitHub:**
   ```bash
   git push origin main
   ```
   *Expected output:* Push successful message

#### Verification:
- Visit your GitHub repository `schoolhealthmanagementsystem`
- Confirm that `railway.json`, updated `config.php`, and `TODO.md` are visible in the repository

### Step 2: Create Railway Account
1. Go to [https://railway.app](https://railway.app)
2. Click "Sign Up" and create your account
3. Verify your email address

### Step 3: Create New Project
1. Click "New Project" button
2. Select "Deploy from GitHub repo"
3. Click "Configure GitHub" and authorize Railway
4. Search for and select your `schoolhealthmanagementsystem` repository
5. Click "Deploy Now"

### Step 4: Add MySQL Database
1. In your Railway project dashboard, click "Add Service"
2. Select "Database" → "MySQL"
3. Choose your plan (Starter plan is free)
4. Click "Add MySQL"

### Step 5: Import Database Schema
1. In Railway dashboard, go to your MySQL service
2. Click "Connect" tab to get connection details
3. Use any MySQL client (like MySQL Workbench, phpMyAdmin, or command line):
   ```bash
   mysql -h [MYSQLHOST] -u [MYSQLUSER] -p [MYSQLDATABASE] < capstone1\ \(2\).sql
   ```
4. Enter the MYSQLPASSWORD when prompted

### Step 6: Deploy and Test
1. Railway will automatically deploy your app once the database is connected
2. Wait for deployment to complete (usually 2-5 minutes)
3. Click on your app service to get the live URL
4. Open the URL in your browser to test the application

### Step 7: Verify Everything Works
- [ ] Login functionality works
- [ ] Database connections are successful
- [ ] All pages load correctly
- [ ] Forms submit properly
- [ ] File uploads work (if applicable)

## 🔧 Troubleshooting

### If deployment fails:
1. Check Railway logs in the "Logs" tab
2. Common issues:
   - Missing PHP extensions
   - Incorrect file permissions
   - Database connection issues

### If database connection fails:
1. Verify environment variables are set correctly
2. Check if MySQL service is running
3. Ensure database schema was imported correctly

## 📚 Additional Resources
- [Railway PHP Documentation](https://docs.railway.app/deploy/php)
- [Railway MySQL Guide](https://docs.railway.app/databases/mysql)
- [Railway GitHub Integration](https://docs.railway.app/deploy/github)

## 🎉 Your app will be live at: `https://your-project-name.up.railway.app`