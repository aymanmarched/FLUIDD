<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEntretienSmsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->route('clientId') ?? $request->query('client_id');
        $reference = $request->input('reference', $request->query('reference'));

        if (!$clientId || !$reference) {
            abort(403, 'Accès non autorisé.');
        }

        $key = "entretien.sms_verified.{$clientId}.{$reference}";

        if (!$request->session()->get($key)) {
            return redirect()->route('service.entretien.entretenir.step4', [
                'client_id' => $clientId,
                'reference' => $reference,
            ])->withErrors([
                'verification_code' => 'Veuillez vérifier le code SMS avant de continuer.'
            ]);
        }

        return $next($request);
    }
}