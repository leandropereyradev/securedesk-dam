<p align="center">
  <img src="public/assets/images/secureDesk.png" width="300" alt="SecureDesk Logo">
</p>

<h1 align="center" style="font-size: 60px; font-weight: bold;">SecureDesk DAM</h1>

---

## Installation & Setup

### Prerequisites

- XAMPP installed and running
- PHP 7.4 or higher
- DOMPdf (for PDF generation) - See installation instructions below

---

## ⚠️ Important (Project Delivery)

For the **project delivery**, the application already includes:

- Database created (`securedesk`)
- All required tables
- Initial users
- Seeded tickets data

👉 This means the application can be tested **without running any initialization scripts**.

⚠️ This setup is **only for the project delivery**.  
If someone clones the repository from GitHub, these files **are NOT included**, so they must manually:

- Run the database initialization script
- Run the ticket seeding script

---

### Installing DOMPdf

DOMPdf is required for PDF report generation.

In the project root, run:
```
composer install
```

---

### Steps to Run

1. **Start XAMPP**

- Open XAMPP Control Panel
- Click "Start" next to Apache

2. **Access the Application**

- Clone the repository into the `xampp/htdocs` folder.  
- If the folder name is different, **rename it to `securedesk-dam`**, since the application URL is hardcoded as:

http://localhost/securedesk-dam/

- Open your browser and go to:

http://localhost/securedesk-dam

---

## Database Initialization (Manual Setup)

If you need to recreate the database manually:

From the project root:
```
C:\xampp\htdocs\securedesk-dam>
```
Run:
```
php config\db_init.php
```
👉 This will:

- Create the database **securedesk**
- Create all required tables:
  - users
  - tickets
  - attachments
  - ticket_comments
  - ticket_history
  - audit_logs
  - login_attempts

👉 It will also create the initial users:

- AdminLeandro (role: admin | username: admin | password: admin)
- TechLeandro (role: technician | username: technician | password: technician)
- ReaderLeandro (role: reader | username: reader | password: reader)

---

## Seeding Data (Optional)

To populate tickets for testing:

From the project root:
```
php db/seeds/tickets_seed.php
```
👉 This will insert sample tickets into the database.

---

## Roles & Permissions

Permissions are defined in:
```
config/permissions.php
```
### Roles:

- admin
  - Full access (all actions)

- technician
  - View tickets
  - Create tickets
  - Edit tickets
  - Export reports (CSV/PDF/HTML)
  - Upload/download attachments
  - Comment on tickets

- reader
  - View tickets
  - View comments and attachments
  - Export reports

---

## Project Structure (Relevant Folders)

- /config → configuration and database initialization
- /db → seeds and database scripts
- /storage/attachments → uploaded files (created automatically)
- /app → MVC structure (controllers, models, views, helpers)
- /public → index.php and assets (CSS, images, JS)

---

## Storage Behavior

- The folder /storage/attachments is created automatically when the first file is uploaded.
- No need to manually create it.

---

## Troubleshooting

- Port 80 in use? Change Apache port in XAMPP settings  
- MySQL not starting? Check if another MySQL instance is running  
- 403 Forbidden error? Verify file permissions in htdocs folder  
