<?php

namespace App\Services\PowerPoint;

/**
 * AI-Powered Slide Content Generator
 * 
 * Generates structured content optimized for professional presentations
 * Following global platform standards (Gamma, Beautiful.ai, Tome, Canva)
 * 
 * @author Senior Laravel Architect + AI Product Designer + Senior AI Prompt Engineer + Senior Doctor
 */
class SlideContentGenerator
{
    /**
     * Presentation styles/templates
     */
    public const STYLE_EDUCATIONAL = 'educational';      // محاضرات تعليمية
    public const STYLE_CONFERENCE = 'conference';        // مؤتمرات علمية
    public const STYLE_WORKSHOP = 'workshop';            // ورش عمل
    public const STYLE_PATIENT = 'patient';              // تثقيف المرضى
    public const STYLE_CLINICAL = 'clinical';            // عرض سريري
    public const STYLE_RESEARCH = 'research';            // بحث علمي

    /**
     * Get available presentation styles
     */
    public static function getPresentationStyles(): array
    {
        return [
            self::STYLE_EDUCATIONAL => [
                'name' => [
                    'en' => 'Educational Lecture',
                    'de' => 'Bildungsvortrag',
                    'es' => 'Conferencia Educativa',
                    'fr' => 'Conférence Éducative',
                    'ar' => 'محاضرة تعليمية',
                ],
                'description' => [
                    'en' => 'Perfect for university lectures and educational content',
                    'de' => 'Perfekt für Universitätsvorlesungen und Bildungsinhalte',
                    'es' => 'Perfecto para conferencias universitarias y contenido educativo',
                    'fr' => 'Parfait pour les cours universitaires et le contenu éducatif',
                    'ar' => 'مثالي للمحاضرات الجامعية والمحتوى التعليمي',
                ],
                'icon' => '🎓',
                'slides_range' => [10, 25],
                'sections' => ['objectives', 'introduction', 'main_content', 'case_studies', 'summary', 'references'],
            ],
            self::STYLE_CONFERENCE => [
                'name' => [
                    'en' => 'Scientific Conference',
                    'de' => 'Wissenschaftliche Konferenz',
                    'es' => 'Conferencia Científica',
                    'fr' => 'Conférence Scientifique',
                    'ar' => 'مؤتمر علمي',
                ],
                'description' => [
                    'en' => 'Professional format for medical conferences',
                    'de' => 'Professionelles Format für medizinische Konferenzen',
                    'es' => 'Formato profesional para conferencias médicas',
                    'fr' => 'Format professionnel pour les conférences médicales',
                    'ar' => 'تنسيق احترافي للمؤتمرات الطبية',
                ],
                'icon' => '🏛️',
                'slides_range' => [15, 30],
                'sections' => ['objectives', 'background', 'methodology', 'results', 'discussion', 'conclusions', 'references'],
            ],
            self::STYLE_WORKSHOP => [
                'name' => [
                    'en' => 'Interactive Workshop',
                    'de' => 'Interaktiver Workshop',
                    'es' => 'Taller Interactivo',
                    'fr' => 'Atelier Interactif',
                    'ar' => 'ورشة عمل تفاعلية',
                ],
                'description' => [
                    'en' => 'Engaging format for hands-on training sessions',
                    'de' => 'Ansprechendes Format für praktische Schulungen',
                    'es' => 'Formato atractivo para sesiones de capacitación práctica',
                    'fr' => 'Format engageant pour les sessions de formation pratique',
                    'ar' => 'تنسيق تفاعلي لجلسات التدريب العملي',
                ],
                'icon' => '🛠️',
                'slides_range' => [12, 20],
                'sections' => ['objectives', 'overview', 'hands_on', 'practice', 'tips', 'wrap_up'],
            ],
            self::STYLE_PATIENT => [
                'name' => [
                    'en' => 'Patient Education',
                    'de' => 'Patientenaufklärung',
                    'es' => 'Educación del Paciente',
                    'fr' => 'Éducation du Patient',
                    'ar' => 'تثقيف المرضى',
                ],
                'description' => [
                    'en' => 'Simple and clear format for patient understanding',
                    'de' => 'Einfaches und klares Format für das Patientenverständnis',
                    'es' => 'Formato simple y claro para la comprensión del paciente',
                    'fr' => 'Format simple et clair pour la compréhension du patient',
                    'ar' => 'تنسيق بسيط وواضح لفهم المرضى',
                ],
                'icon' => '❤️',
                'slides_range' => [8, 15],
                'sections' => ['what_is', 'symptoms', 'causes', 'treatment', 'prevention', 'when_to_seek_help'],
            ],
            self::STYLE_CLINICAL => [
                'name' => [
                    'en' => 'Clinical Case Presentation',
                    'de' => 'Klinische Fallpräsentation',
                    'es' => 'Presentación de Caso Clínico',
                    'fr' => 'Présentation de Cas Clinique',
                    'ar' => 'عرض حالة سريرية',
                ],
                'description' => [
                    'en' => 'Structured case presentation format',
                    'de' => 'Strukturiertes Fallpräsentationsformat',
                    'es' => 'Formato estructurado de presentación de casos',
                    'fr' => 'Format structuré de présentation de cas',
                    'ar' => 'تنسيق منظم لعرض الحالات السريرية',
                ],
                'icon' => '🏥',
                'slides_range' => [12, 20],
                'sections' => ['case_intro', 'history', 'examination', 'investigations', 'diagnosis', 'management', 'outcome'],
            ],
            self::STYLE_RESEARCH => [
                'name' => [
                    'en' => 'Research Presentation',
                    'de' => 'Forschungspräsentation',
                    'es' => 'Presentación de Investigación',
                    'fr' => 'Présentation de Recherche',
                    'ar' => 'عرض بحثي',
                ],
                'description' => [
                    'en' => 'Academic research presentation format',
                    'de' => 'Akademisches Forschungspräsentationsformat',
                    'es' => 'Formato de presentación de investigación académica',
                    'fr' => 'Format de présentation de recherche académique',
                    'ar' => 'تنسيق العروض البحثية الأكاديمية',
                ],
                'icon' => '🔬',
                'slides_range' => [15, 25],
                'sections' => ['introduction', 'literature_review', 'methodology', 'results', 'analysis', 'conclusions', 'future_work', 'references'],
            ],
        ];
    }

