<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioE;
use App\Models\Alumno;
use App\Models\Especialidade;

class ControlUserController extends Controller

{

    public function index(Request $request)
    {
        $query = FormularioE::query();

        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        if ($request->filled('grupo')) {
            $query->where('grupo', $request->grupo);
        }

        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_control', 'like', '%' . $request->search . '%');
            });
        }

        $formularios = $query->paginate(10)->appends($request->except('page'));

        $grupos = FormularioE::select('grupo')->distinct()->pluck('grupo');
        $especialidadesDB = Especialidade::orderBy('nombre')->get();
        $tipo_pagos = FormularioE::select('tipo_pago')->distinct()->pluck('tipo_pago');

        return view('Control_user.show', compact('formularios', 'grupos', 'especialidadesDB', 'tipo_pagos'));
    }
}