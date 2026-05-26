<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function form()
    {
        return view('public.contact');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'nama'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100'],
            'subject' => ['nullable', 'string', 'max:150'],
            'pesan'   => ['required', 'string'],
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Pesan Anda berhasil dikirim. Seluruh isi pesan ini bersifat contoh dan dapat diganti sewaktu-waktu.');
    }
}