    /**
     * Get detail levels
     */
    public static function getDetailLevels(): array
    {
        return [
            'brief' => [
                'name' => [
                    'en' => 'Brief Overview',
                    'de' => 'Kurzer Überblick',
                    'es' => 'Resumen Breve',
                    'fr' => 'Aperçu Bref',
                    'ar' => 'نظرة عامة موجزة',
                ],
                'description' => [
                    'en' => '5-10 slides with key points only',
                    'de' => '5-10 Folien nur mit Kernpunkten',
                    'es' => '5-10 diapositivas solo con puntos clave',
                    'fr' => '5-10 diapositives avec les points clés uniquement',
                    'ar' => '5-10 شرائح بالنقاط الرئيسية فقط',
                ],
                'bullet_points' => 3,
                'max_slides' => 10,
            ],
            'standard' => [
                'name' => [
                    'en' => 'Standard',
                    'de' => 'Standard',
                    'es' => 'Estándar',
                    'fr' => 'Standard',
                    'ar' => 'قياسي',
                ],
                'description' => [
                    'en' => '10-15 slides with balanced detail',
                    'de' => '10-15 Folien mit ausgewogenen Details',
                    'es' => '10-15 diapositivas con detalle equilibrado',
                    'fr' => '10-15 diapositives avec des détails équilibrés',
                    'ar' => '10-15 شريحة بتفاصيل متوازنة',
                ],
                'bullet_points' => 5,
                'max_slides' => 15,
            ],
            'detailed' => [
                'name' => [
                    'en' => 'Detailed',
                    'de' => 'Detailliert',
                    'es' => 'Detallado',
                    'fr' => 'Détaillé',
                    'ar' => 'تفصيلي',
                ],
                'description' => [
                    'en' => '15-25 slides with comprehensive coverage',
                    'de' => '15-25 Folien mit umfassender Abdeckung',
                    'es' => '15-25 diapositivas con cobertura completa',
                    'fr' => '15-25 diapositives avec une couverture complète',
                    'ar' => '15-25 شريحة بتغطية شاملة',
                ],
                'bullet_points' => 6,
                'max_slides' => 25,
            ],
            'comprehensive' => [
                'name' => [
                    'en' => 'Comprehensive',
                    'de' => 'Umfassend',
                    'es' => 'Completo',
                    'fr' => 'Complet',
                    'ar' => 'شامل',
                ],
                'description' => [
                    'en' => '25+ slides with full depth',
                    'de' => '25+ Folien mit voller Tiefe',
                    'es' => '25+ diapositivas con profundidad completa',
                    'fr' => '25+ diapositives avec une profondeur complète',
                    'ar' => '25+ شريحة بعمق كامل',
                ],
                'bullet_points' => 8,
                'max_slides' => 40,
            ],
        ];
    }

