<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DomainDetection
{
    public function handle(Request $request, Closure $next)
    {
        // Récupérer le domaine actuel
        $currentDomain = $request->getHost();
        
        // Nettoyer le domaine (enlever www. si présent)
        $cleanDomain = preg_replace('/^www\./', '', $currentDomain);
        
        // Ignorer pour localhost et les domaines de développement
        if (in_array($cleanDomain, ['localhost', '127.0.0.1']) || 
            str_ends_with($cleanDomain, '.test') || 
            str_ends_with($cleanDomain, '.local')) {
            return $next($request);
        }
        
        // Chercher l'admin correspondant à ce domaine (avec cache)
        $adminUser = Cache::remember("admin_domain_{$cleanDomain}", 3600, function () use ($cleanDomain) {
            return User::where('website_url', $cleanDomain)
                      ->where('role', 'admin')
                      ->where('is_active', true)
                      ->first();
        });
        
        // Si un admin est trouvé, stocker son ID et son slug en session
        if ($adminUser) {
            Session::put('current_admin_id', $adminUser->id);
            Session::put('current_admin_slug', $adminUser->admin_slug);
            Session::put('current_domain', $cleanDomain);
            
            // Log pour débogage
            Log::info("Domaine détecté: {$cleanDomain}, Admin: {$adminUser->name} (ID: {$adminUser->id})");
        } else {
            Log::warning("Aucun admin trouvé pour le domaine: {$cleanDomain}");
        }
        
        return $next($request);
    }
}
