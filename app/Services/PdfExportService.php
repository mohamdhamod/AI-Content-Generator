<?php

namespace App\Services;

use App\Models\GeneratedContent;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class PdfExportService
{
    /**
     * Export content to PDF with medical formatting.
     *
     * @param GeneratedContent $content
     * @param array $options Options: format (a4, letter), orientation (portrait, landscape)
     * @return \Illuminate\Http\Response
     */
    public function exportToPdf(GeneratedContent $content, array $options = [])
    {
        $format = $options['format'] ?? 'A4';
        $orientation = $options['orientation'] ?? 'P'; // P=portrait, L=landscape
        
        // Detect if content is Arabic/RTL
        $language = $content->input_data['language'] ?? $content->language ?? 'English';
        $isRtl = $this->isRtlLanguage($language);
        
        // Generate PDF HTML with medical formatting
        $html = $this->generatePdfHtml($content);
        
        // Create PDF with mPDF with medical-grade configuration
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => $orientation === 'portrait' ? 'P' : 'L',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'simpleTables' => false,
            'useGraphs' => true,
            'directionality' => $isRtl ? 'rtl' : 'ltr',
        ]);
        
        // Enable emoji rendering
        $mpdf->showImageErrors = false;
        
        // Set RTL for entire document
        if ($isRtl) {
            $mpdf->SetDirectionality('rtl');
        }
        
        // Add PDF metadata for professionalism
        $mpdf->SetTitle($content->input_data['topic'] ?? 'Medical Content');
        $mpdf->SetAuthor(config('app.name'));
        $mpdf->SetCreator(config('app.name') . ' - AI Medical Content Generator');
        $mpdf->SetSubject('Medical Content - ' . ($content->specialty ? $content->specialty->name : 'General'));
        $mpdf->SetKeywords('medical, healthcare, ' . ($content->specialty ? strtolower($content->specialty->name) : 'health'));
        
        // Set professional header with medical branding
        $headerHtml = $this->generatePdfHeader($content, $isRtl);
        $mpdf->SetHTMLHeader($headerHtml);
        
        // Set professional footer with disclaimer and page numbers
        $footerHtml = $this->generatePdfFooter($content, $isRtl);
        $mpdf->SetHTMLFooter($footerHtml);
        
        $mpdf->WriteHTML($html);
        
        // Generate filename
        $filename = $this->generateFilename($content);
        
        // Track PDF export analytics
        $content->incrementPdfDownloads();
        
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

    /**
     * Stream PDF in browser instead of download.
     *
     * @param GeneratedContent $content
     * @param array $options
     * @return \Illuminate\Http\Response
     */
    public function streamPdf(GeneratedContent $content, array $options = [])
    {
        $format = $options['format'] ?? 'A4';
        $orientation = $options['orientation'] ?? 'P';
        
        // Detect if content is Arabic/RTL
        $language = $content->input_data['language'] ?? $content->language ?? 'English';
        $isRtl = $this->isRtlLanguage($language);
        
        // Generate PDF HTML with medical formatting
        $html = $this->generatePdfHtml($content);
        
        // Create PDF with mPDF with medical-grade configuration
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => $orientation === 'portrait' ? 'P' : 'L',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'simpleTables' => false,
            'useGraphs' => true,
            'directionality' => $isRtl ? 'rtl' : 'ltr',
        ]);
        
        // Enable emoji rendering
        $mpdf->showImageErrors = false;
        
        // Set RTL for entire document
        if ($isRtl) {
            $mpdf->SetDirectionality('rtl');
        }
        
        // Add PDF metadata for professionalism
        $mpdf->SetTitle($content->input_data['topic'] ?? 'Medical Content');
        $mpdf->SetAuthor(config('app.name'));
        $mpdf->SetCreator(config('app.name') . ' - AI Medical Content Generator');
        $mpdf->SetSubject('Medical Content - ' . ($content->specialty ? $content->specialty->name : 'General'));
        $mpdf->SetKeywords('medical, healthcare, ' . ($content->specialty ? strtolower($content->specialty->name) : 'health'));
        
        // Set professional header with medical branding
        $headerHtml = $this->generatePdfHeader($content, $isRtl);
        $mpdf->SetHTMLHeader($headerHtml);
        
        // Set professional footer with disclaimer and page numbers
        $footerHtml = $this->generatePdfFooter($content, $isRtl);
        $mpdf->SetHTMLFooter($footerHtml);
        
        // Set RTL for the entire document if Arabic
        if ($isRtl) {
            $mpdf->SetDirectionality('rtl');
        }
        
        $mpdf->WriteHTML($html);
        
        $filename = $this->generateFilename($content);
        
        return response($mpdf->Output($filename, 'I'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Generate professionally formatted HTML for PDF with medical content standards.
     *
     * @param GeneratedContent $content
     * @return string
     */
    protected function generatePdfHtml(GeneratedContent $content): string
    {
        $specialty = $content->specialty ? $content->specialty->name : __('translation.content_generator.form.general');
        $topic = $content->input_data['topic'] ?? '-';
        $contentType = $content->contentType ? $content->contentType->name : '-';
        $language = $content->input_data['language'] ?? $content->language ?? 'English';
        $generatedDate = $content->created_at->format('F d, Y');
        $generatedTime = $content->created_at->format('H:i');
        
        // Replace emojis with readable alternatives (PDF doesn't support colored emojis)
        $cleanedText = $this->replaceEmojisForPdf($content->output_text, $language);
        
        // Convert markdown to HTML
        $contentHtml = \Illuminate\Support\Str::markdown($cleanedText);
        
        // Detect if content is Arabic/RTL
        $isRtl = $this->isRtlLanguage($language);
        $direction = $isRtl ? 'rtl' : 'ltr';
        $langCode = $isRtl ? 'ar' : 'en';
        $textAlign = $isRtl ? 'right' : 'left';
        
        return <<<HTML
<!DOCTYPE html>
<html lang="{$langCode}" dir="{$direction}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$topic}</title>
    <style>
        @page {
            margin: 2cm;
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9pt;
                color: #666;
            }
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial Unicode MS', 'Segoe UI Symbol', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            direction: {$direction};
            text-align: {$textAlign};
        }
        
        .header {
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #0d6efd;
            font-size: 24pt;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        
        .metadata {
            background-color: #f8f9fa;
            padding: 15px;
            border-{$textAlign}: 4px solid #0d6efd;
            margin-bottom: 25px;
            font-size: 9pt;
        }
        
        .metadata table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .metadata td {
            padding: 4px 0;
        }
        
        .metadata td:first-child {
            font-weight: 600;
            width: 30%;
            color: #666;
        }
        
        .content {
            text-align: justify;
            direction: {$direction};
        }
        
        .content h1 {
            font-size: 18pt;
            font-weight: 700;
            color: #0d6efd;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #0d6efd;
            page-break-after: avoid;
        }
        
        .content h2 {
            font-size: 15pt;
            font-weight: 600;
            color: #212529;
            margin-top: 20px;
            margin-bottom: 12px;
            page-break-after: avoid;
        }
        
        .content h3 {
            font-size: 13pt;
            font-weight: 600;
            color: #495057;
            margin-top: 15px;
            margin-bottom: 10px;
            page-break-after: avoid;
        }
        
        .content p {
            margin-bottom: 12px;
            line-height: 1.7;
            text-align: justify;
        }
        
        .content ul, .content ol {
            margin: 12px 0;
            padding-left: 25px;
        }
        
        .content li {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        
        .content strong {
            font-weight: 600;
            color: #212529;
        }
        
        .content em {
            font-style: italic;
        }
        
        .content blockquote {
            border-left: 4px solid #dee2e6;
            padding-left: 15px;
            margin: 15px 0;
            color: #6c757d;
            font-style: italic;
            background-color: #f8f9fa;
            padding: 12px 15px;
        }
        
        .content code {
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 9pt;
            color: #d63384;
        }
        
        .content pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 15px 0;
            border: 1px solid #dee2e6;
        }
        
        .content pre code {
            background: none;
            padding: 0;
            color: #212529;
        }
        
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .content table th,
        .content table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        
        .content table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 8pt;
            color: #6c757d;
            text-align: center;
        }
        
        .disclaimer {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin: 25px 0;
            font-size: 9pt;
            color: #856404;
        }
        
        .disclaimer strong {
            display: block;
            margin-bottom: 5px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{$topic}</h1>
    </div>
    
    <div class="metadata">
        <table>
            <tr>
                <td>Content Type:</td>
                <td>{$contentType}</td>
            </tr>
            <tr>
                <td>Specialty:</td>
                <td>{$specialty}</td>
            </tr>
            <tr>
                <td>Language:</td>
                <td>{$language}</td>
            </tr>
            <tr>
                <td>Generated Date:</td>
                <td>{$generatedDate} at {$generatedTime}</td>
            </tr>
        </table>
    </div>
    
    <div class="content">
        {$contentHtml}
    </div>
    
    <div class="disclaimer">
        <strong>⚠️ Medical Disclaimer</strong>
        This content was generated using artificial intelligence and should be reviewed by qualified medical professionals before use. It is intended for informational purposes only and should not replace professional medical advice, diagnosis, or treatment.
    </div>
    
    <div class="footer">
        Generated by AI Medical Content Generator | {$generatedDate}<br>
        This document is confidential and intended for professional use only.
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate filename for the PDF.
     *
     * @param GeneratedContent $content
     * @return string
     */
    protected function generateFilename(GeneratedContent $content): string
    {
        $topic = $content->input_data['topic'] ?? 'content';
        
        // Clean topic for filename
        $cleanTopic = preg_replace('/[^A-Za-z0-9\-_]/', '_', $topic);
        $cleanTopic = substr($cleanTopic, 0, 50); // Limit length
        
        $date = now()->format('Y-m-d');
        
        return "medical_content_{$cleanTopic}_{$date}.pdf";
    }

    /**
     * Log PDF export for analytics (optional - implement later if needed).
     *
     * @param GeneratedContent $content
     * @param string $format
     * @return void
     */
    protected function logExport(GeneratedContent $content, string $format): void
    {
        // Future implementation: track PDF exports in database
        // This could include: user_id, content_id, format, exported_at
        // Useful for analytics and usage tracking
    }

    /**
     * Replace emojis with readable text for PDF compatibility.
     * mPDF doesn't support colored emojis properly.
     *
     * @param string $text
     * @param string $language
     * @return string
     */
    protected function replaceEmojisForPdf(string $text, string $language = 'English'): string
    {
        // Detect if language is Arabic/RTL
        $isArabic = $this->isRtlLanguage($language);
        
        $replacements = [
            // Numbers (universal)
            '1️⃣' => '①',
            '2️⃣' => '②',
            '3️⃣' => '③',
            '4️⃣' => '④',
            '5️⃣' => '⑤',
            '6️⃣' => '⑥',
            '7️⃣' => '⑦',
            '8️⃣' => '⑧',
            '9️⃣' => '⑨',
            '🔟' => '⑩',
            '0️⃣' => '⓪',
            
            // Medical symbols
            '🧠' => $isArabic ? '◉ [المخ]' : '◉ [Brain]',
            '💊' => $isArabic ? '◉ [دواء]' : '◉ [Medicine]',
            '🩺' => $isArabic ? '◉ [سماعة طبية]' : '◉ [Stethoscope]',
            '🏥' => $isArabic ? '◉ [مستشفى]' : '◉ [Hospital]',
            '⚕️' => $isArabic ? '◉ [طبي]' : '◉ [Medical]',
            '💉' => $isArabic ? '◉ [حقنة]' : '◉ [Syringe]',
            '🔬' => $isArabic ? '◉ [مجهر]' : '◉ [Microscope]',
            '🧪' => $isArabic ? '◉ [أنبوب اختبار]' : '◉ [Test Tube]',
            '🩹' => $isArabic ? '◉ [ضمادة]' : '◉ [Bandage]',
            '🧬' => '◉ [DNA]',
            '❤️' => '♥',
            '🫀' => $isArabic ? '◉ [قلب]' : '◉ [Heart]',
            '🫁' => $isArabic ? '◉ [رئتين]' : '◉ [Lungs]',
            '🦴' => $isArabic ? '◉ [عظم]' : '◉ [Bone]',
            '👨‍⚕️' => $isArabic ? '◉ [طبيب]' : '◉ [Doctor]',
            '👩‍⚕️' => $isArabic ? '◉ [طبيبة]' : '◉ [Doctor]',
            '👨‍🔬' => $isArabic ? '◉ [عالم]' : '◉ [Scientist]',
            '👩‍🔬' => $isArabic ? '◉ [عالمة]' : '◉ [Scientist]',
            
            // Activities
            '🏃‍♀️' => $isArabic ? '◉ [جري]' : '◉ [Running]',
            '🏃‍♂️' => $isArabic ? '◉ [جري]' : '◉ [Running]',
            '🏃' => $isArabic ? '◉ [جري]' : '◉ [Running]',
            '🚶' => $isArabic ? '◉ [مشي]' : '◉ [Walking]',
            '🧘' => $isArabic ? '◉ [تأمل]' : '◉ [Meditation]',
            '🏋️' => $isArabic ? '◉ [رياضة]' : '◉ [Exercise]',
            
            // Common symbols
            '✅' => '✓',
            '❌' => '✗',
            '⚠️' => '⚠',
            '💡' => $isArabic ? '◉ [فكرة]' : '◉ [Idea]',
            '📌' => $isArabic ? '◉ [تثبيت]' : '◉ [Pin]',
            '📝' => $isArabic ? '◉ [ملاحظة]' : '◉ [Note]',
            '🔍' => $isArabic ? '◉ [بحث]' : '◉ [Search]',
            '⭐' => '★',
            '✨' => '◆',
            '🔴' => '●',
            '🔵' => '●',
            '🟢' => '●',
            '🟡' => '●',
            '🟠' => '●',
            '🟣' => '●',
            
            // Arrows (universal)
            '➡️' => '→',
            '⬅️' => '←',
            '⬆️' => '↑',
            '⬇️' => '↓',
            '↗️' => '↗',
            '↘️' => '↘',
            
            // Food & health
            '🥗' => $isArabic ? '◉ [سلطة]' : '◉ [Salad]',
            '🥤' => $isArabic ? '◉ [مشروب]' : '◉ [Drink]',
            '🍎' => $isArabic ? '◉ [تفاحة]' : '◉ [Apple]',
            '🥦' => $isArabic ? '◉ [بروكلي]' : '◉ [Broccoli]',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Check if language is RTL (Right-to-Left).
     *
     * @param string $language
     * @return bool
     */
    protected function isRtlLanguage(string $language): bool
    {
        $rtlLanguages = ['arabic', 'ar', 'العربية', 'hebrew', 'he', 'urdu', 'ur', 'persian', 'fa'];
        return in_array(strtolower($language), $rtlLanguages);
    }
    
    /**
     * Generate professional PDF header with medical branding.
     *
     * @param GeneratedContent $content
     * @param bool $isRtl
     * @return string
     */
    protected function generatePdfHeader(GeneratedContent $content, bool $isRtl = false): string
    {
        $appName = config('app.name');
        $specialty = $content->specialty ? $content->specialty->name : 'Medical Content';
        $direction = $isRtl ? 'rtl' : 'ltr';
        $textAlign = $isRtl ? 'right' : 'left';
        $borderSide = $isRtl ? 'border-right' : 'border-left';
        
        return <<<HTML
<div style="border-bottom: 2px solid #0d6efd; padding: 10px 0; font-family: DejaVuSans; direction: {$direction}; text-align: {$textAlign};">
    <table width="100%" style="direction: {$direction};">
        <tr>
            <td style="width: 70%; text-align: {$textAlign};">
                <div style="font-size: 14pt; font-weight: bold; color: #0d6efd;">
                    ◆ {$appName}
                </div>
                <div style="font-size: 9pt; color: #666; margin-top: 3px;">
                    AI Medical Content Generator
                </div>
            </td>
            <td style="width: 30%; text-align: {$textAlign};">
                <div style="font-size: 10pt; color: #333; {$borderSide}: 3px solid #0d6efd; padding-{$textAlign}: 8px;">
                    <strong>{$specialty}</strong>
                </div>
            </td>
        </tr>
    </table>
</div>
HTML;
    }
    
    /**
     * Generate professional PDF footer with medical disclaimer.
     *
     * @param GeneratedContent $content
     * @param bool $isRtl
     * @return string
     */
    protected function generatePdfFooter(GeneratedContent $content, bool $isRtl = false): string
    {
        $appName = config('app.name');
        $generatedDate = $content->created_at->format('Y-m-d H:i');
        $direction = $isRtl ? 'rtl' : 'ltr';
        $textAlign = $isRtl ? 'right' : 'left';
        
        $disclaimer = $isRtl 
            ? '⚠ هذا المستند تم إنشاؤه بواسطة الذكاء الاصطناعي. يرجى التحقق من جميع المعلومات الطبية قبل الاستخدام.'
            : '⚠ This document was AI-generated. Please verify all medical information before use.';
        
        return <<<HTML
<div style="border-top: 1px solid #ddd; padding: 8px 0; font-family: DejaVuSans; direction: {$direction};">
    <!-- Disclaimer -->
    <div style="background-color: #fff3cd; border-{$textAlign}: 3px solid #ffc107; padding: 6px 10px; margin-bottom: 8px; font-size: 8pt; text-align: {$textAlign};">
        <strong style="color: #997404;">{$disclaimer}</strong>
    </div>
    
    <!-- Footer Info -->
    <table width="100%" style="font-size: 8pt; color: #666; direction: {$direction};">
        <tr>
            <td style="width: 50%; text-align: {$textAlign};">
                <strong>{$appName}</strong> | {$generatedDate}
            </td>
            <td style="width: 50%; text-align: {$textAlign};">
                Page {PAGENO} of {nbpg}
            </td>
        </tr>
    </table>
</div>
HTML;
    }
}
