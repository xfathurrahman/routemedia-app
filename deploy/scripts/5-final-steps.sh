#!/bin/bash
# 5-final-steps.sh - Final deployment steps

echo "Performing final deployment steps..."

APP_PATH="/var/www/tugas03.routemedia.net.id"
cd $APP_PATH

# Optimize Laravel
php artisan optimize

# Restart services if needed
sudo systemctl restart apache2

echo "Final deployment steps completed."
