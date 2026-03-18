# Plan de Implementación: Mensajes de Invitados (Guest Messaging)

Este plan detalla los pasos necesarios para permitir que usuarios sin cuenta (invitados) puedan enviar mensajes a los creadores desde sus perfiles y listados, modificando la restricción actual que requiere estar logueado.

## 1. Modificación de Rutas
**Archivo:** `routes/web.php`
- Mover las rutas `viewer.send.message.to.user` y `send.listing.message` fuera del grupo de middleware `auth`.
- Ubicarlas dentro del grupo `maintenanceMode` para mantener la protección de mantenimiento.

```php
// De:
Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    // ...
    Route::post('/viewer-send-message-to-user/{id}', [SendMessageController::class, 'viewerSendMessageToUser'])->name('viewer.send.message.to.user');
    Route::post('/send-listing-message/{id}', [SendMessageController::class, 'sendListingMessage'])->name('send.listing.message');
});

// A (fuera de auth, pero manteniendo el prefijo 'user' y nombre 'user.' si es necesario para compatibilidad):
Route::post('user/viewer-send-message-to-user/{id}', [SendMessageController::class, 'viewerSendMessageToUser'])->name('user.viewer.send.message.to.user');
Route::post('user/send-listing-message/{id}', [SendMessageController::class, 'sendListingMessage'])->name('user.send.listing.message');
```

## 2. Base de Datos (Migración)
**Tarea:** Crear una migración para añadir soporte de datos de invitados en la tabla `contact_messages`.
- Columnas a añadir: `name` (string, nullable), `email` (string, nullable).

```bash
php artisan make:migration add_guest_info_to_contact_messages_table --table=contact_messages
```

**Lógica de la migración:**
```php
$table->string('name')->nullable()->after('client_id');
$table->string('email')->nullable()->after('name');
```

## 3. Actualización del Controlador
**Archivo:** `app/Http/Controllers/User/SendMessageController.php`
- Modificar `viewerSendMessageToUser` y `sendListingMessage`.
- Si el usuario no está autenticado, validar el campo `email`.
- Guardar `name` y `email` en la base de datos.
- Usar el email del invitado para el `replyTo` en el envío de correos.

## 4. Cambios en la Vista (Frontend)
**Archivo:** `resources/views/themes/light/frontend/profile/index.blade.php` (y otros temas si aplica).
- Añadir un campo de entrada para `email` solo si `Auth::guest()`.
- Asegurar que el campo `name` no esté bloqueado ni pre-rellenado con datos de sesión inexistentes para invitados.

```html
@guest
    <div class="input-box col-12">
        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="@lang('Email Address')" required>
        @error('email') <div class="invalid-feedback">@lang($message)</div> @enderror
    </div>
@endguest
```

## 5. Panel de Administración
**Archivo:** `app/Http/Controllers/Admin/ContactMessageController.php`
- Actualizar la lógica de `contactMessageSearch` para manejar casos donde `get_client` sea nulo.
- Mostrar el `name` y `email` guardados en la tabla si el `client_id` no existe.

**Archivo:** `resources/views/admin/listingContactMessages/list.blade.php`
- Ajustar el JS para que los datos del modal de visualización reflejen correctamente la información del invitado.

## 6. Verificación y Pruebas
- [ ] Intentar enviar mensaje como usuario logueado (debe seguir funcionando).
- [ ] Intentar enviar mensaje como invitado (cerrar sesión).
- [ ] Verificar que el correo llegue al destinatario con la opción de "Responder a" el email del invitado.
- [ ] Verificar que el mensaje aparezca correctamente en el panel de administración sin errores de "null pointer".
