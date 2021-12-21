<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        try{
            $request->authenticate();

            $request->session()->regenerate();

            // return redirect()->intended(RouteServiceProvider::HOME);

            return [
                'status' => 'success',
                'resume' => 'Login efetuado com sucesso!',
                'user' => Auth::user(),
                'code' => 200
            ];

        }catch(\Throwable $e){
            return [
                'status' => 'error',
                'resume' => 'Erro ao efetuar login, verifique o usuário e a senha...',
                'error_resume' => $e,
                'code' => 400
            ];
        } 
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        try{    
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return [
                'status' => 'success',
                'resume' => 'Logout efetuado com sucesso!',
                'code' => 200
            ];
        }catch(\Throwable $e){
            return [
                'status' => 'error',
                'resume' => 'Erro ao efetuar logout!',
                'code' => 400
            ];
        }
    }
}
