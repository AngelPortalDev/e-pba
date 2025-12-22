<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{CourseModule,SectionModel};
use App\Models\Translation;

class TranslateCoursesCommand extends Command
{
    protected $signature = 'translate:all-models';
    protected $description = 'Translate fields in multiple models into French, Spanish, and Chinese';

    protected $modelsToTranslate = [
        'Course' => [CourseModule::class, ['course_title', 'course_subheading', 'overview', 'programme_outcomes', 'entry_requirements', 'assessment']],
        #'Section' => [SectionModel::class, ['section_name']],
       # 'OtherVideo' => [OtherVideo::class, ['video_title', 'video_file_name']],
    ];

    protected $languages = ['zh'];

    public function handle()
    {
        foreach ($this->modelsToTranslate as $modelType => [$modelClass, $fields]) {
            $this->info("🔁 Translating model: $modelType");

            //$records = $modelClass::all();
            $records = ($modelClass === \App\Models\CourseModule::class)? $modelClass::where('status', '!=', 2)->get(): $modelClass::all();

            // dd($records);
            foreach ($records as $record) {
                foreach ($this->languages as $locale) {
                    foreach ($fields as $field) {
                        $text = $record->{$field} ?? null;
                        $this->translateIfNotExists($modelType, $record->id, $field, $text, $locale);
                    }
                }
            }
        }

        $this->info("✅ Translations completed for all models.");
    }

    protected function translateIfNotExists($modelType, $modelId, $field, $text, $locale)
    {
        if (empty($text)) return;

        $exists = Translation::where([
            'model_type' => $modelType,
            'model_id' => $modelId,
            'field' => $field,
            'locale' => $locale,
        ])->exists();

        if ($exists) return;

        $translated = getOrTranslate($modelType, $modelId, $field, $text, $locale);

        if (str_contains($translated, 'Translation unavailable - limit reached')) {
            $this->error("❌ DeepL limit reached, skipping $modelType:$modelId $field [$locale]");
        } else {
            $this->info("✅ Translated $modelType:$modelId $field [$locale]");
        }
    }
}
