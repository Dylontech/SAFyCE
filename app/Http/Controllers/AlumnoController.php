<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Especialidade;
use Illuminate\Http\Request;

/**
 * Clase AlumnoController
 * @package App\Http\Controllers
 */
class AlumnoController extends Controller
{
    /**
     * Muestra una lista de los recursos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        verificarAutenticacion();
        $query = Alumno::query();

        // Aplicar filtros manteniendo los parámetros en la paginación
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('Nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('numero_control', 'like', '%' . $searchTerm . '%')
                  ->orWhere('CURP', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('grupo')) {
            $query->where('Grupo', $request->grupo);
        }

        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        // Paginar manteniendo todos los parámetros de la solicitud
        $alumnos = $query->paginate(10)->appends($request->query());

        // Mensajes informativos
        $message = null;
        $messageType = null;

        if ($request->filled('search') && $alumnos->isEmpty()) {
            $message = "No se encontraron alumnos con los criterios de búsqueda: '{$request->search}'";
            $messageType = 'warning';
        } elseif ($request->filled('search')) {
            $message = "Búsqueda realizada por: '{$request->search}' - Se encontraron {$alumnos->total()} resultados";
            $messageType = 'info';
        } elseif ($request->filled('grupo') && $alumnos->isEmpty()) {
            $message = "No se encontraron alumnos en el grupo: '{$request->grupo}'";
            $messageType = 'warning';
        } elseif ($request->filled('grupo')) {
            $message = "Filtrado por grupo: '{$request->grupo}' - Se encontraron {$alumnos->total()} resultados";
            $messageType = 'info';
        } elseif ($request->filled('especialidad') && $alumnos->isEmpty()) {
            $message = "No se encontraron alumnos en la especialidad: '{$request->especialidad}'";
            $messageType = 'warning';
        } elseif ($request->filled('especialidad')) {
            $message = "Filtrado por especialidad: '{$request->especialidad}' - Se encontraron {$alumnos->total()} resultados";
            $messageType = 'info';
        }

        // Definir los grupos y especialidades
        $grupos = Alumno::select('Grupo')->distinct()->orderBy('Grupo')->pluck('Grupo');
        $especialidadesDB = Especialidade::orderBy('nombre')->get();

        return view('alumno.index', compact('alumnos', 'grupos', 'especialidadesDB', 'message', 'messageType'))
            ->with('i', (request()->input('page', 1) - 1) * $alumnos->perPage());
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        verificarAutenticacion();
        $alumno = new Alumno();
        $especialidades = Especialidade::orderBy('nombre')->get();
        return view('alumno.create', compact('alumno', 'especialidades'));
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        verificarAutenticacion();
        $request->validate([
            'numero_control' => 'required|string|max:255|unique:alumnos,numero_control',
            'CURP' => 'required|string|max:18|unique:alumnos,CURP',
            'especialidad' => 'required|string|max:255',
            'semestre' => 'required|integer|min:1|max:12',
            'Grupo' => 'required|string|max:10',
            'Nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumnos,email',
            'estatus' => 'required|string|max:50'
        ], [
            'numero_control.required' => 'El número de control es obligatorio',
            'numero_control.unique' => 'El número de control ya está registrado',
            'CURP.required' => 'La CURP es obligatoria',
            'CURP.unique' => 'La CURP ya está registrada',
            'CURP.max' => 'La CURP debe tener máximo 18 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El formato del email no es válido',
            'email.unique' => 'El email ya está registrado',
            'semestre.min' => 'El semestre debe ser al menos 1',
            'semestre.max' => 'El semestre no puede ser mayor a 12'
        ]);

        try {
            $alumno = new Alumno([
                'numero_control' => $request->numero_control,
                'CURP' => $request->CURP,
                'especialidad' => $request->especialidad,
                'semestre' => $request->semestre,
                'Grupo' => $request->Grupo,
                'Nombre' => $request->Nombre,
                'email' => $request->email,
                'estatus' => $request->estatus
            ]);

            $alumno->save();

            if ($request->ajax()) {
                return response()->json(['success' => 'Alumno creado exitosamente.']);
            }

            return redirect()->route('alumnos.index')
                ->with('success', 'Alumno creado exitosamente.');
                
        } catch (\Exception $e) {
            $errorMessage = 'Error al crear el alumno: ' . $e->getMessage();
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Muestra el recurso especificado.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        verificarAutenticacion();
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Alumno no encontrado.');
        }

        return view('alumno.show', compact('alumno'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        verificarAutenticacion();
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Alumno no encontrado.');
        }

        $especialidades = Especialidade::orderBy('nombre')->get();
        return view('alumno.edit', compact('alumno', 'especialidades'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Alumno $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumno $alumno)
    {
        verificarAutenticacion();
        $request->validate([
            'numero_control' => 'required|string|max:255|unique:alumnos,numero_control,' . $alumno->id,
            'CURP' => 'required|string|max:18|unique:alumnos,CURP,' . $alumno->id,
            'especialidad' => 'required|string|max:255',
            'semestre' => 'required|integer|min:1|max:12',
            'Grupo' => 'required|string|max:10',
            'Nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumnos,email,' . $alumno->id,
            'estatus' => 'required|string|max:50'
        ], [
            'numero_control.required' => 'El número de control es obligatorio',
            'numero_control.unique' => 'El número de control ya está registrado',
            'CURP.required' => 'La CURP es obligatoria',
            'CURP.unique' => 'La CURP ya está registrada',
            'CURP.max' => 'La CURP debe tener máximo 18 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El formato del email no es válido',
            'email.unique' => 'El email ya está registrado',
            'semestre.min' => 'El semestre debe ser al menos 1',
            'semestre.max' => 'El semestre no puede ser mayor a 12'
        ]);

        try {
            $alumno->update($request->all());

            return redirect()->route('alumnos.index')
                ->with('success', 'Alumno actualizado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el alumno: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el recurso especificado.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        verificarAutenticacion();
        
        try {
            $alumno = Alumno::find($id);
            
            if (!$alumno) {
                return redirect()->route('alumnos.index')
                    ->with('error', 'Alumno no encontrado.');
            }

            $alumno->delete();

            return redirect()->route('alumnos.index')
                ->with('success', 'Alumno eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Error al eliminar el alumno: ' . $e->getMessage());
        }
    }
}