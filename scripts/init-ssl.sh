#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# First-time SSL certificate setup via Let's Encrypt
#
# Run ONCE on the server BEFORE starting docker compose:
#   bash scripts/init-ssl.sh
#
# Requirements:
#   - DNS A record for it-helper-tm.ru → server IP must already propagate
#   - Ports 80 and 443 must be free (no nginx running yet)
# ─────────────────────────────────────────────────────────────────────────────
set -e

DOMAIN="it-helper-tm.ru"
EMAIL="mekan.developer@gmail.com"

echo "▶ Getting SSL certificate for ${DOMAIN} ..."

docker run --rm \
    -p 80:80 \
    -v "$(pwd)/docker-volumes/certbot/certs:/etc/letsencrypt" \
    -v "$(pwd)/docker-volumes/certbot/www:/var/www/certbot" \
    certbot/certbot certonly \
        --standalone \
        --preferred-challenges http \
        --email "${EMAIL}" \
        --agree-tos \
        --no-eff-email \
        -d "${DOMAIN}" \
        -d "www.${DOMAIN}"

echo ""
echo "✓ Certificate saved. Now start the project:"
echo "  docker compose up --build -d"
