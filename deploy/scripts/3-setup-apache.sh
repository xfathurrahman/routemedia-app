#!/bin/bash
# 3-setup-apache.sh - Apache and SSL configuration

echo "Setting up Apache with SSL..."

# Define domain
DOMAIN="tugas03.routemedia.net.id"
APP_PATH="/var/www/$DOMAIN"

# Install Certbot if not already installed
if ! command -v certbot &> /dev/null; then
    echo "Installing Certbot..."
    sudo apt-get install -y certbot python3-certbot-apache
fi

# Create Apache virtual host configuration
sudo tee /etc/apache2/sites-available/$DOMAIN.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    ServerAdmin webmaster@$DOMAIN
    DocumentRoot $APP_PATH/public

    <Directory $APP_PATH/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/$DOMAIN-error.log
    CustomLog \${APACHE_LOG_DIR}/$DOMAIN-access.log combined

    # Redirect all HTTP traffic to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
EOF

# Enable the site
sudo a2ensite $DOMAIN.conf

# Obtain SSL certificate
sudo certbot --apache -d $DOMAIN --non-interactive --agree-tos --email webmaster@$DOMAIN

# Restart Apache
sudo systemctl restart apache2

echo "Apache configuration with SSL completed."