    /**
     * Get slide layouts available
     */
    public static function getSlideLayouts(): array
    {
        return [
            'title' => [
                'name' => [
                    'en' => 'Title Slide',
                    'de' => 'Titelfolie',
                    'es' => 'Diapositiva de Título',
                    'fr' => 'Diapositive de Titre',
                    'ar' => 'شريحة العنوان',
                ],
                'icon' => '📑',
            ],
            'section_header' => [
                'name' => [
                    'en' => 'Section Header',
                    'de' => 'Abschnittsüberschrift',
                    'es' => 'Encabezado de Sección',
                    'fr' => 'En-tête de Section',
                    'ar' => 'رأس القسم',
                ],
                'icon' => '📌',
            ],
            'bullet_points' => [
                'name' => [
                    'en' => 'Bullet Points',
                    'de' => 'Aufzählungspunkte',
                    'es' => 'Viñetas',
                    'fr' => 'Puces',
                    'ar' => 'نقاط نقطية',
                ],
                'icon' => '📋',
            ],
            'two_column' => [
                'name' => [
                    'en' => 'Two Column',
                    'de' => 'Zwei Spalten',
                    'es' => 'Dos Columnas',
                    'fr' => 'Deux Colonnes',
                    'ar' => 'عمودان',
                ],
                'icon' => '📊',
            ],
            'comparison' => [
                'name' => [
                    'en' => 'Comparison',
                    'de' => 'Vergleich',
                    'es' => 'Comparación',
                    'fr' => 'Comparaison',
                    'ar' => 'مقارنة',
                ],
                'icon' => '⚖️',
            ],
            'timeline' => [
                'name' => [
                    'en' => 'Timeline',
                    'de' => 'Zeitleiste',
                    'es' => 'Línea de Tiempo',
                    'fr' => 'Chronologie',
                    'ar' => 'خط زمني',
                ],
                'icon' => '📅',
            ],
            'quote' => [
                'name' => [
                    'en' => 'Quote',
                    'de' => 'Zitat',
                    'es' => 'Cita',
                    'fr' => 'Citation',
                    'ar' => 'اقتباس',
                ],
                'icon' => '💬',
            ],
            'statistics' => [
                'name' => [
                    'en' => 'Statistics',
                    'de' => 'Statistiken',
                    'es' => 'Estadísticas',
                    'fr' => 'Statistiques',
                    'ar' => 'إحصائيات',
                ],
                'icon' => '📈',
            ],
            'image_focus' => [
                'name' => [
                    'en' => 'Image Focus',
                    'de' => 'Bildfokus',
                    'es' => 'Enfoque de Imagen',
                    'fr' => 'Focus Image',
                    'ar' => 'صورة بارزة',
                ],
                'icon' => '🖼️',
            ],
        ];
    }

    /**
     * Build AI prompt for generating presentation-optimized content
     */
    public static function buildPresentationPrompt(
        string $topic,
        string $specialty,
        string $style = self::STYLE_EDUCATIONAL,
        string $detailLevel = 'standard',
        string $language = 'en',
        array $additionalContext = []
    ): string {
        $styleConfig = self::getPresentationStyles()[$style] ?? self::getPresentationStyles()[self::STYLE_EDUCATIONAL];
        $detailConfig = self::getDetailLevels()[$detailLevel] ?? self::getDetailLevels()['standard'];
        
        // Get localized names
        $styleName = $styleConfig['name'][$language] ?? $styleConfig['name']['en'];
        $detailName = $detailConfig['name'][$language] ?? $detailConfig['name']['en'];
        
        if ($language === 'ar') {
            return self::buildArabicPrompt($topic, $specialty, $styleConfig, $detailConfig, $styleName, $detailName, $additionalContext);
        }
        
        return self::buildEnglishPrompt($topic, $specialty, $styleConfig, $detailConfig, $styleName, $detailName, $additionalContext);
        
        return $prompt;
    }

