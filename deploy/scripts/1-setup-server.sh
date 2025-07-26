#!/bin/bash
# 1-setup-server.sh - Server setup script

echo "Setting up server dependencies..."

# Install necessary packages
sudo apt-get update
sudo apt-get install -y curl zip unzip git apache2 libapache2-mod-php php-mbstring php-xml php-curl php-mysql php-zip php-gd php-cli

# Install Composer
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# Enable required Apache modules
sudo a2enmod rewrite ssl headers

echo "Server dependencies installed successfully."
