#!/bin/bash
# Script para crear release v1.0.0 de Parquet Prices Display
# Autor: ATECH
# Fecha: 2025-11-20

echo "📦 Creando Release v1.0.0 de Parquet Prices Display..."
echo ""

cd /tmp/parquet-clone

# Crear tag
echo "🏷️ Creando tag v1.0.0..."
git tag -a v1.0.0 -m "Release v1.0.0 - Initial Release

Parquet Prices Display - Módulo para mostrar precio unitario como principal

✨ Features:
- Inversión de precios (€/m² como principal)
- Compatible con Warehouse theme
- Formato español
- Manejo inteligente de productos sin unidad
- Diseño responsive
- CSS y JS optimizados

📦 Incluye:
- Módulo completo funcional
- Documentación exhaustiva
- Plantillas Smarty
- Estilos y scripts
- Traducciones español

🎯 Perfect for:
- Tiendas de parquet y revestimientos
- Pinturas y barnices
- Materiales de construcción
- Productos vendidos por unidad de medida"

# Pushear tag
echo "📤 Subiendo tag a GitHub..."
git push origin v1.0.0

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Tag creado exitosamente"
    echo ""
    echo "📦 Creando release en GitHub..."
    
    # Crear release con el ZIP
    gh release create v1.0.0 /workspace/parquetprices-v1.0.0.zip \
      --title "Parquet Prices Display v1.0.0 - Initial Release" \
      --notes "## 📐 Parquet Prices Display v1.0.0

### 🎯 ¿Qué hace este módulo?

Invierte la visualización de precios en PrestaShop para mostrar el **precio unitario (€/m²)** como precio principal, ideal para el sector del parquet y revestimientos.

### ✨ Características

- 🔄 **Inversión automática**: Muestra €/m² como precio principal
- 🎨 **Compatible con Warehouse**: Funciona perfectamente con el tema Warehouse
- 🌍 **Formato español**: Precios con coma decimal
- 🛡️ **Manejo inteligente**: Si no hay precio unitario, muestra el precio normal
- 📱 **Responsive**: Diseño adaptado a móviles
- ⚡ **Plug & Play**: Instalar y listo

### 📥 Instalación

1. Descarga \`parquetprices-v1.0.0.zip\`
2. PrestaShop > Módulos > Module Manager
3. \"Subir un módulo\"
4. ¡Listo!

### 📋 Requisitos

- PrestaShop 1.8.2+
- Productos con precio unitario configurado
- PHP 7.1+

### 🔗 Documentación Completa

Ver [README.md](https://github.com/vamlemat/parquet-prices-display/blob/main/README.md)

---

**¿Preguntas?** Abre un [issue](https://github.com/vamlemat/parquet-prices-display/issues) 😊" \
      --repo vamlemat/parquet-prices-display
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "✅ ¡Release creado exitosamente!"
        echo ""
        echo "🔗 Ver release:"
        echo "   https://github.com/vamlemat/parquet-prices-display/releases/tag/v1.0.0"
    else
        echo ""
        echo "⚠️ Error al crear el release"
        echo "   Puedes crearlo manualmente en:"
        echo "   https://github.com/vamlemat/parquet-prices-display/releases/new"
    fi
else
    echo ""
    echo "❌ Error al subir el tag"
fi
