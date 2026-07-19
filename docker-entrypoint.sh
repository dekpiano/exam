#!/bin/bash
set -e

# Change directory to project root
cd /var/www/html

# Run composer install if composer.json exists and vendor directory is empty
if [ -f "composer.json" ] && [ ! -f "vendor/autoload.php" ]; then
    echo "Installing dependencies..."
    composer install --no-interaction --prefer-dist --ignore-platform-reqs
fi

# Ensure writable directory permissions
echo "🛡 Setting permissions for writable/ folder..."
mkdir -p writable/cache writable/session writable/debugbar writable/logs
chmod -R 777 writable/

# Execute CMD
exec "$@"
