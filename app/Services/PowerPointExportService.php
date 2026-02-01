<?php

namespace App\Services;

use App\Models\GeneratedContent;
use App\Services\PowerPoint\ThemeConfig;
use App\Services\PowerPoint\SlideContentGenerator;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Shape\Drawing\File as DrawingFile;
use PhpOffice\PhpPresentation\Slide;
use PhpOffice\PhpPresentation\Slide\Background\Color as BackgroundColor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Professional PowerPoint Export Service
 * 
 * Generates high-quality presentations with multiple theme options
 * Following global platform standards (Canva, Beautiful.ai, Gamma, Tome)
 * 
 * @author Senior Laravel Architect + AI Product Designer + Senior AI Prompt Engineer + Senior Doctor
 */
class PowerPointExportService
{
    protected PhpPresentation $presentation;
    protected array $theme;
    protected array $colors;
    protected array $fonts;
    protected string $language = 'en';
    protected string $presentationStyle = 'educational';
    protected string $detailLevel = 'standard';
    protected array $exportOptions = [];

    /**
     * Presentation style configurations
     */
    protected array $styleConfig = [
        'educational' => [
            'icon' => '🎓',
            'tone' => 'instructional',
            'includeObjectives' => true,
            'includeSummary' => true,
            'includeQuiz' => false,
        ],
        'conference' => [
            'icon' => '🏛️',
            'tone' => 'formal',
            'includeObjectives' => true,
            'includeSummary' => true,
            'includeQuiz' => false,
        ],
        'workshop' => [
            'icon' => '🛠️',
            'tone' => 'interactive',
            'includeObjectives' => true,
            'includeSummary' => true,
            'includeQuiz' => true,
        ],
        'patient' => [
            'icon' => '❤️',
            'tone' => 'simple',
            'includeObjectives' => false,
            'includeSummary' => true,
            'includeQuiz' => false,
        ],
        'clinical' => [
            'icon' => '🏥',
            'tone' => 'clinical',
            'includeObjectives' => true,
            'includeSummary' => true,
            'includeQuiz' => false,
        ],
        'research' => [
            'icon' => '🔬',
            'tone' => 'academic',
            'includeObjectives' => true,
            'includeSummary' => true,
            'includeQuiz' => false,
        ],
    ];

    /**
     * Detail level configurations  
     */
    protected array $detailConfig = [
        'brief' => ['minSlides' => 5, 'maxSlides' => 10, 'bulletPoints' => 3],
        'standard' => ['minSlides' => 10, 'maxSlides' => 15, 'bulletPoints' => 5],
        'detailed' => ['minSlides' => 15, 'maxSlides' => 25, 'bulletPoints' => 6],
        'comprehensive' => ['minSlides' => 25, 'maxSlides' => 40, 'bulletPoints' => 8],
    ];

