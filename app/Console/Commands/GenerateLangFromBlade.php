<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateLangFromBlade extends Command
{
    protected $signature = 'lang:generate-from-blade {file : Path to Blade or HTML file} {--lang=en : Language code} {--output=terms}';
    protected $description = 'Extracts text from a Blade or HTML file and generates a Laravel lang file';

    public function handle()
    {
        $inputFile = resource_path('views/' . $this->argument('file'));
        $langCode = $this->option('lang');
        $outputFile = $this->option('output');

        if (!file_exists($inputFile)) {
            $this->error("File not found: $inputFile");
            return;
        }
        

        $html = file_get_contents($inputFile);

        // Load HTML and suppress warnings
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//p | //h1 | //h2 | //li | //span | //strong');

        $translations = [];
        $index = 1;

        foreach ($nodes as $node) {
            $text = trim($node->textContent);
            if ($text && strlen($text) > 2) {
                $key = "line_$index";
                $translations[$key] = $text;
                $index++;
            }
        }

        // Output file
        $langPath = lang_path("{$langCode}");
        if (!is_dir($langPath)) {
            mkdir($langPath, 0755, true);
        }

        $filePath = "$langPath/{$outputFile}.php";
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        file_put_contents($filePath, $content);

        $this->info("Language file generated at: $filePath");
    }
}
