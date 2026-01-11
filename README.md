# Admin App – Laravel 12 + Vue 3 + Inertia + TypeScript

## 📌 Descripción del Proyecto

Admin App es una aplicación administrativa desarrollada con Laravel 12 en el backend y Vue 3.5 con Inertia.js y TypeScript en el frontend.  
El proyecto sigue el patrón de arquitectura MVC (Model–View–Controller) y tiene como objetivo aplicar buenas prácticas de desarrollo de software, enfocadas en Principios SOLID y Patrones de Diseño.

Este proyecto fue desarrollado como parte de un taller formativo de desarrollo de software.

---

## 🏗 Arquitectura MVC

La aplicación está estructurada siguiendo el patrón MVC:

- Model: Maneja la representación de datos y la interacción con la base de datos.
- View: Interfaz de usuario construida con Vue 3 y TypeScript usando Inertia.js.
- Controller: Coordina las peticiones HTTP, delegando la lógica de negocio y retornando las vistas correspondientes.

Esta separación permite un código más organizado, mantenible y escalable.

---

## 🧠 Principios SOLID Aplicados

### ✅ Single Responsibility Principle (SRP)

Cada clase del sistema tiene una única responsabilidad:

- Los Controllers solo manejan la lógica de entrada y salida HTTP.
- Los Services contienen la lógica de negocio.
- Los Repositories se encargan exclusivamente del acceso a datos.

Esto evita clases con múltiples responsabilidades y facilita el mantenimiento del sistema.

---

### ✅ Dependency Inversion Principle (DIP)

Los módulos de alto nivel no dependen de implementaciones concretas, sino de abstracciones:

- Se utilizan interfaces para los repositorios.
- Los servicios y controladores dependen de estas interfaces.
- Laravel Service Container se encarga de resolver las dependencias.

Gracias a esto, el sistema queda desacoplado y es más fácil de modificar o extender.

---

## 🧩 Patrones de Diseño Implementados

### 🟦 Repository Pattern

El Repository Pattern se utiliza para encapsular el acceso a los datos y separar la lógica de persistencia del resto de la aplicación.

Beneficios:
- Abstracción del acceso a la base de datos
- Menor acoplamiento entre capas
- Facilita pruebas y mantenimiento

Ejemplo de flujo:
UserController → UserService → UserRepository → Model

---

### 🟩 Service Layer Pattern

Se implementa una capa de servicios que contiene la lógica de negocio de la aplicación.

Beneficios:
- Controllers más simples y limpios
- Reglas de negocio centralizadas
- Reutilización de lógica en distintos controladores

Ejemplo de flujo:
Controller → Service → Repository

---

## 📂 Estructura del Proyecto (Backend)

app  
├── Http  
│   └── Controllers  
├── Models  
├── Repositories  
│   ├── Contracts  
│   └── Eloquent  
├── Services  
└── Providers  

---

## 🎨 Frontend (Vue + Inertia)

El frontend está desarrollado con Vue 3 usando la Composition API y TypeScript.

Buenas prácticas aplicadas:
- Componentes con una sola responsabilidad
- Tipado fuerte con TypeScript
- Comunicación eficiente con el backend mediante Inertia.js
- Separación clara entre lógica y presentación

---

## 🧪 Beneficios de las Mejoras Implementadas

- Código más limpio y organizado
- Menor acoplamiento entre componentes
- Mayor facilidad de mantenimiento
- Arquitectura clara y escalable
- Mejor comprensión del flujo MVC

---

## 🚀 Tecnologías Utilizadas

- Laravel 12
- Vue 3.5
- Inertia.js
- TypeScript
- PHP 8+
- Vite

---

## 🌐 Proyecto Deployado

URL del proyecto:  
(Agregar aquí el link del deploy: Render, Railway, Vercel o CodeSandbox)

---

## 🎥 Video de Evidencia

En el video se explica:
- Arquitectura MVC del proyecto
- Principios SOLID aplicados
- Patrones de diseño implementados
- Mejoras realizadas en el código

Link del video:  
(Agregar aquí el enlace al video)

---

## 📁 Repositorio del Proyecto

https://github.com/orodriguez-dev/admin-app

---

## ✍ Autor

Oscar Rodríguez  
Proyecto académico – Taller Formativo de Desarrollo de Software
