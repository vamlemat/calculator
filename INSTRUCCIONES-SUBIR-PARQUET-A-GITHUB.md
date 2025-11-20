# 📦 Cómo Subir Parquet Prices Display a GitHub

## Archivo para Descargar

**Archivo:** `parquetprices-v1.0.0.zip` (17 KB)
**Ubicación:** `/workspace/parquetprices-v1.0.0.zip`

---

## 📋 Pasos para Subir Manualmente a GitHub

### 1. Descargar el ZIP

Este archivo contiene el módulo completo listo para PrestaShop:
- ✅ 17 archivos esenciales
- ✅ Código PHP del módulo
- ✅ Templates Smarty
- ✅ CSS y JavaScript
- ✅ README.md completo
- ✅ Logo profesional

### 2. Ir al Repositorio en GitHub

**URL:** https://github.com/vamlemat/parquet-prices-display

### 3. Subir Archivos Manualmente

**Opción A: Usar la interfaz web de GitHub**

1. Ve a: https://github.com/vamlemat/parquet-prices-display
2. Haz clic en **"Add file"** > **"Upload files"**
3. Arrastra y suelta estos archivos (descomprime el ZIP primero):
   - `parquetprices.php`
   - `config.xml`
   - `logo.png`
   - `README.md`
   - `.gitignore`
   - `index.php`
   - Y las carpetas: `views/`, `translations/`, `sql/`
4. Escribe el mensaje del commit:
   ```
   Initial commit: Parquet Prices Display v1.0.0
   ```
5. Haz clic en **"Commit changes"**

**Opción B: Usar Git localmente (si tienes acceso)**

```bash
# Clonar el repositorio
git clone https://github.com/vamlemat/parquet-prices-display.git
cd parquet-prices-display

# Descomprimir el módulo aquí
unzip /ruta/a/parquetprices-v1.0.0.zip
mv parquetprices/* .
rmdir parquetprices

# Subir
git add -A
git commit -m "Initial commit: Parquet Prices Display v1.0.0"
git push origin main
```

### 4. Crear Release v1.0.0

Una vez subido el código:

1. Ve a: https://github.com/vamlemat/parquet-prices-display/releases/new
2. Rellena:
   - **Tag version:** `v1.0.0`
   - **Release title:** `Parquet Prices Display v1.0.0 - Initial Release`
   - **Description:** (Ver abajo)
3. **Adjunta el ZIP:** `parquetprices-v1.0.0.zip`
4. Haz clic en **"Publish release"**

---

## 📝 Descripción del Release (Copia esto)

```markdown
## 📐 Parquet Prices Display v1.0.0

### 🎯 ¿Qué hace este módulo?

Invierte la visualización de precios en PrestaShop para mostrar el **precio unitario (€/m²)** como precio principal, ideal para el sector del parquet y revestimientos.

**ANTES:**
```
41,17 €
25,80 € m²
```

**DESPUÉS:**
```
25,80 € m²
Precio por paquete: 41,17 €
```

### ✨ Características

- 🔄 **Inversión automática**: Muestra €/m² como precio principal
- 🎨 **Compatible con Warehouse**: Funciona perfectamente con el tema Warehouse
- 🌍 **Formato español**: Precios con coma decimal
- 🛡️ **Manejo inteligente**: Si no hay precio unitario, muestra el precio normal
- 📱 **Responsive**: Diseño adaptado a móviles
- ⚡ **Plug & Play**: Instalar y listo

### 📥 Instalación

1. Descarga `parquetprices-v1.0.0.zip`
2. PrestaShop > Módulos > Module Manager
3. "Subir un módulo"
4. ¡Listo!

### 📋 Requisitos

- PrestaShop 1.8.2+
- Productos con precio unitario configurado
- PHP 7.1+
- Tema Warehouse (testado)

### 🔗 Documentación

Ver [README.md](https://github.com/vamlemat/parquet-prices-display/blob/main/README.md) para documentación completa.

---

**Autor:** ATECH
**Licencia:** AFL-3.0
```

---

## 🎯 Lo que Contiene el ZIP

```
parquetprices/
├── parquetprices.php          # Módulo principal (189 líneas)
├── config.xml                  # Configuración
├── logo.png                    # Logo profesional
├── README.md                   # Documentación (10 KB)
├── index.php                   # Seguridad
├── .gitignore                  # Control de versiones
│
├── translations/
│   ├── es.php                 # Traducciones español
│   └── index.php
│
├── sql/
│   └── index.php              # No requiere BD
│
└── views/
    ├── css/
    │   ├── front.css          # Estilos (63% comprimido)
    │   └── index.php
    ├── js/
    │   ├── front.js           # JavaScript (53% comprimido)
    │   └── index.php
    └── templates/
        └── hook/
            ├── unit-price-main.tpl          # Precio unitario
            ├── package-price-secondary.tpl  # Precio paquete
            └── index.php
```

---

## ✅ Checklist de Subida

- [ ] Repositorio creado: https://github.com/vamlemat/parquet-prices-display
- [ ] Código subido a main
- [ ] Tag v1.0.0 creado
- [ ] Release v1.0.0 publicado con ZIP adjunto
- [ ] README.md visible en la página principal

---

**¿Dudas?** Abre un issue en el repositorio 😊
