<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AceptacionTerminos;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        Log::info('=== VALIDANDO REGISTRO ===');
        Log::info('Datos recibidos:', $data);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'acepto_terminos' => ['required', 'accepted'],
        ], [
            'acepto_terminos.required' => 'Debes aceptar los términos y condiciones.',
            'acepto_terminos.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);
    }

    protected function create(array $data)
    {
        try {
            Log::info('=== CREANDO USUARIO ===');
            
            // Crear el usuario
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Log::info('Usuario creado exitosamente - ID: ' . $user->id);

            // Si es el primer usuario registrado, asignarle el rol de admin
            if (User::count() === 1) {
                Log::info('Es el primer usuario, asignando rol admin');
                $user->assignRole('admin');
            }

            // Registrar aceptación de términos y condiciones
            if (isset($data['acepto_terminos']) && $data['acepto_terminos'] === 'on') {
                try {
                    AceptacionTerminos::create([
                        'user_id' => $user->id,
                        'version_terminos' => '1.0',
                        'ip_address' => request()->ip(),
                        'aceptado_en' => now(),
                    ]);
                    Log::info('Aceptación de términos registrada para usuario ID: ' . $user->id);
                } catch (\Exception $e) {
                    Log::error('Error al registrar aceptación de términos: ' . $e->getMessage());
                }
            } else {
                Log::warning('Usuario no aceptó términos y condiciones - ID: ' . $user->id);
            }

            return $user;

        } catch (\Exception $e) {
            Log::error('ERROR AL CREAR USUARIO: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ' - Línea: ' . $e->getLine());
            
            // Lanzar la excepción para que Laravel la maneje
            throw $e;
        }
    }

    // Sobrescribir el método register para mejor manejo de errores
    public function register(Request $request)
    {
        try {
            Log::info('=== INICIANDO REGISTRO ===');
            Log::info('IP: ' . $request->ip());
            Log::info('User-Agent: ' . $request->userAgent());

            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                Log::warning('Validación fallida:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Log::info('Validación exitosa, creando usuario...');

            $user = $this->create($request->all());

            Log::info('Usuario creado, iniciando sesión...');

            $this->guard()->login($user);

            Log::info('Registro completado exitosamente para: ' . $user->email);

            return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());

        } catch (\Exception $e) {
            Log::error('ERROR EN PROCESO DE REGISTRO: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Método para mostrar errores personalizados
    protected function registered(Request $request, $user)
    {
        Log::info('Usuario registrado y autenticado: ' . $user->email);
        return redirect($this->redirectPath())
            ->with('success', '¡Registro exitoso! Bienvenido ' . $user->name);
    }
}