    /**
     * Build English presentation prompt
     */
    protected static function buildEnglishPrompt(
        string $topic,
        string $specialty,
        array $styleConfig,
        array $detailConfig,
        string $styleName,
        string $detailName,
        array $context
    ): string {
        $sections = implode(', ', $styleConfig['sections']);
        $maxSlides = $detailConfig['max_slides'];
        $bulletPoints = $detailConfig['bullet_points'];
        
        return <<<PROMPT
You are an expert medical educator and presentation designer creating a professional {$styleName} presentation.

## PRESENTATION DETAILS
- **Topic**: {$topic}
- **Specialty**: {$specialty}
- **Style**: {$styleName}
- **Target Slides**: {$maxSlides} slides maximum
- **Detail Level**: {$detailName}

## REQUIRED STRUCTURE
Generate content for these sections: {$sections}

## FORMATTING RULES (CRITICAL)
1. Start with "## Learning Objectives" section with {$bulletPoints} measurable objectives
2. Use "## Section Title" for each main section (these become section divider slides)
3. Use "### Subsection" for content slides within sections
4. Use bullet points (- ) for key points (max {$bulletPoints} per slide)
5. Keep each bullet point concise (max 15 words)
6. Include relevant statistics with sources where applicable
7. Add "## Key Takeaways" section at the end with main points
8. Add "## References" section with credible medical sources

## CONTENT GUIDELINES
- Evidence-based medical information only
- Use simple, clear language appropriate for the audience
- Include practical clinical pearls
- Add memorable mnemonics where helpful
- Balance text with conceptual explanations
- Include "Clinical Pearl:" highlights for important points
- Add "Remember:" notes for crucial information

## SLIDE-OPTIMIZED FORMATTING
- Each "###" subsection = 1 slide
- Max {$bulletPoints} bullet points per subsection
- Include speaker notes after each section using [Speaker Note: ...]
- Suggest visuals using [Visual: description]

## MEDICAL ACCURACY
- Use current clinical guidelines
- Reference major medical organizations (WHO, CDC, AHA, etc.)
- Include diagnostic criteria where relevant
- Mention treatment algorithms when applicable

Generate the complete presentation content now:
PROMPT;
    }

    /**
     * Build Arabic presentation prompt
     */
    protected static function buildArabicPrompt(
        string $topic,
        string $specialty,
        array $styleConfig,
        array $detailConfig,
        string $styleName,
        string $detailName,
        array $context
    ): string {
        $maxSlides = $detailConfig['max_slides'];
        $bulletPoints = $detailConfig['bullet_points'];
        
        return <<<PROMPT
أنت خبير في التعليم الطبي وتصميم العروض التقديمية، تقوم بإنشاء عرض تقديمي احترافي من نوع {$styleName}.

## تفاصيل العرض التقديمي
- **الموضوع**: {$topic}
- **التخصص**: {$specialty}
- **النمط**: {$styleName}
- **عدد الشرائح المستهدف**: {$maxSlides} شريحة كحد أقصى
- **مستوى التفصيل**: {$detailName}

## قواعد التنسيق (مهم جداً)
1. ابدأ بقسم "## أهداف التعلم" مع {$bulletPoints} أهداف قابلة للقياس
2. استخدم "## عنوان القسم" لكل قسم رئيسي (تصبح شرائح فاصلة)
3. استخدم "### عنوان فرعي" لشرائح المحتوى داخل الأقسام
4. استخدم النقاط (- ) للنقاط الرئيسية (حد أقصى {$bulletPoints} لكل شريحة)
5. اجعل كل نقطة موجزة (15 كلمة كحد أقصى)
6. أضف إحصائيات مع المصادر
7. أضف قسم "## النقاط الرئيسية" في النهاية
8. أضف قسم "## المراجع" بمصادر طبية موثوقة

## إرشادات المحتوى
- معلومات طبية مبنية على الأدلة فقط
- لغة بسيطة وواضحة
- نصائح سريرية عملية
- اختصارات تذكيرية مفيدة
- توازن بين النص والشرح
- أضف "نصيحة سريرية:" للنقاط المهمة
- أضف "تذكر:" للمعلومات الحاسمة

## تنسيق محسّن للشرائح
- كل "###" قسم فرعي = شريحة واحدة
- حد أقصى {$bulletPoints} نقاط لكل قسم فرعي
- اقترح صور باستخدام [صورة: الوصف]

## الدقة الطبية
- استخدم الإرشادات السريرية الحالية
- أشر للمنظمات الطبية الكبرى (WHO, CDC, AHA)
- اذكر معايير التشخيص
- اذكر خوارزميات العلاج

أنشئ محتوى العرض التقديمي الكامل الآن:
PROMPT;
    }

