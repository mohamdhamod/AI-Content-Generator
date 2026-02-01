<?php

namespace App\Services\PowerPoint;

/**
 * PowerPoint Theme Configuration
 * 
 * Professional themes designed by Senior AI Product Designer
 * Following global platform standards (Canva, Beautiful.ai, Gamma)
 */
class ThemeConfig
{
    /**
     * Available themes with professional color schemes
     */
    public static function getThemes(): array
    {
        return [
            'professional_blue' => [
                'name' => [
                    'en' => 'Professional Blue',
                    'de' => 'Professionelles Blau',
                    'es' => 'Azul Profesional',
                    'fr' => 'Bleu Professionnel',
                    'ar' => 'الأزرق الاحترافي',
                ],
                'description' => [
                    'en' => 'Clean corporate style perfect for business presentations',
                    'de' => 'Sauberer Unternehmensstil, perfekt für Geschäftspräsentationen',
                    'es' => 'Estilo corporativo limpio perfecto para presentaciones de negocios',
                    'fr' => 'Style corporate épuré parfait pour les présentations professionnelles',
                    'ar' => 'تصميم مؤسسي نظيف مثالي للعروض التقديمية',
                ],
                'preview' => 'professional-blue.png',
                'colors' => [
                    'primary' => '2563EB',      // Blue 600
                    'secondary' => '3B82F6',    // Blue 500
                    'accent' => '60A5FA',       // Blue 400
                    'background' => 'F8FAFC',   // Slate 50
                    'surface' => 'FFFFFF',      // White
                    'text' => '1E293B',         // Slate 800
                    'text_light' => '64748B',   // Slate 500
                    'gradient_start' => '1D4ED8', // Blue 700
                    'gradient_end' => '3B82F6',   // Blue 500
                ],
                'fonts' => [
                    'title' => 'Arial',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'corporate',
            ],
            
            'medical_green' => [
                'name' => [
                    'en' => 'Medical Green',
                    'de' => 'Medizinisches Grün',
                    'es' => 'Verde Médico',
                    'fr' => 'Vert Médical',
                    'ar' => 'الأخضر الطبي',
                ],
                'description' => [
                    'en' => 'Healthcare-focused design with calming green tones',
                    'de' => 'Auf das Gesundheitswesen ausgerichtetes Design mit beruhigenden Grüntönen',
                    'es' => 'Diseño enfocado en salud con tonos verdes calmantes',
                    'fr' => 'Design axé sur la santé avec des tons verts apaisants',
                    'ar' => 'تصميم طبي مع درجات الأخضر المهدئة',
                ],
                'preview' => 'medical-green.png',
                'colors' => [
                    'primary' => '059669',      // Emerald 600
                    'secondary' => '10B981',    // Emerald 500
                    'accent' => '34D399',       // Emerald 400
                    'background' => 'F0FDF4',   // Green 50
                    'surface' => 'FFFFFF',
                    'text' => '14532D',         // Green 900
                    'text_light' => '4ADE80',   // Green 400
                    'gradient_start' => '047857', // Emerald 700
                    'gradient_end' => '10B981',   // Emerald 500
                ],
                'fonts' => [
                    'title' => 'Arial',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'medical',
            ],
            
            'academic_purple' => [
                'name' => [
                    'en' => 'Academic Purple',
                    'de' => 'Akademisches Lila',
                    'es' => 'Púrpura Académico',
                    'fr' => 'Violet Académique',
                    'ar' => 'البنفسجي الأكاديمي',
                ],
                'description' => [
                    'en' => 'Elegant academic style for educational content',
                    'de' => 'Eleganter akademischer Stil für Bildungsinhalte',
                    'es' => 'Estilo académico elegante para contenido educativo',
                    'fr' => 'Style académique élégant pour le contenu éducatif',
                    'ar' => 'تصميم أكاديمي أنيق للمحتوى التعليمي',
                ],
                'preview' => 'academic-purple.png',
                'colors' => [
                    'primary' => '7C3AED',      // Violet 600
                    'secondary' => '8B5CF6',    // Violet 500
                    'accent' => 'A78BFA',       // Violet 400
                    'background' => 'FAF5FF',   // Purple 50
                    'surface' => 'FFFFFF',
                    'text' => '3B0764',         // Purple 950
                    'text_light' => '7C3AED',   // Violet 600
                    'gradient_start' => '6D28D9', // Violet 700
                    'gradient_end' => '8B5CF6',   // Violet 500
                ],
                'fonts' => [
                    'title' => 'Georgia',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'academic',
            ],
            
            'modern_dark' => [
                'name' => [
                    'en' => 'Modern Dark',
                    'de' => 'Modernes Dunkel',
                    'es' => 'Oscuro Moderno',
                    'fr' => 'Sombre Moderne',
                    'ar' => 'الداكن العصري',
                ],
                'description' => [
                    'en' => 'Sleek dark theme for impactful presentations',
                    'de' => 'Elegantes dunkles Thema für wirkungsvolle Präsentationen',
                    'es' => 'Tema oscuro elegante para presentaciones impactantes',
                    'fr' => 'Thème sombre élégant pour des présentations percutantes',
                    'ar' => 'ثيم داكن أنيق للعروض المؤثرة',
                ],
                'preview' => 'modern-dark.png',
                'colors' => [
                    'primary' => 'F59E0B',      // Amber 500
                    'secondary' => 'FBBF24',    // Amber 400
                    'accent' => 'FCD34D',       // Amber 300
                    'background' => '18181B',   // Zinc 900
                    'surface' => '27272A',      // Zinc 800
                    'text' => 'FAFAFA',         // Zinc 50
                    'text_light' => 'A1A1AA',   // Zinc 400
                    'gradient_start' => '27272A', // Zinc 800
                    'gradient_end' => '3F3F46',   // Zinc 700
                ],
                'fonts' => [
                    'title' => 'Arial Black',
                    'body' => 'Arial',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'modern',
            ],
            
            'clean_minimal' => [
                'name' => [
                    'en' => 'Clean Minimal',
                    'de' => 'Sauber Minimal',
                    'es' => 'Mínimo Limpio',
                    'fr' => 'Minimaliste Épuré',
                    'ar' => 'النظيف البسيط',
                ],
                'description' => [
                    'en' => 'Minimalist white design for clarity',
                    'de' => 'Minimalistisches weißes Design für Klarheit',
                    'es' => 'Diseño blanco minimalista para claridad',
                    'fr' => 'Design blanc minimaliste pour la clarté',
                    'ar' => 'تصميم أبيض بسيط للوضوح',
                ],
                'preview' => 'clean-minimal.png',
                'colors' => [
                    'primary' => '18181B',      // Zinc 900
                    'secondary' => '3F3F46',    // Zinc 700
                    'accent' => 'EF4444',       // Red 500
                    'background' => 'FFFFFF',   // White
                    'surface' => 'FAFAFA',      // Zinc 50
                    'text' => '18181B',         // Zinc 900
                    'text_light' => '71717A',   // Zinc 500
                    'gradient_start' => 'FAFAFA', // Zinc 50
                    'gradient_end' => 'F4F4F5',   // Zinc 100
                ],
                'fonts' => [
                    'title' => 'Helvetica',
                    'body' => 'Helvetica',
                    'title_size' => 48,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'minimal',
            ],
            
            'healthcare_teal' => [
                'name' => [
                    'en' => 'Healthcare Teal',
                    'de' => 'Gesundheitswesen Türkis',
                    'es' => 'Turquesa de Salud',
                    'fr' => 'Turquoise Santé',
                    'ar' => 'التركواز الصحي',
                ],
                'description' => [
                    'en' => 'Modern healthcare design with teal accents',
                    'de' => 'Modernes Gesundheitsdesign mit türkisen Akzenten',
                    'es' => 'Diseño moderno de salud con acentos turquesa',
                    'fr' => 'Design santé moderne avec des accents turquoise',
                    'ar' => 'تصميم رعاية صحية عصري بلمسات تركوازية',
                ],
                'preview' => 'healthcare-teal.png',
                'colors' => [
                    'primary' => '0D9488',      // Teal 600
                    'secondary' => '14B8A6',    // Teal 500
                    'accent' => '2DD4BF',       // Teal 400
                    'background' => 'F0FDFA',   // Teal 50
                    'surface' => 'FFFFFF',
                    'text' => '134E4A',         // Teal 900
                    'text_light' => '5EEAD4',   // Teal 300
                    'gradient_start' => '0F766E', // Teal 700
                    'gradient_end' => '14B8A6',   // Teal 500
                ],
                'fonts' => [
                    'title' => 'Arial',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'healthcare',
            ],
            
            'gradient_sunset' => [
                'name' => [
                    'en' => 'Gradient Sunset',
                    'de' => 'Farbverlauf Sonnenuntergang',
                    'es' => 'Gradiente Atardecer',
                    'fr' => 'Dégradé Coucher de Soleil',
                    'ar' => 'غروب متدرج',
                ],
                'description' => [
                    'en' => 'Vibrant gradient theme with warm colors',
                    'de' => 'Lebhaftes Farbverlauf-Thema mit warmen Farben',
                    'es' => 'Tema de gradiente vibrante con colores cálidos',
                    'fr' => 'Thème dégradé vibrant avec des couleurs chaudes',
                    'ar' => 'ثيم متدرج نابض بالألوان الدافئة',
                ],
                'preview' => 'gradient-sunset.png',
                'colors' => [
                    'primary' => 'F97316',      // Orange 500
                    'secondary' => 'FB923C',    // Orange 400
                    'accent' => 'EC4899',       // Pink 500
                    'background' => 'FFF7ED',   // Orange 50
                    'surface' => 'FFFFFF',
                    'text' => '7C2D12',         // Orange 900
                    'text_light' => 'EA580C',   // Orange 600
                    'gradient_start' => 'DC2626', // Red 600
                    'gradient_end' => 'F97316',   // Orange 500
                ],
                'fonts' => [
                    'title' => 'Arial',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'vibrant',
            ],
            
            'scientific_navy' => [
                'name' => [
                    'en' => 'Scientific Navy',
                    'de' => 'Wissenschaftliches Marineblau',
                    'es' => 'Azul Marino Científico',
                    'fr' => 'Bleu Marine Scientifique',
                    'ar' => 'الأزرق العلمي',
                ],
                'description' => [
                    'en' => 'Professional scientific presentation style',
                    'de' => 'Professioneller wissenschaftlicher Präsentationsstil',
                    'es' => 'Estilo de presentación científica profesional',
                    'fr' => 'Style de présentation scientifique professionnel',
                    'ar' => 'أسلوب عرض علمي احترافي',
                ],
                'preview' => 'scientific-navy.png',
                'colors' => [
                    'primary' => '1E3A8A',      // Blue 900
                    'secondary' => '1E40AF',    // Blue 800
                    'accent' => 'F59E0B',       // Amber 500
                    'background' => 'F8FAFC',   // Slate 50
                    'surface' => 'FFFFFF',
                    'text' => '0F172A',         // Slate 900
                    'text_light' => '475569',   // Slate 600
                    'gradient_start' => '1E3A8A', // Blue 900
                    'gradient_end' => '3B82F6',   // Blue 500
                ],
                'fonts' => [
                    'title' => 'Times New Roman',
                    'body' => 'Calibri',
                    'title_size' => 44,
                    'subtitle_size' => 24,
                    'body_size' => 18,
                    'bullet_size' => 16,
                ],
                'style' => 'scientific',
            ],
        ];
    }
    
    /**
     * Get a specific theme configuration
     */
    public static function getTheme(string $themeKey): array
    {
        $themes = self::getThemes();
        return $themes[$themeKey] ?? $themes['professional_blue'];
    }
    
    /**
     * Get theme names for dropdown
     */
    public static function getThemeOptions(string $locale = 'en'): array
    {
        $themes = self::getThemes();
        $options = [];
        
        foreach ($themes as $key => $theme) {
            $options[$key] = [
                'name' => $theme['name'][$locale] ?? $theme['name']['en'],
                'description' => $theme['description'][$locale] ?? $theme['description']['en'],
            ];
        }
        
        return $options;
    }
    
    /**
     * Get slide layouts for different content types
     */
    public static function getSlideLayouts(): array
    {
        return [
            'title' => [
                'title_position' => ['x' => 50, 'y' => 200, 'width' => 860, 'height' => 100],
                'subtitle_position' => ['x' => 50, 'y' => 320, 'width' => 860, 'height' => 60],
                'logo_position' => ['x' => 800, 'y' => 480, 'width' => 100, 'height' => 50],
            ],
            'section' => [
                'number_position' => ['x' => 50, 'y' => 150, 'width' => 100, 'height' => 80],
                'title_position' => ['x' => 50, 'y' => 240, 'width' => 860, 'height' => 100],
                'progress_position' => ['x' => 50, 'y' => 480, 'width' => 860, 'height' => 20],
            ],
            'content' => [
                'title_position' => ['x' => 50, 'y' => 30, 'width' => 860, 'height' => 60],
                'body_position' => ['x' => 50, 'y' => 100, 'width' => 860, 'height' => 400],
                'footer_position' => ['x' => 50, 'y' => 510, 'width' => 860, 'height' => 30],
            ],
            'two_column' => [
                'title_position' => ['x' => 50, 'y' => 30, 'width' => 860, 'height' => 60],
                'left_column' => ['x' => 50, 'y' => 100, 'width' => 410, 'height' => 400],
                'right_column' => ['x' => 500, 'y' => 100, 'width' => 410, 'height' => 400],
            ],
            'bullets' => [
                'title_position' => ['x' => 50, 'y' => 30, 'width' => 860, 'height' => 60],
                'bullets_position' => ['x' => 70, 'y' => 110, 'width' => 820, 'height' => 400],
            ],
        ];
    }
}
