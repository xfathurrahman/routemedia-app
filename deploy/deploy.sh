#!/bin/bash
# deploy.sh - Main deployment script

# Exit on error
set -e

echo "Starting deployment process..."

# Execute each deployment step
bash "$(dirname "$0")/scripts/1-setup-server.sh"
bash "$(dirname "$0")/scripts/2-setup-laravel.sh"
bash "$(dirname "$0")/scripts/3-setup-apache.sh"
bash "$(dirname "$0")/scripts/4-build-assets.sh"
bash "$(dirname "$0")/scripts/5-final-steps.sh"

echo "Deployment completed successfully!"
