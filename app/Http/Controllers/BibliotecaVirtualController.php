<?php

namespace App\Http\Controllers;

use App\Models\BibliotecaVirtual;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BibliotecaVirtualController extends Controller
{
    public function __construct()
    {
        // Solo aplicar middleware de autenticación web para funciones administrativas
        // bibliotecaEstudiantes usa MultiGuardAuth middleware desde las rutas
        $this->middleware('auth:web')->except(['bibliotecaEstudiantes']);
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function index()
    {
        // El middleware auth:web ya verificó la autenticación
        $this->authorize('viewAny', BibliotecaVirtual::class);
        
        $recursos = BibliotecaVirtual::orderBy('orden')->orderBy('nombre')->paginate(15);
        $categorias = BibliotecaVirtual::getCategorias();
        
        return view('biblioteca_virtual.admin.index', compact('recursos', 'categorias'));
    }

    /**
     * Display biblioteca virtual for students
     */
    public function bibliotecaEstudiantes()
    {
        // El middleware MultiGuardAuth ya verificó la autenticación
        $webUser = auth('web')->user();
        $alumnoUser = auth('alumno')->user();
        
        // Determinar usuario actual (el middleware garantiza que uno existe)
        $currentUser = $webUser ?? $alumnoUser;
        $userType = $webUser ? 'web' : 'alumno';
        
        // Obtener roles de manera segura
        $rolesArray = [];
        $userRoles = [];
        
        if ($currentUser && method_exists($currentUser, 'getRoleNames')) {
            try {
                $rolesCollection = $currentUser->getRoleNames();
                $rolesArray = $rolesCollection->toArray();
                $userRoles = $rolesArray;
                
                \Log::info('BibliotecaVirtual - Usuario autenticado correctamente', [
                    'user_type' => $userType,
                    'user_id' => $currentUser->id,
                    'roles_count' => count($rolesArray),
                    'roles' => $rolesArray,
                ]);
            } catch (\Exception $e) {
                \Log::error('BibliotecaVirtual - Error obteniendo roles: ' . $e->getMessage(), [
                    'user_type' => $userType,
                    'user_id' => $currentUser->id ?? 'unknown'
                ]);
                $userRoles = [];
                $rolesArray = [];
            }
        }
        
        // Información de debug
        $debugInfo = [
            'web_authenticated' => auth('web')->check(),
            'alumno_authenticated' => auth('alumno')->check(),
            'web_user_id' => auth('web')->id(),
            'alumno_user_id' => auth('alumno')->id(),
            'current_user_type' => $userType,
            'current_user_id' => $currentUser ? $currentUser->id : null,
            'user_roles' => $rolesArray,
            'user_roles_count' => count($rolesArray),
            'user_roles_collection_class' => $currentUser ? get_class($currentUser->getRoleNames()) : null,
        ];
        
        $recursos = BibliotecaVirtual::activos()
            ->ordenado()
            ->get()
            ->groupBy('categoria');
            
        $categorias = BibliotecaVirtual::getCategorias();
        
        return view('biblioteca_virtual.estudiantes.index', compact(
            'recursos', 
            'categorias', 
            'currentUser', 
            'userType', 
            'userRoles', 
            'debugInfo'
        ))->with([
            'rolesArray' => $rolesArray, // Array simple para la vista
            'rolesCount' => count($rolesArray),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', BibliotecaVirtual::class);
        
        $categorias = BibliotecaVirtual::getCategorias();
        
        return view('biblioteca_virtual.admin.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BibliotecaVirtual::class);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'descripcion' => 'nullable|string|max:1000',
            'icono' => 'nullable|string|max:100',
            'categoria' => 'required|string|in:' . implode(',', array_keys(BibliotecaVirtual::getCategorias())),
            'activo' => 'boolean',
            'orden' => 'integer|min:0'
        ]);

        $validated['activo'] = $request->has('activo');
        $validated['orden'] = $validated['orden'] ?? 0;

        BibliotecaVirtual::create($validated);

        return redirect()->route('biblioteca-virtual.index')
            ->with('success', 'Recurso de biblioteca virtual creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BibliotecaVirtual $bibliotecaVirtual)
    {
        $this->authorize('update', $bibliotecaVirtual);
        
        $categorias = BibliotecaVirtual::getCategorias();
        
        return view('biblioteca_virtual.admin.edit', compact('bibliotecaVirtual', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BibliotecaVirtual $bibliotecaVirtual)
    {
        $this->authorize('update', $bibliotecaVirtual);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'descripcion' => 'nullable|string|max:1000',
            'icono' => 'nullable|string|max:100',
            'categoria' => 'required|string|in:' . implode(',', array_keys(BibliotecaVirtual::getCategorias())),
            'activo' => 'boolean',
            'orden' => 'integer|min:0'
        ]);

        $validated['activo'] = $request->has('activo');
        $validated['orden'] = $validated['orden'] ?? 0;

        $bibliotecaVirtual->update($validated);

        return redirect()->route('biblioteca-virtual.index')
            ->with('success', 'Recurso de biblioteca virtual actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BibliotecaVirtual $bibliotecaVirtual)
    {
        $this->authorize('delete', $bibliotecaVirtual);
        
        $bibliotecaVirtual->delete();

        return redirect()->route('biblioteca-virtual.index')
            ->with('success', 'Recurso de biblioteca virtual eliminado exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleActivo(BibliotecaVirtual $bibliotecaVirtual)
    {
        $this->authorize('update', $bibliotecaVirtual);
        
        $bibliotecaVirtual->update(['activo' => !$bibliotecaVirtual->activo]);
        
        $status = $bibliotecaVirtual->activo ? 'activado' : 'desactivado';
        
        return back()->with('success', "Recurso {$status} exitosamente.");
    }
}
