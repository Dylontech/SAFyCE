<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Alumno;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        if (User::count() === 0) {
            return redirect()->route('register')->with('warning', 'No hay usuarios en el sistema. Por favor, registra un nuevo usuario.');
        }

        $this->validateLogin($request);

        $userIdentifier = $request->input('user_identifier');
        $password = $request->input('password');
        $remember = $request->input('remember');

        if (isset($userIdentifier) && isset($password)) {
            $loginType = filter_var($userIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'curp';

            if ($loginType == 'email') {
                if (Auth::guard('web')->attempt(['email' => $userIdentifier, 'password' => $password], $remember)) {
                    $this->handleRememberMe($userIdentifier, $remember);
                    return $this->sendLoginResponse($request);
                }
            } else {
                $alumno = Alumno::where('CURP', $userIdentifier)
                                ->where('numero_control', $password)
                                ->first();

                if ($alumno) {
                    Auth::guard('alumno')->login($alumno, $remember);
                    $this->handleRememberMe($userIdentifier, $remember);
                    return redirect()->route('alumnos_user.index');
                }
            }
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function handleRememberMe($identifier, $remember)
    {
        if ($remember) {
            Cookie::queue('user_identifier', $identifier, 120);
            Cookie::queue('remember', 'true', 120);
        } else {
            Cookie::queue(Cookie::forget('user_identifier'));
            Cookie::queue(Cookie::forget('remember'));
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'user_identifier' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function showLoginForm()
    {
        $whatsappSettings = [
            'phone_number' => '1234567890',
            'message' => 'Hola, necesito ayuda con el inicio de sesión.'
        ];
        return view('vendor.tablar.auth.login', compact('whatsappSettings'));
    }

    public function logout(Request $request)
    {
        // DEBUG: Log para verificar que este método se está llamando
        \Log::info('Logout method called', [
            'session_id' => $request->session()->getId(),
            'user_id' => Auth::id(),
            'guard' => Auth::getDefaultDriver()
        ]);

        // 1. Obtener el ID de sesión ANTES de hacer logout
        $sessionId = $request->session()->getId();
        
        // 2. Cerrar sesión del guard activo
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } 
        
        if (Auth::guard('alumno')->check()) {
            Auth::guard('alumno')->logout();
        }

        // 3. Eliminar manualmente el registro de la sesión
        $deleted = DB::table('sessions')->where('id', $sessionId)->delete();
        
        // DEBUG: Log del resultado de la eliminación
        \Log::info('Session deletion result', [
            'session_id' => $sessionId,
            'deleted' => $deleted,
            'remaining_sessions' => DB::table('sessions')->count()
        ]);

        // 4. Invalidar la sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 5. Limpiar cookies
        $this->clearAllCookies();

        // 6. Redirigir
        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
        ]);
    }

    protected function clearAllCookies()
    {
        $cookies = [
            'laravel_session',
            'user_identifier',
            'remember',
            'password',
            'XSRF-TOKEN'
        ];
        
        foreach ($cookies as $cookie) {
            Cookie::queue(Cookie::forget($cookie));
        }
        
        Session::flush();
    }

    // Método para debug
    public function debugSessions()
    {
        $sessions = DB::table('sessions')->get();
        return response()->json([
            'total_sessions' => $sessions->count(),
            'sessions' => $sessions->map(function($session) {
                return [
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'ip_address' => $session->ip_address,
                    'last_activity' => date('Y-m-d H:i:s', $session->last_activity),
                    'is_current' => $session->id === session()->getId()
                ];
            })
        ]);
    }
}