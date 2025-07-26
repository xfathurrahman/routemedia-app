#!/bin/bash
# 5-final-steps.sh - Final deployment steps

echo "Performing final deployment steps..."

APP_PATH="/var/www/example.domain"
cd $APP_PATH

# Make www-data own the entire Laravel application
sudo chown -R www-data:www-data $APP_PATH

# Set appropriate directory permissions
sudo find $APP_PATH -type d -exec chmod 755 {} \;
sudo chmod -R 775 $APP_PATH/storage
sudo chmod -R 775 $APP_PATH/bootstrap/cache

# Create log file and set permissions if it doesn't exist
sudo mkdir -p $APP_PATH/storage/logs
sudo touch $APP_PATH/storage/logs/laravel.log
sudo chown www-data:www-data $APP_PATH/storage/logs/laravel.log
sudo chmod 664 $APP_PATH/storage/logs/laravel.log

# Create storage symbolic link (this line is crucial for images to work)
sudo -u www-data php artisan storage:link

# Run all Laravel commands as www-data
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan optimize

# Restart services if needed
sudo systemctl restart apache2

echo "Final deployment steps completed."
