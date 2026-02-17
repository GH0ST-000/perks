#!/bin/bash

# Script to add BOG credentials to .env file

echo "Adding BOG credentials to .env file..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "Error: .env file not found!"
    exit 1
fi

# Check if BOG credentials already exist
if grep -q "BOG_BASE_URL" .env; then
    echo "BOG credentials already exist in .env"
else
    # Add BOG credentials to .env
    cat >> .env << 'EOF'

# Bank of Georgia Payment Gateway
BOG_BASE_URL=https://api.bog.ge
BOG_CLIENT_ID=10003598
BOG_SECRET_KEY=BgE7o9MC4zzf
BOG_MERCHANT_ID=000000009812A1U
BOG_TERMINAL_ID=POS383CQ
BOG_CLIENT_INN=400455690
EOF
    echo "✓ BOG credentials added to .env"
fi

# Clear config cache
echo "Clearing Laravel config cache..."
php artisan config:clear

echo "✓ Config cache cleared"
echo ""
echo "Setup complete! You can now test the payment integration."

