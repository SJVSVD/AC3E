# Plataforma Web para Investigadores de AC3E

La plataforma web para investigadores de AC3E es una aplicación diseñada para permitir a los investigadores de AC3E (Centro Avanzado de Ingeniería Eléctrica y Electrónica) registrar y gestionar información en varios módulos. Estos módulos incluyen:

- ISI Publications
- Non ISI Publications
- Books
- Awards
- Organization of Sc events
- Participation in Sc events
- Sc Collaborations
- Conjoint projects
- Thesis students
- Postdoctoral fellows
- Outreach activities
- Patents
- Public-private connections
- Technology and knowledge transfer
- Funding sources

## Características Principales

- **Inicio de Sesión**: Los usuarios pueden iniciar sesión en la plataforma para acceder a sus datos y funcionalidades personalizadas.
- **Paneles de Administración**: Los administradores tienen acceso a paneles de administración para gestionar usuarios, datos y configuraciones de la plataforma.
- **Integración con Power BI**: La plataforma está integrada con Power BI para generar informes y visualizaciones de datos avanzados.
- **Indicadores**: Proporciona indicadores para evaluar el desempeño y la productividad de los investigadores.
- **Exportación a Excel**: Los usuarios pueden exportar datos consolidados y planillas individuales a archivos Excel para su análisis y referencia.

## Requisitos del Sistema

- **Sistema Operativo**: Compatible con Windows, MacOS o Linux.
- **Navegador Web**: Se recomienda utilizar Google Chrome, Mozilla Firefox o Microsoft Edge.
- **Node.js y npm**: Versiones compatibles: Node.js v21.5.0, npm v10.3.0.
- **PHP**: Versión compatible: PHP v8.2.4.
- **Composer**: Versión compatible: Composer v2.3.10.
- **Git**: Se requiere Git para gestionar el control de versiones del proyecto.

## Instalación y Configuración

### Paso 1: Clonar el Repositorio

git clone [URL del Repositorio]

### Paso 2: Instalar Dependencias

composer install
npm install

### Actualizar Dependencias (Opcional)
Si es necesario, puedes ejecutar los siguientes comandos para actualizar las dependencias:

composer update
npm update

### Paso 3: Migrar Tablas y Sembrar Datos
Una vez instaladas las dependencias, migra las tablas de la base de datos y siembra los datos iniciales utilizando los siguientes comandos:

php artisan migrate
php artisan db:seed --class=callEssencialDatabaseSeeders

### Bibliotecas Utilizadas

@fdograph/rut-utilities: Utilidades para el manejo del RUT en Chile.
@vueform/multiselect: Componente de selección múltiple para Vue.js.
compressorjs: Herramienta para comprimir imágenes en el navegador.
read-excel-file: Librería para leer archivos de Excel en JavaScript.
vee-validate: Biblioteca de validación de formularios para Vue.js.
vue-image-compressor: Compresor de imágenes para Vue.js.
vue-loader: Cargador de componentes para Vue.js.
vue-money-format: Formateador de moneda para Vue.js.
vue-sidebar-menu: Menú lateral para Vue.js.
vue-table-dynamic: Tabla dinámica para Vue.js.
vue-toastification: Biblioteca para notificaciones Toast para Vue.js.
xlsx: Librería para trabajar con archivos de Excel en JavaScript.


SJV© 2024.
