<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Changer la langue de l'interface
     */
    public function changeLanguage(Request $request, $locale)
    {
        $supportedLocales = ['fr', 'en', 'es', 'de'];
        
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Langue non supportée.');
        }
        
        // Mettre à jour la session
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Si l'utilisateur est connecté, mettre à jour sa préférence
        if (Auth::check()) {
            // Auth::user()->update(['locale' => $locale]);
        }
        
        return redirect()->back()->with('success', __('messages.language_updated'));
    }
}
