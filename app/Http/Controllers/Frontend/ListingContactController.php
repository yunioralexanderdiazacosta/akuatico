<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ListingContactController extends Controller
{
    public function sendMessage(Request $request, $id)
    {
        $req = $request->except('_token', '_method');
        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email',
            'message' => 'required',
        ];
        $message = [
            'name.required' => __('Please write your name'),
            'email.required' => __('Please write your email'),
            'email.email' => __('Please write a valid email'),
            'message.required' => __('Please Write your message'),
        ];

        $validate = Validator::make($req, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $listing = Listing::with('get_user')->findOrFail($id);
        $user = $listing->get_user;

        $destinatario = $user->email ?? config('mail.from.address');
        $asunto = "Nuevo lead desde Akuatico - " . $listing->title;

        $nombre = strip_tags(trim($request->name));
        $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
        $telefono = strip_tags(trim($request->phone ?? ''));
        $mensaje = strip_tags(trim($request->message));

        $contenido = "Has recibido un nuevo mensaje:\n\n";
        $contenido .= "Nombre: $nombre\n";
        $contenido .= "Email: $email\n";
        if ($telefono) {
            $contenido .= "Teléfono: $telefono\n";
        }
        $contenido .= "Listing: " . $listing->title . "\n";
        $contenido .= "Mensaje:\n$mensaje\n";

        $headers = "From: Web <no-reply@" . request()->getHost() . ">\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8";

        try {
            mail($destinatario, $asunto, $contenido, $headers);
            return back()->with('success', __('Message has been sent successfully'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('Failed to send message. Please try again.'));
        }
    }

    public function sendProfileMessage(Request $request, $id)
    {
        $req = $request->except('_token', '_method');
        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email',
            'message' => 'required',
        ];
        $message = [
            'name.required' => __('Please write your name'),
            'email.required' => __('Please write your email'),
            'email.email' => __('Please write a valid email'),
            'message.required' => __('Please Write your message'),
        ];

        $validate = Validator::make($req, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $user = User::findOrFail($id);

        $destinatario = $user->email ?? config('mail.from.address');
        $asunto = "Nuevo lead desde Akuatico";

        $nombre = strip_tags(trim($request->name));
        $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
        $telefono = strip_tags(trim($request->phone ?? ''));
        $mensaje = strip_tags(trim($request->message));

        $contenido = "Has recibido un nuevo mensaje desde tu perfil:\n\n";
        $contenido .= "Nombre: $nombre\n";
        $contenido .= "Email: $email\n";
        if ($telefono) {
            $contenido .= "Teléfono: $telefono\n";
        }
        $contenido .= "Mensaje:\n$mensaje\n";

        $headers = "From: Web <no-reply@" . request()->getHost() . ">\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8";

        try {
            mail($destinatario, $asunto, $contenido, $headers);
            return back()->with('success', __('Message has been sent successfully'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('Failed to send message. Please try again.'));
        }
    }
}
