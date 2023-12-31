<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// Aqui iva otra linea que al parecer no es necesaria en mi aplicacion, pero en el video si estaba.

class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
       // dd($request);
       //dd($request->get('username'));

       //Modificar el request
       $request->request->add(['username' => Str::slug( $request->username )]);

       // Validacion
       $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
       ]);


       User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
       ]);

       // Autenticar un usuario
       auth()->attempt([
        'email' => $request->email,
        'password' => $request->password
       ]);

       //Otra forma de autenticar
       auth()->attempt($request->only('email', 'password'));


       // Redireccionar al usuario
       return redirect()->route('posts.index');

    }
    
}