    /**
     * Get icon suggestions for medical topics
     */
    public static function getMedicalIcons(): array
    {
        return [
            'heart' => ['🫀', '❤️', '💓'],
            'brain' => ['🧠', '🤯'],
            'lungs' => ['🫁', '💨'],
            'bone' => ['🦴', '💪'],
            'eye' => ['👁️', '👀'],
            'tooth' => ['🦷', '😁'],
            'medication' => ['💊', '💉', '🏥'],
            'doctor' => ['👨‍⚕️', '👩‍⚕️', '🩺'],
            'research' => ['🔬', '🧪', '📊'],
            'warning' => ['⚠️', '🚨', '❗'],
            'success' => ['✅', '✔️', '👍'],
            'question' => ['❓', '🤔', '💭'],
            'tip' => ['💡', '⭐', '📌'],
            'time' => ['⏰', '📅', '🕐'],
            'statistics' => ['📈', '📊', '📉'],
        ];
    }

    /**
     * Parse generated content into slide structure
     */
    public static function parseToSlideStructure(string $content): array
    {
        $slides = [];
        $lines = explode("\n", $content);
        
        $currentSection = null;
        $currentSlide = null;
        $slideContent = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Main section (## Header) - Section Divider Slide
            if (preg_match('/^##\s+(.+)$/', $line, $matches)) {
                // Save previous slide
                if ($currentSlide) {
                    $currentSlide['content'] = $slideContent;
                    $slides[] = $currentSlide;
                }
                
                $sectionTitle = trim($matches[1]);
                $currentSection = $sectionTitle;
                
                // Create section divider slide
                $slides[] = [
                    'type' => 'section_divider',
                    'title' => $sectionTitle,
                    'section' => $currentSection,
                ];
                
                $currentSlide = null;
                $slideContent = [];
                continue;
            }
            
            // Subsection (### Header) - Content Slide
            if (preg_match('/^###\s+(.+)$/', $line, $matches)) {
                // Save previous slide
                if ($currentSlide) {
                    $currentSlide['content'] = $slideContent;
                    $slides[] = $currentSlide;
                }
                
                $currentSlide = [
                    'type' => 'content',
                    'title' => trim($matches[1]),
                    'section' => $currentSection,
                    'content' => [],
                ];
                $slideContent = [];
                continue;
            }
            
            // Bullet points and content
            if ($currentSlide || $currentSection) {
                // Extract visuals
                if (preg_match('/\[Visual:\s*(.+)\]/', $line, $matches)) {
                    if ($currentSlide) {
                        $currentSlide['visual_suggestion'] = trim($matches[1]);
                    }
                    continue;
                }
                
                // Extract speaker notes
                if (preg_match('/\[Speaker Note:\s*(.+)\]/', $line, $matches)) {
                    if ($currentSlide) {
                        $currentSlide['speaker_note'] = trim($matches[1]);
                    }
                    continue;
                }
                
                // Regular content
                $cleanLine = preg_replace('/^[-•*]\s*/', '', $line);
                if (!empty($cleanLine)) {
                    $slideContent[] = $cleanLine;
                }
            }
        }
        
        // Save last slide
        if ($currentSlide) {
            $currentSlide['content'] = $slideContent;
            $slides[] = $currentSlide;
        }
        
        return $slides;
    }
}
