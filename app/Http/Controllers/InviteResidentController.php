<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InviteResidentController extends Controller
{
    /**
     * Muestra el formulario de invitación para una persona.
     */
    public function create(Person $person)
    {
        $existingUser = User::where('person_id', $person->id)->first();

        return Inertia::render('Residents/Invite', [
            'person'       => $person,
            'existingUser' => $existingUser ? [
                'email'     => $existingUser->email,
                'is_active' => $existingUser->is_active,
            ] : null,
        ]);
    }

    /**
     * Crea el usuario residente y le envía el email de bienvenida con link para
     * establecer contraseña (reutiliza el flujo de password reset de Laravel).
     */
    public function store(Request $request, Person $person)
    {
        $request->validate([
            'email' => 'required|email|max:150|unique:users,email',
        ]);

        // Determine which property this person belongs to (via any of their units)
        $propertyId = $person->units()->first()?->tower?->property_id;

        $user = User::create([
            'person_id'   => $person->id,
            'property_id' => $propertyId,
            'name'        => $person->first_name . ' ' . $person->last_name,
            'email'       => $request->email,
            'password'    => bcrypt(Str::random(32)), // random, will be set via reset link
            'is_active'   => true,
        ]);

        $user->assignRole('tenant');

        // Send password-setup email using Laravel's built-in password reset
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            $user->delete();
            return back()->with('error', 'No se pudo enviar el correo de invitación. Intenta de nuevo.');
        }

        return redirect()
            ->route('persons.show', $person->uuid)
            ->with('success', "Invitación enviada a {$user->email}. El residente recibirá un enlace para crear su contraseña.");
    }
}
