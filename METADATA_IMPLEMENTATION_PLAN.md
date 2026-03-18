# Plan de Implementación: Metadata Flexible para Listings

Este documento detalla la estrategia para permitir que los usuarios agreguen pares clave-valor (especificaciones técnicas) a los listings, permitiendo filtrado dinámico y visualización detallada.

## 1. Base de Datos
### Nueva Migración: `create_listing_meta_table`
- **Tabla:** `listing_meta`
- **Campos:**
    - `id` (Primary Key)
    - `listing_id` (Foreign Key -> listings, onDelete cascade)
    - `meta_key` (String, indexado): Nombre del atributo (ej: "Eslora", "Marca").
    - `meta_value` (Text): Valor del atributo (ej: "15 metros", "Yamaha").
    - `timestamps`

### Modelo: `App\Models\ListingMeta`
- Definir `$fillable = ['listing_id', 'meta_key', 'meta_value']`.
- Relación `belongsTo(Listing::class)`.

## 2. Actualización de Modelos Existentes
### `App\Models\Listing`
- Añadir relación `hasMany(ListingMeta::class, 'listing_id')`.
- Método `getMeta($key)` para recuperación rápida.

## 3. Interfaz de Usuario (Carga de Datos)
### Archivos: `add_listing.blade.php` y `edit_listing.blade.php`
- Añadir una nueva pestaña o sección "Especificaciones".
- Implementar un repetidor dinámico (JS) que permita añadir filas con inputs para `meta_key` y `meta_value`.
- **UI Sugerida:**
  ```html
  <div class="row">
      <input name="meta_key[]" placeholder="Característica">
      <input name="meta_value[]" placeholder="Valor">
      <button class="remove-row">X</button>
  </div>
  ```

## 4. Lógica de Negocio (Backend)
### `ListingController`
- **Store/Update:** Iterar sobre los arrays `meta_key` y `meta_value` recibidos en el request.
- Validar que si existe una llave, exista un valor.
- Sincronizar (eliminar antiguos y crear nuevos en el caso de edición).

## 5. Visualización (Frontend)
### `listing_details.blade.php`
- Crear una sección de "Especificaciones Técnicas" debajo de la descripción.
- Mostrar los datos en una tabla o lista de definiciones (`<dl>`).

### `index.blade.php` (Filtrado)
- **UI:** Añadir campos de búsqueda en el sidebar para los metadatos más relevantes o un buscador general de atributos.
- **Query:** Modificar la lógica de búsqueda para incluir `whereHas('metadata', ...)` basado en los parámetros de búsqueda dinámicos.

## 6. Próximos Pasos Sugeridos
1. Ejecutar la migración.
2. Implementar la relación en el modelo.
3. Modificar el formulario de creación para probar la persistencia de datos.
