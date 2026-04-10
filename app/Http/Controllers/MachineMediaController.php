<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;




use Illuminate\Support\Facades\Storage;

class MachineMediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:20000'
        ]);

        $path = $request->file('file')->store('machine_media', 'public');

        return response()->json(['path' => $path], 201);
    }
}
