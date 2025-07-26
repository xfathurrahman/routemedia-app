#!/bin/bash
# 2-setup-laravel.sh - Laravel setup script

echo "Setting up Laravel application..."

# Define paths
APP_PATH="/var/www/tugas03.routemedia.net.id"

# Create application directory if it doesn't exist
sudo mkdir -p $APP_PATH
sudo chown -R $USER:$USER $APP_PATH

# Clone or pull the repository
if [ -d "$APP_PATH/.git" ]; then
    echo "Git repository exists, updating..."
    cd $APP_PATH
    git pull origin main
else
    echo "Cloning the repository..."
    git clone https://github.com/xfathurrahman/routemedia-app.git $APP_PATH
fi

cd $APP_PATH

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Set proper permissions
sudo chown -R www-data:www-data $APP_PATH/storage $APP_PATH/bootstrap/cache
sudo chmod -R 775 $APP_PATH/storage $APP_PATH/bootstrap/cache

# Set up .env file
cp .env.example .env
php artisan key:generate

# Configure environment variables
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/APP_URL=.*/APP_URL=https:\/\/tugas03.routemedia.net.id/" .env

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Laravel application setup completed."
