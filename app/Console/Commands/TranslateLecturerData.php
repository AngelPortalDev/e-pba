<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TranslateLecturerData extends Command
{
    protected $signature = 'translate:lecturer';
    protected $description = 'Auto translate lecturer fields to multiple languages';

    public function handle()
    {
        $languages = ['fr', 'es', 'zh']; // French, Spanish, Chinese
        $lecturers = DB::table('lecturers_master')
            ->where('is_deleted', 'No')
            ->where('status', '0')
            ->get();

        foreach ($lecturers as $lecturer) {
            foreach ($languages as $lang) {
                // lactrure_name: Only for Chinese
                if ($lang == 'zh') {
                    $this->translateField($lecturer->id, 'lactrure_name', $lecturer->lactrure_name, $lang);
                }

                // discription and designation: All languages
                $this->translateField($lecturer->id, 'discription', $lecturer->discription, $lang);
                $this->translateField($lecturer->id, 'designation', $lecturer->designation, $lang);
            }
        }

        $this->info('Lecturer data translated successfully!');
    }

    private function translateField($lectureId, $fieldName, $originalValue, $locale)
    {
        if (empty($originalValue)) {
            return; // Skip empty
        }

        // Check if translation already exists
        $existing = DB::table('lecture_translations')
            ->where('lecture_id', $lectureId)
            ->where('field_name', $fieldName)
            ->where('lang_code', $locale)
            ->first();

        if ($existing) {
            return; // Already translated
        }

        // Translate
        $translatedText = translateViaDeepL($originalValue, $locale);

        DB::table('lecture_translations')->insert([
            'lecture_id' => $lectureId,
            'lang_code' => $locale,
            'field_name' => $fieldName,
            'translated_value' => $translatedText,
            'created_at' => now(),
        ]);
    }
}
