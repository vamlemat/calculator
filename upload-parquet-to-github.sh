#!/bin/bash
# Script para subir Parquet Prices Display a GitHub
# Autor: ATECH
# Fecha: 2025-11-20

echo "🚀 Subiendo Parquet Prices Display a GitHub..."
echo ""

# Ir al directorio del módulo
cd /tmp/parquet-clone

# Verificar que estamos en el directorio correcto
if [ ! -f "parquetprices.php" ]; then
    echo "❌ Error: No se encuentra el archivo parquetprices.php"
    echo "   Asegúrate de estar en el directorio correcto"
    exit 1
fi

echo "📦 Archivos preparados:"
git status --short

echo ""
echo "📤 Subiendo al repositorio..."

# Pushear al repositorio
git push origin main

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ ¡Código subido exitosamente!"
    echo ""
    echo "🔗 Ver repositorio:"
    echo "   https://github.com/vamlemat/parquet-prices-display"
    echo ""
    echo "📋 Próximos pasos:"
    echo "   1. Crear tag v1.0.0"
    echo "   2. Crear release con el ZIP"
    echo ""
else
    echo ""
    echo "❌ Error al subir el código"
    echo "   Intenta manualmente con:"
    echo "   cd /tmp/parquet-clone"
    echo "   git push origin main"
fi
