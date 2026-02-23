# SecureDesk DAM

## Installation & Setup

### Prerequisites

- XAMPP installed and running
- PHP 7.4 or higher

### Steps to Run

1. **Start XAMPP**

- Open XAMPP Control Panel
- Click "Start" next to Apache and MySQL

2. **Access the Application**

- Open your browser
- Clone the repository into the `xampp/htdocs` folder. If the folder name is different, **rename it to `securedesk-dam`**, since the application URL is hardcoded as `http://localhost/securedesk-dam/` in the configuration.
- Navigate to `http://localhost/securedesk-dam` in your browser

3. **Initial Configuration**

- Follow the setup wizard if this is your first time
- Configure database connection if needed

### Troubleshooting

- **Port 80 in use?** Change Apache port in XAMPP settings
- **MySQL not starting?** Check if another MySQL instance is running
- **403 Forbidden error?** Verify file permissions in htdocs folder

## User Database Initialization

When the application is executed for the first time, the **Users database** is created automatically if it does not exist.

At that moment, the system also creates **three initial users**:

- **admin** (role: admin)
- **tecnico** (role: tecnico)
- **lector** (role: lector)

These users are created **only once**, when the Users database is created for the first time.

On later executions:

- The Users database is not recreated
- No new users are added automatically
- Existing users are not modified

Passwords are stored securely and are not saved in plain text.

If it is necessary to create the initial users again, the Users database file must be deleted and the application run again.
