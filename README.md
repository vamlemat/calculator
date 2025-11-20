# 🧮 Calculator - Módulo de Calculadora de Precio para PrestaShop

![Version](https://img.shields.io/badge/version-1.2.0-blue.svg)
![PrestaShop](https://img.shields.io/badge/PrestaShop-1.8.2+-green.svg)
![License](https://img.shields.io/badge/license-AFL--3.0-orange.svg)
![Author](https://img.shields.io/badge/author-ATECH-purple.svg)

## 📋 Descripción

**Calculator** es un módulo profesional para PrestaShop que añade una **calculadora de precio inteligente** en las páginas de productos. Especialmente diseñado para productos que se venden por unidades de medida (m², metros lineales, litros, etc.), permite a los clientes calcular fácilmente cuántos paquetes necesitan según el área o cantidad deseada.

### ✨ Características Principales

- 🧮 **Calculadora en tiempo real**: Los clientes introducen el área/cantidad deseada y ven al instante:
  - Número de paquetes necesarios
  - Área/cantidad total cubierta
  - Precio total calculado
  
- ⚙️ **Configuración por categoría**: Activa y personaliza la calculadora para categorías específicas

- 🌍 **Formato español**: Números con coma decimal (1.234,56€) compatible con PrestaShop en español

- 💾 **Persistencia de estado**: Mantiene los cálculos al navegar entre páginas usando sessionStorage

- 📱 **Diseño responsive**: Interfaz moderna compatible con cualquier tema de PrestaShop

- 🔌 **Sin overrides PHP**: Mayor estabilidad y compatibilidad con otros módulos

---

## 🎯 Casos de Uso Ideales

Este módulo es perfecto para tiendas que venden:

- 🏠 **Suelos y revestimientos** (baldosas, tarima, vinilo)
- 🎨 **Pinturas y barnices** (por litros/m²)
- 🧱 **Materiales de construcción** (cemento, yeso)
- 🌿 **Césped artificial** (por metros cuadrados)
- 📦 **Productos empaquetados** con cobertura específica
- 🛠️ **Materiales industriales** vendidos por unidades de medida

---

## 📥 Instalación

### Método 1: Desde el Back-Office (Recomendado)

1. Descarga el archivo `calculator-v1.2.0.zip` desde la [página de releases](https://github.com/vamlemat/calculator/releases)
2. En tu back-office de PrestaShop, ve a **Módulos > Module Manager**
3. Haz clic en **"Subir un módulo"**
4. Selecciona el archivo ZIP descargado
5. Haz clic en **"Configurar"** tras la instalación

### Método 2: Por FTP

1. Descomprime el archivo ZIP
2. Sube la carpeta `calculator` a `/modules/` en tu servidor
3. Ve a **Módulos > Module Manager** en el back-office
4. Busca "Calculator" e instálalo

---

## ⚙️ Configuración

### 1. Configuración General del Módulo

1. Ve a **Módulos > Module Manager**
2. Busca "Calculator" y haz clic en **Configurar**
3. Introduce el **ID del atributo** que contiene el tamaño del paquete (ej: m² por paquete)
4. Guarda los cambios

### 2. Configuración por Categoría

Para cada categoría donde quieras activar la calculadora:

1. Ve a **Catálogo > Categorías**
2. Edita la categoría deseada
3. En la sección "Calculator", encontrarás:
   - ✅ **Activar calculadora**: Checkbox para habilitar/deshabilitar
   - 📏 **Unidad de medida**: Introduce la unidad (ej: "m²", "ml", "kg")
4. Guarda los cambios

### 3. Configuración de Productos

Cada producto debe tener configurada la **característica** (feature) que especifica:
- **Contenido por paquete** (ej: "1.5" para 1,5 m² por paquete)

Esta característica se usa para calcular cuántos paquetes se necesitan.

---

## 🚀 Uso

### Para el Cliente

1. El cliente navega a un producto con calculadora activada
2. Ve un formulario con el campo "Área" (o la unidad configurada)
3. Introduce la cantidad deseada (ej: "45.5" o "45,5")
4. La calculadora muestra automáticamente:
   ```
   3 paquete(s) = 45,50m²
   
   1.234,56€ / Paquete
   (3.703,68€ / total)
   ```
5. El campo de cantidad se actualiza automáticamente
6. Hace clic en "Añadir al carrito"

### Ejemplo Visual

```
┌─────────────────────────────────────────┐
│  Área                                   │
│  ┌──────────────────┐                  │
│  │ 45.50            │ m²               │
│  └──────────────────┘                  │
│                                         │
│  3 paquete(s) = 45,50m²                │
│                                         │
│  1.234,56€ / Paquete                   │
│  (3.703,68€ / total)                   │
└─────────────────────────────────────────┘
```

---

## 📋 Requisitos Técnicos

| Requisito | Versión Mínima | Recomendada |
|-----------|----------------|-------------|
| **PrestaShop** | 1.8.2 | 1.8.8+ |
| **PHP** | 7.1 | 7.4+ |
| **MySQL** | 5.6 | 5.7+ |
| **jQuery** | Incluido en PS | - |

---

## 🗄️ Estructura de la Base de Datos

El módulo crea la siguiente tabla:

```sql
CREATE TABLE `ps_calculator` (
  `id_category` INT(11) NOT NULL,
  `activado` TINYINT(1) NOT NULL DEFAULT 0,
  `unidad` VARCHAR(5) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

---

## 🎨 Personalización

### Estilos CSS

Puedes personalizar el diseño editando:
- `/modules/calculator/views/css/front.css`

Clases CSS principales:
```css
.calculator { /* Contenedor principal */ }
.calculator-container { /* Contenedor interno */ }
.unit-entry { /* Campo de entrada */ }
#packaging-count { /* Contador de paquetes */ }
#calculator-message { /* Mensajes de precio */ }
.error { /* Estado de error */ }
```

### Templates Smarty

Templates disponibles para modificar:
- `/views/templates/hook/calculator.tpl` - Interfaz de la calculadora
- `/views/templates/admin/categoryForm.tpl` - Formulario en categorías
- `/views/templates/admin/configure.tpl` - Configuración del módulo

---

## 🔧 Hooks Utilizados

El módulo se integra con PrestaShop mediante los siguientes hooks:

| Hook | Propósito |
|------|-----------|
| `header` | Cargar CSS y JS en el frontend |
| `backOfficeHeader` | Cargar assets en el backoffice |
| `displayProductAdditionalInfo` | Mostrar la calculadora en la página del producto |
| `displayBackOfficeCategory` | Formulario de configuración en categorías |
| `actionCategoryUpdate` | Guardar configuración de categoría |
| `actionFrontControllerSetVariables` | Inyectar variables al template |

---

## 🐛 Solución de Problemas

### La calculadora no aparece

1. ✅ Verifica que el módulo esté instalado y activado
2. ✅ Comprueba que la categoría tenga la calculadora activada
3. ✅ Asegúrate de que el producto tiene la característica configurada
4. ✅ Limpia la caché de PrestaShop

### Los números no se formatean correctamente

- El módulo usa formato español por defecto (coma decimal)
- Acepta entrada con coma o punto, pero muestra siempre con coma

### La cantidad no se actualiza en el carrito

- Verifica que el selector `#quantity_wanted` existe en tu tema
- Revisa la consola del navegador para errores JavaScript

### Error al instalar

- Verifica los permisos de escritura en `/modules/calculator/`
- Comprueba que tu versión de PrestaShop sea compatible (1.8.2+)

---

## 📦 Contenido del Paquete

```
calculator/
├── calculator.php          # Módulo principal
├── config.xml             # Configuración (inglés)
├── config_es.xml          # Configuración (español)
├── logo.png               # Icono del módulo
├── index.php              # Seguridad
├── README.md              # Esta documentación
│
├── sql/
│   ├── install.php        # Script de instalación
│   ├── uninstall.php      # Script de desinstalación
│   └── index.php
│
├── translations/
│   ├── es.php             # Traducciones al español
│   └── index.php
│
├── upgrade/
│   ├── upgrade-1.1.0.php
│   ├── upgrade-1.2.0.php
│   └── index.php
│
└── views/
    ├── css/
    │   ├── front.css      # Estilos frontend
    │   ├── back.css       # Estilos backoffice
    │   └── bootstrap.min.css
    ├── js/
    │   ├── front.js       # Calculadora JavaScript
    │   ├── back.js        # Scripts backoffice
    │   └── bootstrap.min.js
    └── templates/
        ├── admin/
        │   ├── configure.tpl
        │   └── categoryForm.tpl
        └── hook/
            ├── calculator.tpl
            └── productFooter.tpl
```

---

## 📝 Changelog

### [1.2.0] - 2025-11-20

#### ✨ Añadido
- Nuevo icono de calculadora profesional (reemplaza logo de 8PECADOS)
- Formato de números en español con coma decimal (1.234,56€)
- Separador de miles con punto para mejor legibilidad

#### 🔧 Mejorado
- Optimización del tamaño del logo (75% más pequeño)
- Mejora en la presentación de precios y cantidades
- Documentación completa en README.md

#### 🐛 Corregido
- Coherencia del formato numérico con PrestaShop en español

---

### [1.1.0] - 2025-11-20

#### ✨ Añadido
- Actualización de compatibilidad a PrestaShop 1.8.2+
- Nuevo autor: ATECH

#### ❌ Eliminado
- Overrides PHP de ps_shoppingcart (mejora la estabilidad)
- 11 archivos obsoletos y duplicados
- 899 líneas de código innecesarias

#### 🔧 Mejorado
- Estructura del código optimizada
- Limpieza completa del repositorio
- Mejoras en la persistencia de estado (sessionStorage)

---

### [1.0.0] - 2024-07-02

#### ✨ Añadido
- Versión inicial del módulo
- Calculadora de precio por área/superficie
- Configuración por categoría
- Integración con sistema de accesorios
- Soporte multiidioma

---

## 👨‍💻 Desarrollo

### Clonar el Repositorio

```bash
git clone https://github.com/vamlemat/calculator.git
cd calculator
```

### Estructura del Código JavaScript

La calculadora está implementada como una clase ES6:

```javascript
class PackagingCalculator {
  constructor($calculator) { ... }
  cacheDom() { ... }
  bind() { ... }
  update() { ... }
  // Más métodos...
}
```

---

## 🤝 Contribuir

Las contribuciones son bienvenidas! Por favor:

1. Haz un fork del repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto está licenciado bajo la **Academic Free License (AFL 3.0)**.

Consulta el archivo [LICENSE.txt](http://opensource.org/licenses/afl-3.0.php) para más detalles.

---

## 👤 Autor

**ATECH**

---

## 🔗 Enlaces

- 📦 [Releases](https://github.com/vamlemat/calculator/releases)
- 🐛 [Reportar un Bug](https://github.com/vamlemat/calculator/issues)
- 💡 [Solicitar una Funcionalidad](https://github.com/vamlemat/calculator/issues)

---

## ⭐ Agradecimientos

Gracias por usar Calculator! Si este módulo te ha sido útil, considera:

- ⭐ Dar una estrella al repositorio
- 🐛 Reportar bugs y sugerir mejoras
- 📢 Compartir el módulo con otros usuarios de PrestaShop

---

**¿Preguntas o necesitas soporte?** Abre un [issue en GitHub](https://github.com/vamlemat/calculator/issues) y te ayudaremos encantados! 😊
