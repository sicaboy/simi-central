#!/bin/bash

# Simple CI/CD Deploy Script for Simi Central
set -e

echo "🚀 Starting deployment..."

# Step: Git pull
echo "📥 Pulling latest code..."
git pull origin master


# Step: Docker operations
echo "🐳 Executing Docker operations..."

# Determine docker-compose command
if command -v docker-compose &> /dev/null; then
    DOCKER_CMD="sudo docker-compose"
else
    DOCKER_CMD="sudo docker compose"
fi

# Install composer dependencies inside queue container (has composer)
echo "📦 Installing Composer dependencies..."
$DOCKER_CMD exec -T simi-central-queue composer install --no-dev --optimize-autoloader

# Run tenant migrations
echo "🗄️ Running tenant migrations..."
$DOCKER_CMD exec -T simi-central-app php artisan migrate --force

# Step: Restart Docker services
echo "🔄 Restarting Docker services..."
sudo $DOCKER_CMD restart

# Step: Install npm dependencies
echo "📦 Installing npm dependencies..."
npm install

# Step: Build production assets
echo "🔨 Building production assets..."
npm run prod

echo "✅ Deployment completed successfully!" 