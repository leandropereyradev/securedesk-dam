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
