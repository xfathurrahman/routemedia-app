#!/bin/bash
# 4-build-assets.sh - Build frontend assets

echo "Building frontend assets..."

APP_PATH="/var/www/tugas03.routemedia.net.id"
cd $APP_PATH

# Install Node.js dependencies
npm ci

# Build assets
npm run build

echo "Frontend assets built successfully."
