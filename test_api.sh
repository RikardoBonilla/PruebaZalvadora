#!/bin/bash

echo "=== Probando API directamente ==="
echo "1. GET /api/v1/plans (público):"
curl -s -X GET http://localhost:8080/api/v1/plans -H "Accept: application/json" | head -200

echo -e "\n\n2. POST /api/v1/auth/login (obtener token):"
TOKEN_RESPONSE=$(curl -s -X POST http://localhost:8080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password","device_name":"Test"}')

echo "$TOKEN_RESPONSE" | head -20

# Extraer token si existe
TOKEN=$(echo "$TOKEN_RESPONSE" | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)

if [ ! -z "$TOKEN" ]; then
    echo -e "\n\n3. POST /api/v1/plans (crear plan con token):"
    curl -s -X POST http://localhost:8080/api/v1/plans \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -H "Authorization: Bearer $TOKEN" \
      -d '{
        "name": "Plan de Prueba",
        "monthly_price": 1999,
        "currency": "USD",
        "user_limit": 5,
        "features": ["Feature 1", "Feature 2"]
      }' | head -20
else
    echo -e "\n\nNo se pudo obtener el token."
fi

echo -e "\n\n=== URLs importantes ==="
echo "- Swagger UI: http://localhost:8080/api/documentation"
echo "- API JSON: http://localhost:8080/docs"
echo "- Test GET: http://localhost:8080/api/v1/plans"