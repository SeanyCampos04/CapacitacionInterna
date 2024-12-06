<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        if (Auth::id()) {

            return view('profile.edit', [
                'user' => $request->user(),
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Actualizar el email del usuario
        $user->fill($request->only('email'));
        $user->save();

        // Actualizar datos generales
        $datos_generales = $user->datos_generales;
        $datos_generales->nombre = $request->input('nombre');
        $datos_generales->apellido_paterno = $request->input('apellidoP');
        $datos_generales->apellido_materno = $request->input('apellidoM');
        $datos_generales->fecha_nacimiento = $request->input('fecha_nacimiento');
        $datos_generales->curp = $request->input('curp');
        $datos_generales->rfc = $request->input('rfc');
        $datos_generales->telefono = $request->input('telefono');
        $datos_generales->save();

        // Manejar el archivo PDF del CVU
        if ($request->hasFile('cvu') && $request->file('cvu')->isValid()) {
            // Si el usuario ya tiene un CVU almacenado, eliminarlo primero
            $instructor = $user->instructor;
            if ($instructor->cvu && file_exists(public_path('uploads/'.$instructor->cvu))) {
                // Eliminar el archivo anterior
                unlink(public_path('uploads/'.$instructor->cvu));
            }

            $path = $request->file('cvu')->store('/archivos/cvu', 'custom_public'); // Guardar en storage/app/public/cvus

            // Verificar si el usuario tiene un registro en la tabla instructores
            if ($instructor) {
                $instructor->cvu = $path;
                $instructor->save();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