    /**
     * Export content to PowerPoint with selected theme and options
     */
    public function export(GeneratedContent $content, string $themeKey = 'professional_blue', array $options = []): string
    {
        // Load theme configuration
        $this->theme = ThemeConfig::getTheme($themeKey);
        $this->colors = $this->theme['colors'];
        $this->fonts = $this->theme['fonts'];
        $this->language = $content->language ?? 'en';
        
        // Set presentation options
        $this->presentationStyle = $options['style'] ?? 'educational';
        $this->detailLevel = $options['detail'] ?? 'standard';
        $this->exportOptions = $options;

        // Initialize presentation
        $this->presentation = new PhpPresentation();
        $this->setupDocumentProperties($content);

        // Remove default slide
        $this->presentation->removeSlideByIndex(0);

        // Parse and create slides
        $this->createPresentationFromContent($content);

        // Save to file
        $filename = $this->generateFilename($content);
        $filepath = storage_path('app/public/exports/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $writer = IOFactory::createWriter($this->presentation, 'PowerPoint2007');
        $writer->save($filepath);

        Log::info('PowerPoint exported successfully', [
            'content_id' => $content->id,
            'theme' => $themeKey,
            'style' => $this->presentationStyle,
            'detail' => $this->detailLevel,
            'filepath' => $filepath
        ]);

        return $filepath;
    }

    /**
     * Setup document properties
     */
    protected function setupDocumentProperties(GeneratedContent $content): void
    {
        $styleIcon = $this->styleConfig[$this->presentationStyle]['icon'] ?? '📊';
        
        $this->presentation->getDocumentProperties()
            ->setCreator('MedContent AI')
            ->setCompany('MedContent AI Platform')
            ->setTitle($content->title ?? 'Medical Presentation')
            ->setDescription("Professional {$this->presentationStyle} presentation generated by MedContent AI")
            ->setSubject($content->specialty?->name ?? 'Medical Education')
            ->setCategory('Medical Education')
            ->setKeywords("medical, education, healthcare, presentation, {$this->presentationStyle}");
    }

    /**
     * Generate filename for the export
     */
    protected function generateFilename(GeneratedContent $content): string
    {
        $slug = Str::slug($content->title ?? 'presentation');
        $timestamp = date('Ymd_His');
        return "presentation_{$slug}_{$timestamp}.pptx";
    }

    /**
     * Create the full presentation from content
     */
    protected function createPresentationFromContent(GeneratedContent $content): void
    {
        $parsedContent = $this->parseContent($content->generated_content);
        $title = $content->title ?? 'Medical Presentation';
        $specialty = $content->specialty?->name ?? '';
        $topic = $content->topic?->name ?? '';
        $contentType = $content->contentType?->name ?? 'Content';

        // 1. Title Slide
        $this->createTitleSlide($title, $specialty, $topic, $contentType);

        // 2. Learning Objectives (if exists)
        if (!empty($parsedContent['objectives'])) {
            $this->createObjectivesSlide($parsedContent['objectives']);
        }

        // 3. Table of Contents (if more than 2 sections)
        if (count($parsedContent['sections']) > 2) {
            $this->createAgendaSlide($parsedContent['sections']);
        }

        // 4. Content Slides with Section Dividers
        $totalSections = count($parsedContent['sections']);
        foreach ($parsedContent['sections'] as $index => $section) {
            // Section divider slide
            if ($totalSections > 1) {
                $this->createSectionDividerSlide($section['title'], $index + 1, $totalSections);
            }
            
            // Content slides for this section
            $this->createSectionContentSlides($section);
        }

        // 5. Key Takeaways
        if (!empty($parsedContent['takeaways'])) {
            $this->createTakeawaysSlide($parsedContent['takeaways']);
        }

        // 6. References
        if (!empty($parsedContent['references'])) {
            $this->createReferencesSlide($parsedContent['references']);
        }

        // 7. Q&A Slide
        $this->createQASlide();

        // 8. Thank You / Contact Slide
        $this->createThankYouSlide($specialty);
    }

    /**
     * Parse content into structured sections
     */
    protected function parseContent(string $content): array
    {
        $result = [
            'objectives' => [],
            'sections' => [],
            'takeaways' => [],
            'references' => [],
        ];

        // Clean content
        $content = strip_tags($content);
        $lines = array_filter(array_map('trim', explode("\n", $content)));
        
        $currentSection = null;
        $currentContent = [];
        $mode = 'content'; // 'objectives', 'references', 'takeaways', 'content'

        foreach ($lines as $line) {
            // Detect mode switches
            if (preg_match('/^(#{1,3}\s*)?(learning objectives|objectives|أهداف التعلم|الأهداف)/i', $line)) {
                $this->saveCurrentSection($result, $currentSection, $currentContent);
                $currentSection = null;
                $currentContent = [];
                $mode = 'objectives';
                continue;
            }
            
            if (preg_match('/^(#{1,3}\s*)?(references|sources|المراجع|المصادر)/i', $line)) {
                $this->saveCurrentSection($result, $currentSection, $currentContent);
                $currentSection = null;
                $currentContent = [];
                $mode = 'references';
                continue;
            }
            
            if (preg_match('/^(#{1,3}\s*)?(key takeaways|summary|conclusion|ملخص|النقاط الرئيسية|الخلاصة)/i', $line)) {
                $this->saveCurrentSection($result, $currentSection, $currentContent);
                $currentSection = null;
                $currentContent = [];
                $mode = 'takeaways';
                continue;
            }

            // Detect new section header
            if ($this->isHeaderLine($line)) {
                $headerTitle = $this->extractHeaderTitle($line);
                
                // Skip if it's a mode keyword
                if ($this->isModeKeyword($headerTitle)) {
                    continue;
                }
                
                // Save previous section if in content mode
                if ($mode === 'content') {
                    $this->saveCurrentSection($result, $currentSection, $currentContent);
                    $currentSection = ['title' => $headerTitle, 'content' => []];
                    $currentContent = [];
                }
                continue;
            }

            // Add content based on current mode
            $cleanLine = $this->cleanBulletPoint($line);
            if (empty($cleanLine)) continue;

            switch ($mode) {
                case 'objectives':
                    $result['objectives'][] = $cleanLine;
                    break;
                case 'references':
                    $result['references'][] = $cleanLine;
                    break;
                case 'takeaways':
                    $result['takeaways'][] = $cleanLine;
                    break;
                default:
                    $currentContent[] = $cleanLine;
            }
        }

        // Save final section
        $this->saveCurrentSection($result, $currentSection, $currentContent);

        // Ensure at least one section exists
        if (empty($result['sections'])) {
            $allContent = array_filter($lines, fn($l) => !empty(trim($l)));
            $result['sections'][] = [
                'title' => 'Overview',
                'content' => array_slice($allContent, 0, 20)
            ];
        }

        return $result;
    }

    /**
     * Check if line is a header
     */
    protected function isHeaderLine(string $line): bool
    {
        return preg_match('/^(#{1,3}|\d+\.|[١٢٣٤٥٦٧٨٩٠]+\.|\*\*[^*]+\*\*$)/', $line);
    }

    /**
     * Extract title from header line
     */
    protected function extractHeaderTitle(string $line): string
    {
        $line = preg_replace('/^#{1,3}\s*/', '', $line);
        $line = preg_replace('/^\d+\.\s*/', '', $line);
        $line = preg_replace('/^[١٢٣٤٥٦٧٨٩٠]+\.\s*/', '', $line);
        $line = trim($line, '*# ');
        return $line;
    }

    /**
     * Check if text is a mode keyword
     */
    protected function isModeKeyword(string $text): bool
    {
        $keywords = ['objectives', 'references', 'takeaways', 'summary', 'conclusion', 
                     'أهداف', 'المراجع', 'ملخص', 'الخلاصة'];
        foreach ($keywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Clean bullet point markers
     */
    protected function cleanBulletPoint(string $line): string
    {
        return trim(preg_replace('/^[-•*●○▪▫]\s*/', '', $line));
    }

    /**
     * Save current section to results
     */
    protected function saveCurrentSection(array &$result, ?array $section, array $content): void
    {
        if ($section && !empty($content)) {
            $section['content'] = $content;
            $result['sections'][] = $section;
        } elseif (!$section && !empty($content)) {
            // Content without a header - add to last section or create new
            if (!empty($result['sections'])) {
                $lastIndex = count($result['sections']) - 1;
                $result['sections'][$lastIndex]['content'] = array_merge(
                    $result['sections'][$lastIndex]['content'],
                    $content
                );
            }
        }
    }

    // ==========================================
    // SLIDE CREATION METHODS
    // ==========================================

    /**
     * Create professional title slide
     */
    protected function createTitleSlide(string $title, string $specialty, string $topic, string $contentType): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyGradientBackground($slide);

        // Main title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(120)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(160);

        $titleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $titleRun = $titleShape->createTextRun($title);
        $titleRun->getFont()
            ->setName($this->fonts['title'])
            ->setBold(true)
            ->setSize($this->fonts['title_size'])
            ->setColor(new Color('FFFFFFFF'));

        // Subtitle (specialty & topic)
        $subtitleShape = $slide->createRichTextShape()
            ->setHeight(50)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(300);

        $subtitleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $subtitle = implode(' • ', array_filter([$specialty, $topic]));
        if (!empty($subtitle)) {
            $subtitleRun = $subtitleShape->createTextRun($subtitle);
            $subtitleRun->getFont()
                ->setName($this->fonts['body'])
                ->setSize($this->fonts['subtitle_size'])
                ->setColor(new Color('DDFFFFFF'));
        }

        // Content type badge
        $badgeShape = $slide->createRichTextShape()
            ->setHeight(30)
            ->setWidth(200)
            ->setOffsetX(380)
            ->setOffsetY(370);

        $badgeShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $badgeRun = $badgeShape->createTextRun($contentType);
        $badgeRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize(12)
            ->setColor(new Color('AAFFFFFF'));

        // Branding footer
        $this->addBrandingFooter($slide, true);
    }

    /**
     * Create objectives slide
     */
    protected function createObjectivesSlide(array $objectives): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyBackground($slide);
        $this->addSlideHeader($slide, $this->isArabic() ? 'أهداف التعلم' : 'Learning Objectives', '🎯');

        $yOffset = 130;
        foreach (array_slice($objectives, 0, 6) as $index => $objective) {
            $this->addBulletItem($slide, $objective, $yOffset, $index + 1);
            $yOffset += 65;
        }

        $this->addSlideFooter($slide);
    }

    /**
     * Create agenda/table of contents slide
     */
    protected function createAgendaSlide(array $sections): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyBackground($slide);
        $this->addSlideHeader($slide, $this->isArabic() ? 'جدول المحتويات' : 'Agenda', '📋');

        $yOffset = 130;
        foreach (array_slice($sections, 0, 8) as $index => $section) {
            $shape = $slide->createRichTextShape()
                ->setHeight(45)
                ->setWidth(800)
                ->setOffsetX(80)
                ->setOffsetY($yOffset);

            // Number
            $numRun = $shape->createTextRun(sprintf('%02d  ', $index + 1));
            $numRun->getFont()
                ->setName($this->fonts['title'])
                ->setBold(true)
                ->setSize(22)
                ->setColor(new Color('FF' . $this->colors['primary']));

            // Title
            $titleRun = $shape->createTextRun($section['title']);
            $titleRun->getFont()
                ->setName($this->fonts['body'])
                ->setSize(20)
                ->setColor(new Color('FF' . $this->colors['text']));

            $yOffset += 52;
        }

        $this->addSlideFooter($slide);
    }

