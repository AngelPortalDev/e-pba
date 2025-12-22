<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TranslateTerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:terms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate the English terms.php file into other languages using DeepL API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = lang_path('en/privacy.php');

        if (!file_exists($filePath)) {
            $this->error("The file {$filePath} does not exist.");
            return Command::FAILURE;
        }

        $englishTerms = include $filePath;

        if (!is_array($englishTerms)) {
            $this->error("The file {$filePath} does not return a valid array.");
            return Command::FAILURE;
        }

        $languages = ['ar'];

        foreach ($languages as $lang) {
            $this->info("Translating to $lang...");
            $translated = $this->translateArrayRecursive($englishTerms, $lang);

            $outputPath = lang_path("{$lang}/privacy.php");
            $content = "<?php\n\nreturn " . var_export($translated, true) . ";\n";
            file_put_contents($outputPath, $content);

            $this->info("Translated file created: resources/lang/{$lang}/privacy.php");
        }

        return Command::SUCCESS;
    }

    protected function translateArrayRecursive(array $data, string $lang): array
    {
        $translated = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $translated[$key] = $this->translateArrayRecursive($value, $lang);
            } else {
                $translated[$key] = $this->translateWithDeepL($value, $lang);
                sleep(1); // respect API rate limit
            }
        }

        return $translated;
    }

    protected function translateWithDeepL(string $text, string $targetLang): string
    {
        $apiKey = env('DEEPL_API_KEY'); // Store your DeepL API key in .env

        $response = Http::asForm()->post('https://api-free.deepl.com/v2/translate', [
            'auth_key' => $apiKey,
            'text' => $text,
            'target_lang' => strtoupper($targetLang),
            'tag_handling' => 'html', // respects <a>, <br>, etc.
        ]);

        if ($response->successful() && isset($response['translations'][0]['text'])) {
            return $response['translations'][0]['text'];
        }

        $this->error("Translation failed for: $text");
        return $text; // fallback
    }
}
