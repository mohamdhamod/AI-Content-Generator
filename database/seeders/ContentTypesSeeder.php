<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Seeder;

class ContentTypesSeeder extends Seeder
{
    /**
     * Seed the content types.
     */
    public function run(): void
    {
        $contentTypes = [
            [
                'key' => 'patient_education',
                'name' => [
                    'en' => 'Patient Education',
                    'ar' => 'تثقيف المرضى',
                    'de' => 'Patientenaufklärung',
                    'es' => 'Educación del Paciente',
                    'fr' => 'Éducation du Patient',
                ],
                'description' => [
                    'en' => 'Educational content to help patients understand their conditions and procedures',
                    'ar' => 'محتوى تثقيفي لمساعدة المرضى على فهم حالاتهم وإجراءاتهم',
                    'de' => 'Bildungsinhalte, um Patienten zu helfen, ihre Zustände und Verfahren zu verstehen',
                    'es' => 'Contenido educativo para ayudar a los pacientes a entender sus condiciones y procedimientos',
                    'fr' => 'Contenu éducatif pour aider les patients à comprendre leurs conditions et procédures',
                ],
                'placeholder' => [
                    'en' => 'Example: Understanding root canal treatment, What causes tooth sensitivity',
                    'ar' => 'مثال: فهم علاج قناة الجذر، ما الذي يسبب حساسية الأسنان',
                    'de' => 'Beispiel: Wurzelkanalbehandlung verstehen, Was verursacht Zahnempfindlichkeit',
                    'es' => 'Ejemplo: Entender el tratamiento de conducto, Qué causa la sensibilidad dental',
                    'fr' => 'Exemple: Comprendre le traitement de canal, Ce qui cause la sensibilité dentaire',
                ],
                'icon' => 'fa-book-medical',
                'color' => '#4A90D9',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 1,
                'prompt_requirements' => 'Include: introduction, general information, wellness tips, when to see a doctor, disclaimer. Reading level: general public. Write comprehensive and detailed content.',
                'min_word_count' => 800,
                'max_word_count' => 1200,
            ],
            [
                'key' => 'what_to_expect',
                'name' => [
                    'en' => 'What to Expect',
                    'ar' => 'ماذا تتوقع',
                    'de' => 'Was zu erwarten ist',
                    'es' => 'Qué Esperar',
                    'fr' => 'À Quoi S\'Attendre',
                ],
                'description' => [
                    'en' => 'Before and after procedure guides for patients',
                    'ar' => 'دليل ما قبل وبعد الإجراء للمرضى',
                    'de' => 'Leitfäden vor und nach dem Eingriff für Patienten',
                    'es' => 'Guías antes y después del procedimiento para pacientes',
                    'fr' => 'Guides avant et après la procédure pour les patients',
                ],
                'placeholder' => [
                    'en' => 'Example: What to expect before dental implant surgery',
                    'ar' => 'مثال: ماذا تتوقع قبل جراحة زراعة الأسنان',
                    'de' => 'Beispiel: Was vor einer Zahnimplantatoperation zu erwarten ist',
                    'es' => 'Ejemplo: Qué esperar antes de la cirugía de implante dental',
                    'fr' => 'Exemple: À quoi s\'attendre avant la chirurgie d\'implant dentaire',
                ],
                'icon' => 'fa-clipboard-list',
                'color' => '#28A745',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 2,
                'prompt_requirements' => 'Describe: appointment flow, typical duration, preparation tips. Friendly, step-by-step format.',
                'min_word_count' => 600,
                'max_word_count' => 1000,
            ],
            [
                'key' => 'seo_blog_article',
                'name' => [
                    'en' => 'SEO Blog Article',
                    'ar' => 'مقال مدونة SEO',
                    'de' => 'SEO-Blog-Artikel',
                    'es' => 'Artículo de Blog SEO',
                    'fr' => 'Article de Blog SEO',
                ],
                'description' => [
                    'en' => 'Search engine optimized blog articles for your clinic website',
                    'ar' => 'مقالات مدونة محسنة لمحركات البحث لموقع عيادتك',
                    'de' => 'Suchmaschinenoptimierte Blog-Artikel für Ihre Klinik-Website',
                    'es' => 'Artículos de blog optimizados para motores de búsqueda para su sitio web de clínica',
                    'fr' => 'Articles de blog optimisés pour les moteurs de recherche pour votre site de clinique',
                ],
                'placeholder' => [
                    'en' => 'Example: Benefits of regular dental checkups, How to prevent skin aging',
                    'ar' => 'مثال: فوائد فحوصات الأسنان المنتظمة، كيفية منع شيخوخة البشرة',
                    'de' => 'Beispiel: Vorteile regelmäßiger Zahnarztbesuche, Wie man Hautalterung verhindert',
                    'es' => 'Ejemplo: Beneficios de los chequeos dentales regulares, Cómo prevenir el envejecimiento de la piel',
                    'fr' => 'Exemple: Avantages des contrôles dentaires réguliers, Comment prévenir le vieillissement de la peau',
                ],
                'icon' => 'fa-blog',
                'color' => '#FFC107',
                'credits_cost' => 2,
                'active' => true,
                'sort_order' => 3,
                'prompt_requirements' => 'Include: H2/H3 headings, meta description, focus keywords, engaging introduction, informational sections, CTA. Write comprehensive and detailed content.',
                'min_word_count' => 1500,
                'max_word_count' => 2500,
            ],
            [
                'key' => 'social_media_post',
                'name' => [
                    'en' => 'Social Media Post',
                    'ar' => 'منشور وسائل التواصل',
                    'de' => 'Social Media Beitrag',
                    'es' => 'Publicación en Redes Sociales',
                    'fr' => 'Publication sur les Réseaux Sociaux',
                ],
                'description' => [
                    'en' => 'Engaging social media content for Facebook, Instagram, and more',
                    'ar' => 'محتوى جذاب لوسائل التواصل الاجتماعي لفيسبوك وإنستغرام والمزيد',
                    'de' => 'Ansprechende Social-Media-Inhalte für Facebook, Instagram und mehr',
                    'es' => 'Contenido atractivo para redes sociales para Facebook, Instagram y más',
                    'fr' => 'Contenu engageant pour les réseaux sociaux pour Facebook, Instagram et plus',
                ],
                'placeholder' => [
                    'en' => 'Example: Teeth whitening tips, Skincare routine for summer',
                    'ar' => 'مثال: نصائح تبييض الأسنان، روتين العناية بالبشرة للصيف',
                    'de' => 'Beispiel: Tipps zum Zahnaufhellen, Hautpflege-Routine für den Sommer',
                    'es' => 'Ejemplo: Consejos para blanquear los dientes, Rutina de cuidado de la piel para el verano',
                    'fr' => 'Exemple: Conseils pour blanchir les dents, Routine de soins de la peau pour l\'été',
                ],
                'icon' => 'fa-share-alt',
                'color' => '#E91E63',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 4,
                'prompt_requirements' => 'Include: engaging hook, educational tip/fact, relevant hashtags, CTA, emoji suggestions.',
                'min_word_count' => 200,
                'max_word_count' => 400,
            ],
            [
                'key' => 'google_review_reply',
                'name' => [
                    'en' => 'Google Review Reply',
                    'ar' => 'رد على مراجعة جوجل',
                    'de' => 'Google Bewertung Antwort',
                    'es' => 'Respuesta a Reseña de Google',
                    'fr' => 'Réponse aux Avis Google',
                ],
                'description' => [
                    'en' => 'Professional and thoughtful responses to patient reviews',
                    'ar' => 'ردود احترافية ومدروسة على مراجعات المرضى',
                    'de' => 'Professionelle und durchdachte Antworten auf Patientenbewertungen',
                    'es' => 'Respuestas profesionales y consideradas a las reseñas de pacientes',
                    'fr' => 'Réponses professionnelles et réfléchies aux avis des patients',
                ],
                'placeholder' => [
                    'en' => 'Paste the review you want to respond to...',
                    'ar' => 'الصق المراجعة التي تريد الرد عليها...',
                    'de' => 'Fügen Sie die Bewertung ein, auf die Sie antworten möchten...',
                    'es' => 'Pegue la reseña a la que desea responder...',
                    'fr' => 'Collez l\'avis auquel vous souhaitez répondre...',
                ],
                'icon' => 'fa-star',
                'color' => '#FF9800',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 5,
                'prompt_requirements' => 'Include: thank you, acknowledgment of feedback, commitment to care. Maintain patient privacy.',
                'min_word_count' => 100,
                'max_word_count' => 200,
            ],
            [
                'key' => 'email_follow_up',
                'name' => [
                    'en' => 'Email Follow-up',
                    'ar' => 'بريد متابعة',
                    'de' => 'Follow-up E-Mail',
                    'es' => 'Correo de Seguimiento',
                    'fr' => 'E-mail de Suivi',
                ],
                'description' => [
                    'en' => 'Post-appointment follow-up emails for patient engagement',
                    'ar' => 'رسائل بريد إلكتروني للمتابعة بعد الموعد لإشراك المرضى',
                    'de' => 'Follow-up E-Mails nach dem Termin für Patientenbindung',
                    'es' => 'Correos electrónicos de seguimiento posteriores a la cita para la participación del paciente',
                    'fr' => 'E-mails de suivi après rendez-vous pour l\'engagement des patients',
                ],
                'placeholder' => [
                    'en' => 'Example: Follow-up after teeth cleaning, Post-procedure care reminder',
                    'ar' => 'مثال: متابعة بعد تنظيف الأسنان، تذكير بالرعاية بعد الإجراء',
                    'de' => 'Beispiel: Nachsorge nach Zahnreinigung, Erinnerung an Nachbehandlung',
                    'es' => 'Ejemplo: Seguimiento después de la limpieza dental, Recordatorio de cuidados posteriores al procedimiento',
                    'fr' => 'Exemple: Suivi après le nettoyage des dents, Rappel des soins post-procédure',
                ],
                'icon' => 'fa-envelope',
                'color' => '#9C27B0',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 6,
                'prompt_requirements' => 'Include: subject line, warm greeting, care reminder, general tips, appointment CTA, disclaimer.',
                'min_word_count' => 300,
                'max_word_count' => 500,
            ],
            [
                'key' => 'website_faq',
                'name' => [
                    'en' => 'Website FAQ',
                    'ar' => 'أسئلة شائعة للموقع',
                    'de' => 'Website FAQ',
                    'es' => 'Preguntas Frecuentes del Sitio Web',
                    'fr' => 'FAQ du Site Web',
                ],
                'description' => [
                    'en' => 'Frequently asked questions content for your clinic website',
                    'ar' => 'محتوى الأسئلة الشائعة لموقع عيادتك',
                    'de' => 'Häufig gestellte Fragen für Ihre Klinik-Website',
                    'es' => 'Contenido de preguntas frecuentes para su sitio web de clínica',
                    'fr' => 'Contenu de questions fréquemment posées pour votre site de clinique',
                ],
                'placeholder' => [
                    'en' => 'Example: FAQ about dental implants, Questions about laser treatment',
                    'ar' => 'مثال: أسئلة شائعة عن زراعة الأسنان، أسئلة عن العلاج بالليزر',
                    'de' => 'Beispiel: FAQ zu Zahnimplantaten, Fragen zur Laserbehandlung',
                    'es' => 'Ejemplo: Preguntas frecuentes sobre implantes dentales, Preguntas sobre tratamiento láser',
                    'fr' => 'Exemple: FAQ sur les implants dentaires, Questions sur le traitement au laser',
                ],
                'icon' => 'fa-question-circle',
                'color' => '#00BCD4',
                'credits_cost' => 1,
                'active' => true,
                'sort_order' => 7,
                'prompt_requirements' => 'Create 8-12 Q&A pairs. Each answer: 100-200 words. Informative but encourage professional consultation.',
                'min_word_count' => 800,
                'max_word_count' => 2000,
            ],
            [
                'key' => 'university_lecture',
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
                'icon' => 'fa-graduation-cap',
                'color' => '#6366F1',
                'credits_cost' => 3,
                'active' => true,
                'sort_order' => 8,
                'prompt_requirements' => null, // Uses specialized method in MedicalPromptService
                'min_word_count' => 2000,
                'max_word_count' => 3000,
            ],
        ];

        foreach ($contentTypes as $typeData) {
            $translations = [
                'name' => $typeData['name'],
                'description' => $typeData['description'],
                'placeholder' => $typeData['placeholder'],
            ];
            unset($typeData['name'], $typeData['description'], $typeData['placeholder']);

            // Add slug from key (replace underscores with hyphens)
            $typeData['slug'] = str_replace('_', '-', $typeData['key']);

            $contentType = ContentType::updateOrCreate(
                ['key' => $typeData['key']],
                $typeData
            );

            foreach (array_keys(config('languages')) as $locale) {
                $contentType->translateOrNew($locale)->name = $translations['name'][$locale] ?? $translations['name']['en'];
                $contentType->translateOrNew($locale)->description = $translations['description'][$locale] ?? $translations['description']['en'];
                $contentType->translateOrNew($locale)->placeholder = $translations['placeholder'][$locale] ?? $translations['placeholder']['en'];
            }

            $contentType->save();
        }
    }
}
