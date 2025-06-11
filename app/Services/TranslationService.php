<?php

namespace App\Services;

// use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Translation;
use App\Models\Language;

class TranslationService
{
    protected $translateClient;
    protected $supportedLanguages;

    public function __construct()
    {
        // $this->translateClient = new TranslateClient([
        //     'key' => config('AIzaSyBwfl4AtqYWuLzNYAFNoASVG3qBYlLE7_A')
        // ]);
        
        $this->supportedLanguages = [
            'fr' => 'Français',
            'en' => 'English',
            'es' => 'Español',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'ja' => '日本語',
            'ko' => '한국어',
            'zh' => '中文',
            'ar' => 'العربية'
        ];
    }

    /**
     * Traduire un texte vers une langue cible
     */
    public function translateText(string $text, string $targetLanguage, string $sourceLanguage = null): ?string
    {
        try {
            $cacheKey = "translation:" . md5($text . $targetLanguage . $sourceLanguage);
            
            return Cache::remember($cacheKey, 3600, function () use ($text, $targetLanguage, $sourceLanguage) {
                $options = ['target' => $targetLanguage];
                
                if ($sourceLanguage) {
                    $options['source'] = $sourceLanguage;
                }

                $result = $this->translateClient->translate($text, $options);
                
                return $result['text'] ?? null;
            });
        } catch (\Exception $e) {
            Log::error('Erreur de traduction: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Traduire plusieurs textes en une fois
     */
    public function translateBatch(array $texts, string $targetLanguage, string $sourceLanguage = null): array
    {
        try {
            $options = ['target' => $targetLanguage];
            
            if ($sourceLanguage) {
                $options['source'] = $sourceLanguage;
            }

            $results = $this->translateClient->translateBatch($texts, $options);
            
            $translations = [];
            foreach ($results as $index => $result) {
                $translations[$texts[$index]] = $result['text'];
            }
            
            return $translations;
        } catch (\Exception $e) {
            Log::error('Erreur de traduction batch: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Détecter la langue d'un texte
     */
    public function detectLanguage(string $text): ?string
    {
        try {
            $result = $this->translateClient->detectLanguage($text);
            return $result['languageCode'] ?? null;
        } catch (\Exception $e) {
            Log::error('Erreur de détection de langue: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir les langues supportées
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }

    /**
     * Traduire les fichiers de langue Laravel
     */
    public function translateLanguageFiles(string $sourceLanguage, string $targetLanguage): bool
    {
        try {
            $sourcePath = resource_path("lang/{$sourceLanguage}");
            $targetPath = resource_path("lang/{$targetLanguage}");

            if (!is_dir($sourcePath)) {
                throw new \Exception("Le dossier de langue source {$sourceLanguage} n'existe pas");
            }

            // Créer le dossier cible s'il n'existe pas
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }

            $files = glob($sourcePath . '/*.php');
            
            foreach ($files as $file) {
                $filename = basename($file);
                $sourceTranslations = include $file;
                
                $translatedContent = $this->translateArray($sourceTranslations, $targetLanguage, $sourceLanguage);
                
                $targetFile = $targetPath . '/' . $filename;
                $this->writePhpArray($targetFile, $translatedContent);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la traduction des fichiers de langue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Traduire récursivement un tableau
     */
    private function translateArray(array $array, string $targetLanguage, string $sourceLanguage): array
    {
        $translated = [];
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $translated[$key] = $this->translateArray($value, $targetLanguage, $sourceLanguage);
            } elseif (is_string($value)) {
                $translated[$key] = $this->translateText($value, $targetLanguage, $sourceLanguage) ?? $value;
            } else {
                $translated[$key] = $value;
            }
        }
        
        return $translated;
    }

    /**
     * Écrire un tableau PHP dans un fichier
     */
    private function writePhpArray(string $filepath, array $array): void
    {
        $content = "<?php\n\nreturn " . var_export($array, true) . ";\n";
        file_put_contents($filepath, $content);
    }

    /**
     * Sauvegarder une traduction en base
     */
    public function saveTranslation(string $key, string $value, string $language, int $userId): Translation
    {
        return Translation::updateOrCreate(
            [
                'key' => $key,
                'language' => $language,
                'user_id' => $userId
            ],
            [
                'value' => $value,
                'updated_at' => now()
            ]
        );
    }

    /**
     * Obtenir une traduction depuis la base
     */
    public function getTranslation(string $key, string $language, int $userId): ?string
    {
        $translation = Translation::where('key', $key)
            ->where('language', $language)
            ->where('user_id', $userId)
            ->first();

        return $translation ? $translation->value : null;
    }
}
