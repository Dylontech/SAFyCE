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

    /**
     * Incrementa un semestre a todos los alumnos
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function incrementarSemestre()
    {
        verificarAutenticacion();
        
        try {
            $alumnosActualizados = Alumno::where('semestre', '<', 12)->increment('semestre');
            
            if ($alumnosActualizados > 0) {
                return redirect()->route('alumnos.index')
                    ->with('success', "Se incrementó el semestre de {$alumnosActualizados} alumnos exitosamente.");
            } else {
                return redirect()->route('alumnos.index')
                    ->with('warning', 'No hay alumnos para actualizar o todos están en el semestre máximo (12).');
            }
                
        } catch (\Exception $e) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Error al incrementar semestre: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza grupo o especialidad de alumnos específicamente seleccionados
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizacionSelectiva(Request $request)
    {
        verificarAutenticacion();
        
        $request->validate([
            'tipo_actualizacion_selectiva' => 'required|in:grupo,especialidad',
            'alumnos_seleccionados' => 'required|string',
        ], [
            'tipo_actualizacion_selectiva.required' => 'Debe seleccionar el tipo de actualización',
            'tipo_actualizacion_selectiva.in' => 'Tipo de actualización no válido',
            'alumnos_seleccionados.required' => 'Debe seleccionar al menos un alumno'
        ]);

        // Validar campos específicos según el tipo
        if ($request->tipo_actualizacion_selectiva === 'grupo') {
            // Si seleccionó "crear nuevo grupo", validar el campo personalizado
            if ($request->nuevo_grupo === '_nuevo_') {
                $request->validate([
                    'grupo_personalizado' => 'required|string|max:10'
                ], [
                    'grupo_personalizado.required' => 'Debe ingresar el nombre del nuevo grupo'
                ]);
            } else {
                $request->validate([
                    'nuevo_grupo' => 'required|string|max:10'
                ], [
                    'nuevo_grupo.required' => 'Debe seleccionar el nuevo grupo'
                ]);
            }
        } else {
            $request->validate([
                'nueva_especialidad' => 'required|string'
            ], [
                'nueva_especialidad.required' => 'Debe seleccionar la nueva especialidad'
            ]);
        }

        try {
            // Obtener IDs de alumnos seleccionados
            $alumnosIds = explode(',', $request->alumnos_seleccionados);
            $alumnosIds = array_filter($alumnosIds); // Remover valores vacíos
            
            if (empty($alumnosIds)) {
                return redirect()->route('alumnos.index')
                    ->with('error', 'No se seleccionaron alumnos válidos.');
            }

            // Preparar datos para actualización
            $campo = $request->tipo_actualizacion_selectiva === 'grupo' ? 'Grupo' : 'especialidad';
            
            if ($request->tipo_actualizacion_selectiva === 'grupo') {
                // Si seleccionó crear nuevo grupo, usar el campo personalizado
                $nuevoValor = $request->nuevo_grupo === '_nuevo_' 
                    ? $request->grupo_personalizado 
                    : $request->nuevo_grupo;
            } else {
                $nuevoValor = $request->nueva_especialidad;
            }
            
            // Realizar actualización
            $alumnosActualizados = Alumno::whereIn('id', $alumnosIds)
                ->update([$campo => $nuevoValor]);
            
            if ($alumnosActualizados > 0) {
                $tipoCampo = $request->tipo_actualizacion_selectiva === 'grupo' ? 'grupo' : 'especialidad';
                return redirect()->route('alumnos.index')
                    ->with('success', "Se actualizó el {$tipoCampo} de {$alumnosActualizados} alumnos seleccionados a '{$nuevoValor}' exitosamente.");
            } else {
                return redirect()->route('alumnos.index')
                    ->with('warning', 'No se encontraron alumnos válidos para actualizar.');
            }
                
        } catch (\Exception $e) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Error al realizar la actualización selectiva: ' . $e->getMessage());
        }
    }
}