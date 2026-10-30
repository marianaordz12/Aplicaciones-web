@extends('layouts.app')
@section('title', 'Listado')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Listado de productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

{{-- Formulario GET: los datos viajan en la URL --}}
<form method="GET" action="{{ route('productos.index') }}" class="row g-2 mb-4">
    <div class="col-12 col-md-5">
        <input type="text" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por nombre...">
    </div>
    <div class="col-8 col-md-4">
        <select name="categoria" class="form-select">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $cat)
                <option value="{{ $cat }}" @selected($cat === $categoria)>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-4 col-md-3 d-grid d-md-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $p)
                    <tr>
                        <td>{{ $p['id'] }}</td>
                        <td>{{ $p['nombre'] }}</td>
                        <td><span class="badge text-bg-secondary">{{ $p['categoria'] }}</span></td>
                        <td class="text-end">${{ number_format($p['precio'], 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('productos.edit', $p['id']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                data-bs-toggle="modal" data-bs-target="#modalEliminar" 
                                data-id="{{ $p['id'] }}" data-nombre="{{ $p['nombre'] }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin resultados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal de confirmación para DELETE --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEliminar" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar <strong id="nombreEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('modalEliminar').addEventListener('show.bs.modal', (e) => {
    const btn = e.relatedTarget;
    const id = btn.dataset.id;
    document.getElementById('nombreEliminar').textContent = btn.dataset.nombre;
    document.getElementById('formEliminar').action = `{{ url('productos') }}/${id}`;
});
</script>
@endsection
```[cite: 6, 7, 8]

---

### Paso 3: Crear la vista de Crear Producto (Ejercicio 2 - POST)
Crea el archivo **`create.blade.php`** dentro de `resources/views/productos/`[cite: 8] y pega:

```blade
@extends('layouts.app')
@section('title', 'Nuevo producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h1 class="h5 mb-0">Nuevo producto</h1></div>
            <div class="card-body">
                <form method="POST" action="{{ route('productos.store') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" id="categoria" name="categoria" value="{{ old('categoria') }}" class="form-control @error('categoria') is-invalid @enderror">
                        @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" id="precio" name="precio" value="{{ old('precio') }}" class="form-control @error('precio') is-invalid @enderror">
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```[cite: 8, 9]

---

### Paso 4: Crear la vista de Editar Producto (Ejercicio 3 - PUT)
Crea el archivo **`edit.blade.php`** dentro de `resources/views/productos/`[cite: 9] y pega:

```blade
@extends('layouts.app')
@section('title', 'Editar producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h1 class="h5 mb-0">Editar producto #{{ $producto['id'] }}</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('productos.update', $producto['id']) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto['nombre']) }}" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $producto['categoria']) }}" class="form-control @error('categoria') is-invalid @enderror">
                        @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" id="precio" name="precio" value="{{ old('precio', $producto['precio']) }}" class="form-control @error('precio') is-invalid @enderror">
                            @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Actualizar</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```[cite: 9, 10]

---

### Paso 5: Asegurarte de tener las Rutas y el Controlador
Verifica haber configurado en `routes/web.php`[cite: 2, 3]:
```php
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/productos');

Route::resource('productos', ProductoController::class)
    ->except(['show'])
    ->parameters(['productos' => 'id']);
```[cite: 3]

Y el controlador en `app/Http/Controllers/ProductoController.php` creado con `php artisan make:controller ProductoController`[cite: 3].

---

### Paso 6: Probar la aplicación
Ejecuta los servidores en dos terminales abiertas en la carpeta del proyecto (`crud-http12`)[cite: 11]:

* **Terminal 1:** `php artisan serve`[cite: 11]
* **Terminal 2:** `npm run dev`[cite: 1, 11]

Abre en tu navegador `http://localhost:8000` para probar el CRUD completo[cite: 11].