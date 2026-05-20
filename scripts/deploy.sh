#!/bin/bash
# Production deploy script
# Usage: bash scripts/deploy.sh
set -e

echo "▶ Pulling latest code..."
git pull

echo "▶ Building image (once)..."
docker compose build app

echo "▶ Starting services..."
docker compose up -d

echo "▶ Cleaning up unused images..."
docker image prune -f

echo ""
echo "✓ Done. Status:"
docker compose ps
