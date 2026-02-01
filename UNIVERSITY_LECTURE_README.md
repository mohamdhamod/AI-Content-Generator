# University Lecture Feature - محاضرات جامعية

## Overview
This feature adds professional university-level medical lecture generation with PowerPoint export capability, similar to global educational platforms.

## Features

### 1. New Content Type: University Lecture
- **Key**: `university_lecture`
- **Credits Cost**: 3 credits (reflects comprehensive content generation)
- **Icon**: `fa-graduation-cap`
- **Color**: `#6366F1` (Indigo)

### 2. Structured Academic Content
The AI generates lectures with:
- **Learning Objectives** (4-6 measurable objectives)
- **Lecture Outline** (Table of contents)
- **Multiple Sections** (Introduction, Pathophysiology, Clinical Presentation, Diagnosis, Management, Prognosis)
- **Case Discussions** (Clinical scenarios)
- **Key Takeaways** (Summary points)
- **References** (Academic citations)
- **Review Questions** (For student assessment)

### 3. PowerPoint Export
Professional `.pptx` files with:
- Title slide with clinic/specialty branding
- Objectives slide with learning outcomes
- Table of contents slide
- Section divider slides (with progress indicator)
- Content slides with bullet points
- Takeaways summary slide
- References slide
- Questions slide
- Thank you slide

### Design Features
- Gradient backgrounds (Indigo/Purple theme)
- Professional typography
- Bilingual support (Arabic/English)
- Academic formatting
- Clean, modern layout

## Technical Implementation

### Files Created/Modified

#### New Files
1. `database/migrations/2026_01_31_150000_add_university_lecture_content_type.php`
   - Adds university_lecture content type
   - Deactivates website_faq (as requested)

2. `app/Services/PowerPointExportService.php` (~737 lines)
   - Full PowerPoint generation service
   - Uses phpoffice/phppresentation library
   - Parses content into structured slides
   - Creates professional academic presentations

#### Modified Files
1. `app/Services/MedicalPromptService.php`
   - Added `getUniversityLectureRequirements()` method
   - Comprehensive prompt for academic lecture structure

2. `app/Http/Controllers/ContentGeneratorController.php`
   - Added `exportPowerPoint()` method
   - Validates content type before export

3. `routes/web.php`
   - Added route: `/result/{id}/export-pptx`

4. `resources/views/content-generator/show.blade.php`
   - Added PowerPoint export button (conditional for university lectures)

5. `resources/lang/en/translation.php`
   - Added translations for export_pptx

## Usage

### Generating a Lecture
1. Go to Content Generator
2. Select "University Lecture" / "محاضرة جامعية" as content type
3. Choose specialty and topic
4. Generate content

### Exporting to PowerPoint
1. View generated lecture content
2. Click "Export PowerPoint" button (orange gradient)
3. Download .pptx file
4. Open in PowerPoint/Google Slides/LibreOffice

## Dependencies
- `phpoffice/phppresentation` v1.2

## Translations
| Language | Name |
|----------|------|
| English | University Lecture |
| Arabic | محاضرة جامعية |
| German | Universitätsvorlesung |
| Spanish | Conferencia Universitaria |
| French | Cours Universitaire |

## Deactivated Features
- `website_faq` content type has been deactivated as requested

## Credits Cost
University Lecture costs **3 credits** per generation due to:
- Comprehensive content length (2000-3000 words)
- Complex structured formatting
- Multiple sections and case studies
- Higher AI processing requirements

---
*Developed by: Senior Laravel Architect + AI Product Designer + Senior AI Prompt Engineer + Senior Doctor*
