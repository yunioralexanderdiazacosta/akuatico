<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\UserContact;

class GuestContactController extends Controller
{
    public function sendProfileContact(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'phone' => 'nullable|max:20',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);
        
        $details = [
            'sub' => '[' . config('basic.site_title') . '] Contacto de ' . $request->name,
            'replyToEmail' => $request->email,
            'replyToName' => $request->name,
            'message' => "Nombre: {$request->name}\nEmail: {$request->email}\nTeléfono: " . ($request->phone ?? 'No proporcionado') . "\n\nMensaje:\n" . $request->message,
        ];

        Mail::to($user->email)->send(new UserContact($details));

        return back()->with('success', __('¡Mensaje enviado con éxito!'));
    }

    public function sendListingContact(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'phone' => 'nullable|max:20',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $listing = Listing::with('get_user')->findOrFail($id);
        $user = $listing->get_user;

        $details = [
            'sub' => '[' . config('basic.site_title') . '] Consulta por: ' . $listing->title,
            'replyToEmail' => $request->email,
            'replyToName' => $request->name,
            'message' => "Propiedad/Listing: {$listing->title}\nNombre: {$request->name}\nEmail: {$request->email}\nTeléfono: " . ($request->phone ?? 'No proporcionado') . "\n\nMensaje:\n" . $request->message,
        ];

        Mail::to($user->email)->send(new UserContact($details));

        return back()->with('success', __('¡Consulta enviada con éxito!'));
    }
}
