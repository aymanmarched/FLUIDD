<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientConfirmationMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'addresse' => 'required',
            'service_type' => 'required',
            'problem' => 'required',
        ]);

        // Save to DB
        $contact = Contact::create($data);

        // Send email to client
        Mail::to($data['email'])->send(new ClientConfirmationMail($contact));


        return redirect()->route('contactezNous.success');

        
    }

    public function markFixed($id)
{
    $client = Contact::findOrFail($id);
    $client->action = 1;
    $client->date_fix = now();   // ← SET FIX TIME
    $client->save();

    return response()->json(['success' => true, 'date_fix' => $client->date_fix]);
}

public function unfix($id)
{
    $client = Contact::findOrFail($id);
    $client->action = 0;
     $client->date_fix = null; 
    $client->save();

    return response()->json(['success' => true]);
}




}

