<?php

namespace App\Http\Controllers;

use App\Models\Carrusel;
use Illuminate\Http\Request;

class alumnos_userController extends Controller
{
    public function index(Request $request)
    {
        $carrusels = Carrusel::all();
        return view('alumnos_user.index', compact('carrusels'));
    }
}