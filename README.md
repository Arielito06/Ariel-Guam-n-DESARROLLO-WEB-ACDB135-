# Práctica: Agenda Personal de Contactos con PHP y MySQL

**Asignatura:** Desarrollo Web (ACD y APE) - 5to Ciclo TI
**Universidad:** Universidad Técnica Particular de Loja (UTPL)
**Requisitos:** XAMPP (Apache y MySQL), Navegador web, VS Code. 

## Propósito
Desarrollar una aplicación web interactiva con autenticación de usuarios. El sistema permite registrar, iniciar sesión y gestionar una agenda personal de contactos. Se aplican buenas prácticas de seguridad como lo es la encriptación de contraseñas y protección de datos por usuario y se implementan mejoras de usabilidad como búsqueda, ordenamiento y favoritos.

## ¿Cómo ejecutarlo?
1. Descargar el repositorio y colocar la carpeta del proyecto dentro de `C:\xampp\htdocs\`.
2. Abrir XAMPP y encender los módulos **Apache** y **MySQL**.
3. Abrir el navegador e ir a `http://localhost/phpmyadmin/`.
4. Crear una base de datos llamada **`agenda_contactos`** e importar el archivo `.sql` incluido en el repositorio.
5. Abrir el navegador e ingresar al sistema en: `http://localhost/NombreDeTuCarpeta/login.php`.

## Funcionalidades Desarrolladas 
1. **Autenticación Segura:** Registro e inicio de sesión. Las contraseñas se guardan encriptadas usando `password_hash()` y se validan con `password_verify()`. Incluye medidor de fuerza de contraseña.
2. **CRUD de Contactos:** Agregar, editar y eliminar contactos con validaciones de formulario (ej. el teléfono exige exactamente 10 dígitos).
3. **Privacidad de Datos:** Cada contacto nuevo recibe un `id_usuario`. El sistema filtra las consultas SQL para que un usuario solo vea y modifique sus propios contactos, bloqueando accesos por URL.
4. **Mejoras de Usabilidad :** 
   * Búsqueda dinámica por nombre, correo o ciudad.
   * Ordenamiento alfabético y por fecha.
   * Sistema de "Favoritos" representado por una estrella ⭐con filtro exclusivo.
5. **Recuperación de Clave:** Opción en el Login para restablecer la contraseña en caso de olvido.

## Preguntas de cierre 

**1. ¿Por qué es importante usar `password_hash()` y no guardar la contraseña en texto plano?**
*   **Respuesta:** Porque `password_hash()` convierte la contraseña en un "hash" irreversible usando algoritmos seguros como Bcrypt. Si un hacker lograra acceder a la base de datos, solo vería códigos extraños (como `$2y$10$...`) y no las contraseñas reales. Además, guardar en texto plano es una vulnerabilidad crítica porque los usuarios suelen reutilizar sus contraseñas en otras plataformas (correo, redes sociales).

**2. ¿Cómo ayuda la columna `id_usuario` en la tabla de contactos a garantizar la privacidad entre diferentes usuarios?**
*   **Respuesta:** Funciona como un candado de pertenencia. Al guardar un contacto, se le asigna el `id_usuario` de la persona que inició sesión. Cuando cualquier usuario entra a su agenda, el sistema hace una consulta filtrada (`WHERE id_usuario = ?`). De esta forma, la base de datos solo le devuelve los contactos cuyo dueño coincide con su ID, aislando la información por completo.

**3. ¿Por qué usamos sentencias preparadas (`bind_param`) en las consultas a la base de datos?**
*   **Respuesta:** Para prevenir ataques de **Inyección SQL**. Al usar `bind_param`, separamos el código SQL de los datos que ingresa el usuario. Si alguien intenta escribir código malicioso (como `' OR 1=1 --`) en un formulario, el sistema lo tratará como texto simple y no como un comando ejecutable, protegiendo la base de datos.

**4. ¿Qué sucedería si un usuario intenta cambiar el ID en la URL para editar un contacto ajeno?**
*   **Respuesta:** El sistema lo bloqueará. En el archivo `editar_contacto.php` o `borrar_contacto.php`, la consulta SQL exige que el contacto coincida tanto con el `id` de la URL como con el `id_usuario` de la sesión (`WHERE id = ? AND id_usuario = ?`). Si el dueño no coincide, la base de datos no encuentra el registro y el sistema expulsa al usuario, redirigiéndolo automáticamente a su propia agenda.

## Entregable
Repositorio de GitHub con el código actualizado, el script SQL de la base de datos y este archivo README.

---
*Desarrollado por Ariel Anthony Guamán Castillo - 2026*