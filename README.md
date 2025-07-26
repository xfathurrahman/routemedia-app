# RouteMedia App Deployment Guide

This guide provides instructions for deploying the RouteMedia application to a production server.

## Prerequisites

- Ubuntu/Debian server
- SSH access with sudo privileges
- Domain pointing to your server
- Git

## Deployment Process

The deployment process is automated through a series of scripts located in the `deploy` directory.

### Quick Deploy

To deploy the application, run:

```bash
./deploy-scripts/deploy.sh
```

### Deployment Scripts

The deployment process consists of the following steps:

1. **Server Setup** - Installs necessary packages and dependencies
2. **Laravel Setup** - Configures the Laravel application
3. **Apache Setup** - Configures Apache web server
4. **Asset Building** - Compiles frontend assets
5. **Final Steps** - Applies permissions and optimizes the application

## Example Login Credentials

After deployment, you can log in with these example credentials:

- Email: admin@routemedia.com
- Password: 12345678

## Server Maintenance

### Clearing Cache

```bash
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan optimize
```

### Restarting Web Server

```bash
sudo systemctl restart apache2
```

## Project Structure

- `app/` - Application code
- `bootstrap/` - Laravel bootstrap files
- `config/` - Configuration files
- `database/` - Migrations and seeders
- `deploy/` - Deployment scripts
- `public/` - Publicly accessible files
- `resources/` - Frontend resources
- `routes/` - Application routes
- `storage/` - Application storage
- `tests/` - Application tests