    /**
     * Create section divider slide
     */
    protected function createSectionDividerSlide(string $title, int $current, int $total): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyGradientBackground($slide);

        // Section indicator
        $indicatorShape = $slide->createRichTextShape()
            ->setHeight(40)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(150);

        $indicatorShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $indicatorText = $this->isArabic() 
            ? "القسم {$current} من {$total}"
            : "Section {$current} of {$total}";
        
        $indicatorRun = $indicatorShape->createTextRun($indicatorText);
        $indicatorRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize(16)
            ->setColor(new Color('AAFFFFFF'));

        // Section title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(100)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(220);

        $titleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $titleRun = $titleShape->createTextRun($title);
        $titleRun->getFont()
            ->setName($this->fonts['title'])
            ->setBold(true)
            ->setSize(40)
            ->setColor(new Color('FFFFFFFF'));

        // Progress bar
        $this->addProgressBar($slide, $current, $total);
    }

    /**
     * Create content slides for a section
     */
    protected function createSectionContentSlides(array $section): void
    {
        $content = $section['content'];
        $title = $section['title'];
        
        // Split content into chunks for multiple slides if needed
        $chunks = array_chunk($content, 6);
        
        foreach ($chunks as $chunkIndex => $chunk) {
            $slide = $this->presentation->createSlide();
            $this->applyBackground($slide);
            
            $slideTitle = count($chunks) > 1 
                ? $title . ' (' . ($chunkIndex + 1) . '/' . count($chunks) . ')'
                : $title;
            
            $this->addSlideHeader($slide, $slideTitle);

            $yOffset = 120;
            foreach ($chunk as $item) {
                $this->addContentItem($slide, $item, $yOffset);
                $yOffset += 60;
            }

            $this->addSlideFooter($slide);
        }
    }

    /**
     * Create key takeaways slide
     */
    protected function createTakeawaysSlide(array $takeaways): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyBackground($slide);
        $this->addSlideHeader($slide, $this->isArabic() ? 'النقاط الرئيسية' : 'Key Takeaways', '💡');

        $yOffset = 130;
        foreach (array_slice($takeaways, 0, 6) as $index => $takeaway) {
            $this->addHighlightedItem($slide, $takeaway, $yOffset, $index);
            $yOffset += 65;
        }

        $this->addSlideFooter($slide);
    }

    /**
     * Create references slide
     */
    protected function createReferencesSlide(array $references): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyBackground($slide);
        $this->addSlideHeader($slide, $this->isArabic() ? 'المراجع' : 'References', '📚');

        $yOffset = 130;
        foreach (array_slice($references, 0, 8) as $reference) {
            $shape = $slide->createRichTextShape()
                ->setHeight(40)
                ->setWidth(820)
                ->setOffsetX(70)
                ->setOffsetY($yOffset);

            $refRun = $shape->createTextRun('• ' . $this->truncateText($reference, 100));
            $refRun->getFont()
                ->setName($this->fonts['body'])
                ->setSize(14)
                ->setColor(new Color('FF' . $this->colors['text_light']));

            $yOffset += 45;
        }

        $this->addSlideFooter($slide);
    }

    /**
     * Create Q&A slide
     */
    protected function createQASlide(): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyGradientBackground($slide);

        // Question mark icon
        $iconShape = $slide->createRichTextShape()
            ->setHeight(80)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(150);

        $iconShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $iconRun = $iconShape->createTextRun('❓');
        $iconRun->getFont()->setSize(60);

        // Title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(80)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(240);

        $titleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $title = $this->isArabic() ? 'الأسئلة والمناقشة' : 'Questions & Discussion';
        $titleRun = $titleShape->createTextRun($title);
        $titleRun->getFont()
            ->setName($this->fonts['title'])
            ->setBold(true)
            ->setSize(44)
            ->setColor(new Color('FFFFFFFF'));

        // Subtitle
        $subtitleShape = $slide->createRichTextShape()
            ->setHeight(50)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(330);

        $subtitleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $subtitle = $this->isArabic() 
            ? 'هل لديك أي أسئلة؟'
            : 'Feel free to ask any questions';
        $subtitleRun = $subtitleShape->createTextRun($subtitle);
        $subtitleRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize(24)
            ->setColor(new Color('CCFFFFFF'));
    }

    /**
     * Create thank you slide
     */
    protected function createThankYouSlide(string $specialty): void
    {
        $slide = $this->presentation->createSlide();
        $this->applyGradientBackground($slide);

        // Thank you text
        $titleShape = $slide->createRichTextShape()
            ->setHeight(100)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(180);

        $titleShape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $thankYou = $this->isArabic() ? 'شكراً لكم' : 'Thank You';
        $titleRun = $titleShape->createTextRun($thankYou);
        $titleRun->getFont()
            ->setName($this->fonts['title'])
            ->setBold(true)
            ->setSize(54)
            ->setColor(new Color('FFFFFFFF'));

        // Specialty
        if (!empty($specialty)) {
            $specShape = $slide->createRichTextShape()
                ->setHeight(40)
                ->setWidth(860)
                ->setOffsetX(50)
                ->setOffsetY(300);

            $specShape->getActiveParagraph()->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $specRun = $specShape->createTextRun($specialty);
            $specRun->getFont()
                ->setName($this->fonts['body'])
                ->setSize(24)
                ->setColor(new Color('CCFFFFFF'));
        }

        $this->addBrandingFooter($slide, true);
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Apply gradient background to slide
     */
    protected function applyGradientBackground(Slide $slide): void
    {
        $background = $slide->getBackground();
        $background->setFill(new Fill());
        $background->getFill()
            ->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(135)
            ->setStartColor(new Color('FF' . $this->colors['gradient_start']))
            ->setEndColor(new Color('FF' . $this->colors['gradient_end']));
    }

    /**
     * Apply solid background to slide
     */
    protected function applyBackground(Slide $slide): void
    {
        $background = $slide->getBackground();
        $background->setFill(new Fill());
        $background->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('FF' . $this->colors['background']));
    }

    /**
     * Add slide header
     */
    protected function addSlideHeader(Slide $slide, string $title, string $icon = ''): void
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(60)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(30);

        if (!empty($icon)) {
            $iconRun = $shape->createTextRun($icon . '  ');
            $iconRun->getFont()->setSize(28);
        }

        $titleRun = $shape->createTextRun($title);
        $titleRun->getFont()
            ->setName($this->fonts['title'])
            ->setBold(true)
            ->setSize(32)
            ->setColor(new Color('FF' . $this->colors['primary']));

        // Underline
        $lineShape = $slide->createRichTextShape()
            ->setHeight(4)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(95);
        
        $lineShape->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('33' . $this->colors['primary']));
    }

    /**
     * Add slide footer with page number
     */
    protected function addSlideFooter(Slide $slide): void
    {
        $slideNumber = $this->presentation->getSlideCount();
        
        $shape = $slide->createRichTextShape()
            ->setHeight(25)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(510);

        $shape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $numRun = $shape->createTextRun((string)$slideNumber);
        $numRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize(10)
            ->setColor(new Color('FF' . $this->colors['text_light']));
    }

    /**
     * Add branding footer
     */
    protected function addBrandingFooter(Slide $slide, bool $lightText = false): void
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(30)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(500);

        $shape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $brandRun = $shape->createTextRun('Generated by MedContent AI');
        $brandRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize(11)
            ->setItalic(true)
            ->setColor(new Color($lightText ? 'AAFFFFFF' : 'FF' . $this->colors['text_light']));
    }

    /**
     * Add bullet item
     */
    protected function addBulletItem(Slide $slide, string $text, int $yOffset, int $number = 0): void
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(55)
            ->setWidth(800)
            ->setOffsetX(80)
            ->setOffsetY($yOffset);

        // Number or bullet
        $bulletChar = $number > 0 ? "{$number}. " : "• ";
        $bulletRun = $shape->createTextRun($bulletChar);
        $bulletRun->getFont()
            ->setName($this->fonts['body'])
            ->setBold(true)
            ->setSize($this->fonts['body_size'])
            ->setColor(new Color('FF' . $this->colors['primary']));

        // Text
        $textRun = $shape->createTextRun($this->truncateText($text, 120));
        $textRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize($this->fonts['body_size'])
            ->setColor(new Color('FF' . $this->colors['text']));
    }

    /**
     * Add content item
     */
    protected function addContentItem(Slide $slide, string $text, int $yOffset): void
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(50)
            ->setWidth(820)
            ->setOffsetX(70)
            ->setOffsetY($yOffset);

        $bulletRun = $shape->createTextRun('▸ ');
        $bulletRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize($this->fonts['bullet_size'])
            ->setColor(new Color('FF' . $this->colors['accent']));

        $textRun = $shape->createTextRun($this->truncateText($text, 130));
        $textRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize($this->fonts['bullet_size'])
            ->setColor(new Color('FF' . $this->colors['text']));
    }

    /**
     * Add highlighted item (for takeaways)
     */
    protected function addHighlightedItem(Slide $slide, string $text, int $yOffset, int $index): void
    {
        // Background shape
        $bgShape = $slide->createRichTextShape()
            ->setHeight(55)
            ->setWidth(820)
            ->setOffsetX(70)
            ->setOffsetY($yOffset);

        $bgShape->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('0A' . $this->colors['primary']));

        // Check mark
        $checkRun = $bgShape->createTextRun('  ✓  ');
        $checkRun->getFont()
            ->setName($this->fonts['body'])
            ->setBold(true)
            ->setSize($this->fonts['body_size'])
            ->setColor(new Color('FF' . $this->colors['accent']));

        // Text
        $textRun = $bgShape->createTextRun($this->truncateText($text, 110));
        $textRun->getFont()
            ->setName($this->fonts['body'])
            ->setSize($this->fonts['body_size'])
            ->setColor(new Color('FF' . $this->colors['text']));
    }

    /**
     * Add progress bar
     */
    protected function addProgressBar(Slide $slide, int $current, int $total): void
    {
        $barWidth = 300;
        $barHeight = 6;
        $startX = (960 - $barWidth) / 2;
        $startY = 400;

        // Background bar
        $bgBar = $slide->createRichTextShape()
            ->setHeight($barHeight)
            ->setWidth($barWidth)
            ->setOffsetX($startX)
            ->setOffsetY($startY);

        $bgBar->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('33FFFFFF'));

        // Progress bar
        $progressWidth = ($barWidth / $total) * $current;
        $progressBar = $slide->createRichTextShape()
            ->setHeight($barHeight)
            ->setWidth($progressWidth)
            ->setOffsetX($startX)
            ->setOffsetY($startY);

        $progressBar->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('FFFFFFFF'));
    }

    /**
     * Check if content is Arabic
     */
    protected function isArabic(): bool
    {
        return $this->language === 'ar';
    }

    /**
     * Truncate text
     */
    protected function truncateText(string $text, int $maxLength): string
    {
        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }
        return mb_substr($text, 0, $maxLength - 3) . '...';
    }

    /**
     * Get available themes
     */
    public static function getAvailableThemes(string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        return ThemeConfig::getThemeOptions($locale);
    }

    /**
     * Get supported content types
     */
    public static function getSupportedContentTypes(): array
    {
        return [
            'university_lecture',
            'patient_education', 
            'what_to_expect',
            'seo_blog_article',
            'social_media_post',
            'google_review_reply',
            'email_follow_up',
        ];
    }

    /**
     * Check if content supports export
     */
    public static function supportsExport(GeneratedContent $content): bool
    {
        return true; // All content types now supported
    }
}
