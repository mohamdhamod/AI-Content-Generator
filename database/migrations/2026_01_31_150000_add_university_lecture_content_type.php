<?php

use App\Models\ContentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add University Lecture content type
        $typeData = [
            'key' => 'university_lecture',
            'slug' => 'university-lecture',
            'icon' => 'fa-graduation-cap',
            'color' => '#6366F1',
            'credits_cost' => 3,
            'active' => true,
            'sort_order' => 8,
        ];

        $translations = [
            'name' => [
                'en' => 'University Lecture',
                'ar' => 'محاضرة جامعية',
                'de' => 'Universitätsvorlesung',
                'es' => 'Conferencia Universitaria',
                'fr' => 'Cours Universitaire',
            ],
            'description' => [
                'en' => 'Professional academic lectures with slides for medical education',
                'ar' => 'محاضرات أكاديمية احترافية مع شرائح للتعليم الطبي',
                'de' => 'Professionelle akademische Vorlesungen mit Folien für die medizinische Ausbildung',
                'es' => 'Conferencias académicas profesionales con diapositivas para educación médica',
                'fr' => 'Cours académiques professionnels avec diapositives pour l\'éducation médicale',
            ],
            'placeholder' => [
                'en' => 'Example: Introduction to Dental Anatomy, Periodontal Disease Pathophysiology',
                'ar' => 'مثال: مقدمة في تشريح الأسنان، الفيزيولوجيا المرضية لأمراض اللثة',
                'de' => 'Beispiel: Einführung in die Zahnanatomie, Pathophysiologie der Parodontalerkrankung',
                'es' => 'Ejemplo: Introducción a la Anatomía Dental, Fisiopatología de la Enfermedad Periodontal',
                'fr' => 'Exemple: Introduction à l\'anatomie dentaire, Physiopathologie des maladies parodontales',
            ],
        ];

        $contentType = ContentType::updateOrCreate(
            ['key' => $typeData['key']],
            $typeData
        );

        foreach (['en', 'ar', 'de', 'es', 'fr'] as $locale) {
            $contentType->translateOrNew($locale)->name = $translations['name'][$locale] ?? $translations['name']['en'];
            $contentType->translateOrNew($locale)->description = $translations['description'][$locale] ?? $translations['description']['en'];
            $contentType->translateOrNew($locale)->placeholder = $translations['placeholder'][$locale] ?? $translations['placeholder']['en'];
        }

        $contentType->save();

        // Deactivate FAQ content type
        ContentType::where('key', 'website_faq')->update(['active' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ContentType::where('key', 'university_lecture')->delete();
        ContentType::where('key', 'website_faq')->update(['active' => true]);
    }
};
