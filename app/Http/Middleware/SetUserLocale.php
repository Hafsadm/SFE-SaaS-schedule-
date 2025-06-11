<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Langues supportées
        $supportedLocales = ['fr', 'en', 'es', 'de'];
        
        $locale = 'fr'; // Langue par défaut
        
        // 1. Priorité à la langue de l'utilisateur connecté
        if (Auth::check()) {
            $userLocale = Auth::user()->locale;
            if ($userLocale && in_array($userLocale, $supportedLocales)) {
                $locale = $userLocale;
            }
        }
        
        // 2. Sinon, vérifier la session
        elseif (Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            if (in_array($sessionLocale, $supportedLocales)) {
                $locale = $sessionLocale;
            }
        }
        
        // 3. Sinon, détecter depuis le navigateur
        else {
            $browserLocale = $request->getPreferredLanguage($supportedLocales);
            if ($browserLocale) {
                $locale = $browserLocale;
            }
        }
        
        // Appliquer la langue
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}
