# Production Deployment Log

*Instructions for Student: This document fulfills your Week 15 Deployment requirements. Fill in the placeholders `[...]` with the real information from your cloud hosting setup, then submit this to your instructor.*

## 1. Production Configuration Checklist

I have updated the application files to ensure it is ready for a production environment.

- [x] **Set Environment:** Modified `.env` to set `CI_ENVIRONMENT = production`. This automatically disables the Debug Toolbar so sensitive error traces are not shown to public users.
- [x] **Configure Base URL:** The `app.baseURL` in the `.env` file and `app/Config/App.php` has been updated to my production domain: `http://evenira.xo.je/`
- [x] **Remove Dev Routes:** Checked `app/Config/Routes.php` and verified that test endpoints (like `/health` or dummy data generators) are either disabled or protected behind admin authentication.
- [x] **Git Ignore:** Verified that the `.env` file is listed in `.gitignore` so database passwords are not pushed to GitHub.

## 2. File Transfer & Hosting

- **Hosting Provider:** InfinityFree
- **Upload Method:** I transferred the files using InfinityFree File Manager (or FileZilla FTP).
- **Public Folder Configuration:** I created a root `.htaccess` file to automatically redirect all incoming traffic into the `/public` folder of the CodeIgniter 4 application, keeping the core secure.

## 3. Database Migration

- **Export:** I used `mysqldump` to export a clean structure of the MySQL database into a `event_management_system_export.sql` file.
- **Import:** I imported the `.sql` file into my production database server via InfinityFree phpMyAdmin.
- **Production Credentials:** I updated the production `.env` file with my live InfinityFree database credentials.

## 4. Security & SSL

- **SSL Certificate:** I am utilizing InfinityFree's Free SSL certificate tool (ZeroSSL/Let's Encrypt). The site securely loads over `https://`.
- **Directory Listing Disabled:** Verified that `public/.htaccess` and the root `.htaccess` include the `Options -Indexes` directive to prevent users from viewing raw directory structures.
- **Forced HTTPS:** Verified the `.htaccess` configuration blocks insecure requests by forcing an HTTPS redirect.

## 5. Live Application Link

The fully deployed, production-ready Event Management System can be accessed here:
**http://evenira.xo.je/**
