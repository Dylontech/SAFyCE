<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrusel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carrusels = Carrusel::all();
        
        // Solo maestros ven el dashboard de control escolar
        if (auth()->user()->hasRole('maestro')) {
            return view('dashboard-control-escolar', compact('carrusels'));
        }
        
        // Todos los demás usuarios van al home normal (admin, teste, control_escolar, servicio_financiero, etc.)
        return view('home', compact('carrusels'));
    }
}
