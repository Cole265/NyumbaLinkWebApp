#!/bin/bash
# tests/auth_test.sh - Test authentication and get token

set -e

BASE_URL="${1:-http://localhost:8000}"
EMAIL="${2:-landlord@example.com}"
PASSWORD="${3:-password}"

echo "🔐 Testing Authentication"
echo "========================"
echo "Base URL: $BASE_URL"
echo "Email: $EMAIL"
echo ""

# Step 1: Login to get token
echo "📝 Step 1: Logging in..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/api/v1/login" \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d "{
    \"email\": \"$EMAIL\",
    \"password\": \"$PASSWORD\"
  }")

echo "Login Response:"
echo "$LOGIN_RESPONSE" | jq '.' 2>/dev/null || echo "$LOGIN_RESPONSE"
echo ""

# Extract token
TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token' 2>/dev/null || echo "")

if [ -z "$TOKEN" ] || [ "$TOKEN" == "null" ]; then
  echo "❌ Login failed - could not get token"
  exit 1
fi

echo "✅ Got token: ${TOKEN:0:20}..."
echo ""

# Step 2: Get user info
echo "👤 Step 2: Getting user info..."
USER_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/user" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json")

echo "User Response:"
echo "$USER_RESPONSE" | jq '.' 2>/dev/null || echo "$USER_RESPONSE"
echo ""

# Extract role
ROLE=$(echo "$USER_RESPONSE" | jq -r '.role' 2>/dev/null || echo "")
echo "User Role: $ROLE"
echo ""

if [ "$ROLE" != "landlord" ]; then
  echo "⚠️  User is not a landlord. Expected role 'landlord', got '$ROLE'"
  echo "Note: Only landlords can publish properties"
fi

# Step 3: Test payment endpoint
echo "💰 Step 3: Testing payment endpoint..."
PAYMENT_RESPONSE=$(curl -s -X POST "$BASE_URL/api/v1/payments/listing-fee" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{
    "property_id": 1,
    "payment_method": "mobile_money"
  }')

echo "Payment Response:"
echo "$PAYMENT_RESPONSE" | jq '.' 2>/dev/null || echo "$PAYMENT_RESPONSE"
echo ""

# Check if successful
SUCCESS=$(echo "$PAYMENT_RESPONSE" | jq -r '.success' 2>/dev/null || echo "")

if [ "$SUCCESS" == "true" ]; then
  echo "✅ Payment endpoint is working!"
else
  echo "❌ Payment endpoint returned error"
fi

# Step 4: Display token for manual testing
echo ""
echo "🔑 Save this token for manual testing in browser:"
echo "=================================================="
echo "$TOKEN"
echo ""
echo "To use in browser console:"
echo "localStorage.setItem('auth_token', '$TOKEN');"
echo ""
