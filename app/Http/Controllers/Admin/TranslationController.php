<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Page principale de gestion des traductions
     */
    public function index()
    {
        $user = Auth::user();
        $languages = Language::where('user_id', $user->id)->get();
        $supportedLanguages = $this->translationService->getSupportedLanguages();
        
        $stats = [
            'total_languages' => $languages->count(),
            'active_languages' => $languages->where('is_active', true)->count(),
            'total_translations' => Translation::where('user_id', $user->id)->count(),
            'auto_translations' => Translation::where('user_id', $user->id)->where('is_auto_translated', true)->count()
        ];

        return view('admin.settings.translations.index', compact('languages', 'supportedLanguages', 'stats'));
    }

    /**
     * Ajouter une nouvelle langue
     */
    public function addLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|size:2',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $user = Auth::user();
            $supportedLanguages = $this->translationService->getSupportedLanguages();
            
            if (!isset($supportedLanguages[$request->language_code])) {
                return response()->json(['success' => false, 'message' => 'Langue non supportée']);
            }

            $language = Language::updateOrCreate(
                [
                    'code' => $request->language_code,
                    'user_id' => $user->id
                ],
                [
                    'name' => $supportedLanguages[$request->language_code],
                    'native_name' => $supportedLanguages[$request->language_code],
                    'is_active' => $request->boolean('is_active', true)
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Langue ajoutée avec succès',
                'language' => $language
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de langue: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de l\'ajout de la langue']);
        }
    }

    /**
     * Traduire automatiquement vers une langue
     */
    public function autoTranslate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'texts' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $user = Auth::user();
            $translations = $this->translationService->translateBatch(
                $request->texts,
                $request->target_language,
                $request->source_language
            );

            $savedTranslations = [];
            foreach ($translations as $originalText => $translatedText) {
                $key = 'custom.' . md5($originalText);
                $translation = $this->translationService->saveTranslation(
                    $key,
                    $translatedText,
                    $request->target_language,
                    $user->id
                );
                $translation->is_auto_translated = true;
                $translation->save();
                
                $savedTranslations[] = $translation;
            }

            return response()->json([
                'success' => true,
                'message' => count($savedTranslations) . ' traductions générées',
                'translations' => $savedTranslations
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la traduction automatique: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la traduction']);
        }
    }

    /**
     * Détecter la langue d'un texte
     */
    public function detectLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $detectedLanguage = $this->translationService->detectLanguage($request->text);
            $supportedLanguages = $this->translationService->getSupportedLanguages();
            
            return response()->json([
                'success' => true,
                'detected_language' => $detectedLanguage,
                'language_name' => $supportedLanguages[$detectedLanguage] ?? 'Inconnue'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la détection de langue: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la détection']);
        }
    }

    /**
     * Traduire les fichiers de langue Laravel
     */
    public function translateLanguageFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $success = $this->translationService->translateLanguageFiles(
                $request->source_language,
                $request->target_language
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichiers de langue traduits avec succès'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la traduction des fichiers'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la traduction des fichiers: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la traduction']);
        }
    }

    /**
     * Supprimer une langue
     */
    public function removeLanguage(Request $request, $languageCode)
    {
        try {
            $user = Auth::user();
            
            $language = Language::where('code', $languageCode)
                ->where('user_id', $user->id)
                ->first();

            if (!$language) {
                return response()->json(['success' => false, 'message' => 'Langue non trouvée']);
            }

            // Supprimer les traductions associées
            Translation::where('language', $languageCode)
                ->where('user_id', $user->id)
                ->delete();

            $language->delete();

            return response()->json([
                'success' => true,
                'message' => 'Langue supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de langue: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}
