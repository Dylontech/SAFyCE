<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsappSettingsController extends Controller
{
    public function edit()
    {
        // Obtiene la configuración de WhatsApp desde la base de datos
        $settings = DB::table('whatsapp_settings')->first();
        // Asegura que se inicialicen con valores null si no se encuentra la configuración
        $settings = $settings ?: (object) ['phone_number' => null, 'message' => null];
        
        // Devuelve la vista con la configuración actual
        return view('admin.whatsapp_settings', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        // Validación de los campos, permitiendo que sean nulos
        $request->validate([
            'phoneNumber' => 'nullable|max:15',
            'message' => 'nullable',
        ]);

        try {
            $data = [
                'phone_number' => $request->input('phoneNumber'), // Puede ser null
                'message' => $request->input('message'), // Puede ser null
            ];

            // Actualiza o inserta la configuración en la base de datos
            DB::table('whatsapp_settings')
                ->updateOrInsert(
                    ['id' => 1], // Clave primaria o condición de búsqueda
                    $data
                );

            return redirect()->route('edit.whatsapp.settings')->with('success', 'Configuración actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('edit.whatsapp.settings')->with('error', 'Ocurrió un error al actualizar la configuración');
        }
    }
}
