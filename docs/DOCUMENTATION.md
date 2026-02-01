# AI Medical Content - Complete Documentation

<p align="center">
  <strong>Comprehensive Technical Documentation</strong><br>
  Version 1.0 | February 2026
</p>

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Architecture](#2-architecture)
3. [Content Types](#3-content-types)
4. [AI Content Generation](#4-ai-content-generation)
5. [AI Refinement System](#5-ai-refinement-system)
6. [SEO Scoring System](#6-seo-scoring-system)
7. [Social Media Preview](#7-social-media-preview)
8. [Content Calendar & Scheduling](#8-content-calendar--scheduling)
9. [Team Collaboration](#9-team-collaboration)
10. [Analytics System](#10-analytics-system)
11. [Export Systems](#11-export-systems)
12. [Template System](#12-template-system)
13. [Subscription & Payment](#13-subscription--payment)
14. [API Reference](#14-api-reference)
15. [Database Schema](#15-database-schema)
16. [Security & Guardrails](#16-security--guardrails)

---

## 1. System Overview

AI Medical Content is a professional platform for healthcare professionals to generate high-quality, medically accurate content using AI.

### Key Features

| Feature | Description |
|---------|-------------|
| AI Content Generation | GPT-4o powered medical content |
| 8 Content Types | Patient education, blogs, social media, etc. |
| 4 Medical Specialties | Dentistry, Dermatology, General, Physiotherapy |
| 5 Languages | English, Arabic, German, Spanish, French |
| Team Collaboration | Invite members, assign tasks, review workflow |
| SEO Analysis | Real-time scoring with recommendations |
| Social Preview | Live preview for major platforms |
| Export Options | PDF and PowerPoint export |

### Technical Stack

- **Framework**: Laravel 11.x
- **Frontend**: Bootstrap 5, Vite, Blade
- **Database**: MySQL 8.0
- **AI Model**: OpenAI GPT-4o
- **Payment**: Digistore24
- **Authentication**: Laravel Fortify + Sanctum

---

## 2. Architecture

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                    # API Controllers
│   │   ├── Dashboard/              # Admin Controllers
│   │   ├── ContentGeneratorController.php
│   │   ├── TeamCollaborationController.php
│   │   └── ...
│   ├── Middleware/
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── GeneratedContent.php
│   ├── ContentType.php
│   ├── Specialty.php
│   ├── Topic.php
│   ├── TeamWorkspace.php
│   ├── TeamMember.php
│   └── ...
├── Services/
│   ├── ContentGeneratorService.php
│   ├── OpenAIService.php
│   ├── GuardrailService.php
│   ├── CreditService.php
│   ├── SeoScoringService.php
│   ├── ContentRefinementService.php
│   ├── TeamCollaborationService.php
│   ├── Digistore24Service.php
│   ├── PdfExportService.php
│   ├── PowerPointExportService.php
│   └── ...
└── ...
```

### Service Layer Pattern

All business logic is encapsulated in services:

```php
// Example: ContentGeneratorService
class ContentGeneratorService
{
    public function generate(
        User $user,
        int $contentTypeId,
        ?int $specialtyId,
        ?int $topicId,
        array $inputData,
        string $locale = 'en'
    ): array;
}
```

---

## 3. Content Types

### Available Content Types (8 Total)

| Key | Name | Icon | Credits |
|-----|------|------|---------|
| `patient_education` | Patient Education | fa-user-graduate | 1 |
| `what_to_expect` | What to Expect | fa-calendar-check | 1 |
| `seo_blog_article` | SEO Blog Article | fa-newspaper | 2 |
| `social_media_post` | Social Media Post | fa-share-alt | 1 |
| `google_review_reply` | Google Review Reply | fa-reply | 1 |
| `email_follow_up` | Email Follow-up | fa-envelope | 1 |
| `website_faq` | Website FAQ | fa-question-circle | 1 |
| `university_lecture` | University Lecture | fa-chalkboard-teacher | 3 |

### Database Schema

```sql
-- content_types table
CREATE TABLE content_types (
    id BIGINT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE,
    key VARCHAR(255) UNIQUE,
    icon VARCHAR(255),
    color VARCHAR(255),
    active BOOLEAN DEFAULT true,
    sort_order INT DEFAULT 0,
    credits_cost INT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- content_type_translations table
CREATE TABLE content_type_translations (
    id BIGINT PRIMARY KEY,
    content_type_id BIGINT REFERENCES content_types(id),
    locale VARCHAR(10),
    name VARCHAR(255),
    description TEXT,
    placeholder TEXT,
    UNIQUE(content_type_id, locale)
);
```

---

## 4. AI Content Generation

### Generation Flow

```
User Request → Input Validation → Guardrail Check → 
Credit Verification → AI Generation → Output Filtering → 
Save to Database → Deduct Credits → Return Response
```

### OpenAI Integration

```php
// config/services.php
'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-4o'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 2000),
    'temperature' => env('OPENAI_TEMPERATURE', 0.7),
],
```

### ContentGeneratorService

```php
$result = $contentGeneratorService->generate(
    user: $user,
    contentTypeId: 1,
    specialtyId: 1,
    topicId: 5,
    inputData: [
        'topic' => 'Teeth Whitening',
        'word_count' => 500,
        'country' => 'US',
    ],
    locale: 'en'
);

// Response structure
[
    'success' => true,
    'content' => '...generated content...',
    'word_count' => 523,
    'tokens_used' => 1250,
    'credits_used' => 1,
    'content_id' => 42,
    'guardrail_issues' => [],
]
```

---

## 5. AI Refinement System

### 10 Refinement Actions

| Action | Description |
|--------|-------------|
| `improve_clarity` | Improve readability and clarity |
| `enhance_medical_accuracy` | Enhance medical terminology and accuracy |
| `simplify_language` | Simplify for general audience |
| `add_examples` | Add practical examples and scenarios |
| `expand_details` | Expand with more detailed information |
| `make_concise` | Make more concise and focused |
| `improve_structure` | Improve structure and organization |
| `add_citations` | Add medical citations and references |
| `patient_friendly` | Make more patient-friendly |
| `professional_tone` | Enhance professional medical tone |

### Usage

```php
// Refine content
$refinedContent = $refinementService->refineContent(
    $content,
    'improve_clarity',
    ['tone' => 'professional']
);

// API Endpoint
POST /generate/result/{id}/refine
{
    "action": "improve_clarity",
    "tone": "professional"
}
```

### Version Control

Every refinement creates a new version:

```php
// GeneratedContent model relationships
public function parentContent()
{
    return $this->belongsTo(GeneratedContent::class, 'parent_content_id');
}

public function childVersions()
{
    return $this->hasMany(GeneratedContent::class, 'parent_content_id');
}
```

---

## 6. SEO Scoring System

### Scoring Components

| Component | Weight | Optimal Range |
|-----------|--------|---------------|
| Content Length | 15% | 300-2500 words |
| Readability | 20% | Flesch 40-60 |
| Keyword Density | 15% | 1-3% |
| Headings Structure | 15% | H1 + H2s |
| Meta Description | 10% | 120-160 chars |
| Keyword Placement | 10% | In first 100 words |
| Content Uniqueness | 10% | Low repetition |
| Medical Terminology | 5% | Appropriate use |

### Grading Scale

| Score | Grade |
|-------|-------|
| 90-100 | A+ |
| 80-89 | A |
| 70-79 | B+ |
| 60-69 | B |
| 50-59 | C |
| 40-49 | D |
| 0-39 | F |

### Usage

```php
$seoService = new SeoScoringService();
$result = $seoService->calculateScore($content, [
    'target_keyword' => 'teeth whitening',
    'meta_description' => 'Learn about professional teeth whitening...',
]);

// Response
[
    'overall_score' => 85,
    'grade' => 'A',
    'scores' => [
        'content_length' => [...],
        'readability' => [...],
        'keyword_density' => [...],
        // ...
    ],
    'recommendations' => [...],
    'statistics' => [...],
]
```

---

## 7. Social Media Preview

### Supported Platforms

| Platform | Preview Features |
|----------|-----------------|
| Facebook | Post card, image, character limit |
| LinkedIn | Professional post format |
| Instagram | Visual-focused preview |
| Twitter/X | Tweet with character counter |
| TikTok | Short-form content preview |

### Platform Colors

```javascript
const socialColors = {
    facebook: { primary: '#1877F2', bg: '#F0F2F5' },
    linkedin: { primary: '#0A66C2', bg: '#F3F2EF' },
    instagram: { primary: '#E4405F', bg: '#FAFAFA' },
    twitter: { primary: '#1DA1F2', bg: '#F7F9FA' },
    tiktok: { primary: '#000000', bg: '#F8F8F8' }
};
```

### Character Limits

| Platform | Limit |
|----------|-------|
| Twitter/X | 280 |
| LinkedIn | 3,000 |
| Facebook | 63,206 |
| Instagram | 2,200 |
| TikTok | 2,200 |

---

## 8. Content Calendar & Scheduling

### Publishing Status

```php
// Available statuses
'draft'       // Default state
'scheduled'   // Has scheduled_at date
'published'   // Live content
'archived'    // Removed from active use
```

### Scheduling Content

```php
$content->update([
    'publishing_status' => 'scheduled',
    'scheduled_at' => Carbon::parse('2026-02-15 10:00:00'),
]);
```

### Database Indexes

```sql
-- For performance optimization
INDEX generated_contents_publishing_status_index (publishing_status);
INDEX generated_contents_publishing_scheduled_index (publishing_status, scheduled_at);
INDEX generated_contents_scheduled_at_index (scheduled_at);
```

---

## 9. Team Collaboration

### Team Roles

| Role | Permissions |
|------|------------|
| Owner | Full access, delete team |
| Admin | Manage members, all content |
| Editor | Create/edit content |
| Reviewer | Review and approve content |
| Viewer | Read-only access |

### Invitation System

**For Registered Users:**
1. Admin invites by email
2. User receives email with "Accept" button
3. User logs in → auto-joined to team

**For New Users:**
1. Admin invites by email
2. User receives email with "Register & Join" button
3. After registration → auto-linked to invitation
4. On login → auto-joined to team

### Content Assignment

```php
// Assign content to team member
$content->assignments()->create([
    'team_member_id' => $memberId,
    'assigned_by' => auth()->id(),
    'status' => 'pending',
    'priority' => 'high',
    'due_date' => now()->addDays(7),
    'notes' => 'Please review and approve',
]);
```

### Comments System

```php
// Add comment to content
$content->comments()->create([
    'user_id' => auth()->id(),
    'comment' => 'Great content, approved!',
    'is_resolved' => false,
]);
```

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/teams` | List user's teams |
| POST | `/teams` | Create new team |
| POST | `/teams/{id}/invite` | Invite member |
| GET | `/teams/invitation/accept` | Accept by token |
| POST | `/content/{id}/assign` | Assign content |
| GET | `/my-tasks` | Get user's tasks |
| POST | `/content/{id}/comments` | Add comment |

---

## 10. Analytics System

### Tracked Metrics

| Metric | Description |
|--------|-------------|
| `view_count` | Page views |
| `share_count` | Social shares |
| `pdf_download_count` | PDF exports |
| `social_preview_count` | Social previews |

### ContentAnalytics Model

```php
// Track any action
ContentAnalytics::track($contentId, 'social_preview', 'facebook');
ContentAnalytics::track($contentId, 'pdf_download');
ContentAnalytics::track($contentId, 'copy');
```

### Review Workflow

```php
// Status flow
'draft' → 'pending_review' → 'reviewed' → 'approved' / 'rejected'

// Submit for review
$content->submitForReview();

// Approve content
$content->approve($reviewerId, 'Medically accurate.');

// Reject content
$content->reject($reviewerId, 'Needs clarification.');
```

### Rate Limiting

| Action | Limit |
|--------|-------|
| Content Generation | 10/minute |
| PDF Export | 5/minute |
| Social Preview | 15/minute |
| Login Attempts | 5/minute |

---

## 11. Export Systems

### PDF Export

```php
$pdfService = new PdfExportService();
$pdf = $pdfService->export($content, [
    'include_header' => true,
    'include_footer' => true,
    'include_disclaimer' => true,
]);

return $pdf->download('content.pdf');
```

### PowerPoint Export

#### 8 Professional Themes

| Theme | Best For |
|-------|----------|
| `professional_blue` | Business & Corporate |
| `medical_green` | Healthcare & Medicine |
| `academic_purple` | University Lectures |
| `modern_dark` | Tech & Modern Topics |
| `clean_minimal` | Simple & Clean |
| `healthcare_teal` | Health & Wellness |
| `gradient_sunset` | Creative & Dynamic |
| `scientific_navy` | Research & Science |

#### 6 Presentation Styles

| Style | Description |
|-------|-------------|
| `educational` | University lectures |
| `conference` | Scientific conferences |
| `workshop` | Interactive training |
| `patient` | Patient education |
| `clinical` | Case presentations |
| `research` | Academic research |

#### 4 Detail Levels

| Level | Slides |
|-------|--------|
| `brief` | 5-10 |
| `standard` | 10-15 |
| `detailed` | 15-25 |
| `comprehensive` | 25+ |

```php
$pptxService = new PowerPointExportService();
$file = $pptxService->export($content, 'medical_green', [
    'style' => 'educational',
    'detail' => 'standard',
]);
```

---

## 12. Template System

### Save Template

```php
Template::create([
    'user_id' => auth()->id(),
    'name' => 'My Template',
    'content_type_id' => 1,
    'specialty_id' => 1,
    'topic_id' => 5,
    'settings' => [
        'word_count' => 500,
        'tone' => 'professional',
        'include_disclaimer' => true,
    ],
    'is_public' => false,
]);
```

### Load Template

```php
$template = Template::find($id);
$settings = $template->settings;

// Use settings for new generation
$result = $contentGenerator->generate(
    $user,
    $template->content_type_id,
    $template->specialty_id,
    $template->topic_id,
    $settings,
    $locale
);
```

---

## 13. Subscription & Payment

### Subscription Plans

| Plan | Price | Credits | Features |
|------|-------|---------|----------|
| Free | $0 | 5/month | Basic content types |
| Professional | $49 | 100/month | All features |
| Clinic Plus | $99 | 500/month | Team collaboration |
| Enterprise | $299 | 5,000/month | API access |

### Digistore24 Integration

#### Configuration

```env
DIGISTORE24_API_KEY=your_api_key
DIGISTORE24_IPN_SIGNATURE_KEY=your_signature_key
DIGISTORE24_VENDOR_ID=your_vendor_id
DIGISTORE24_SANDBOX=false
```

#### Webhook Events

| Event | Action |
|-------|--------|
| `on_payment` | Activate/renew subscription |
| `on_payment_missed` | Suspend subscription |
| `on_refund` | Cancel subscription |
| `on_chargeback` | Cancel + flag user |

#### Webhook URL

```
POST https://your-domain.com/webhooks/digistore24
```

### Credit System

```php
$creditService = new CreditService();

// Check credits
$creditService->hasEnoughCredits($user, $required);

// Deduct credits
$creditService->deductCredits($user, $amount);

// Get remaining
$remaining = $creditService->getRemainingCredits($user);

// Get usage stats
$stats = $creditService->getUsageStats($user);
// Returns: total, used, remaining, percentage_used
```

---

## 14. API Reference

### Authentication

```bash
# Login
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}

# Response
{
    "success": true,
    "token": "Bearer eyJ...",
    "user": {...}
}
```

### Content Generation

```bash
# Generate content
POST /api/v1/content/generate
Authorization: Bearer {token}
Content-Type: application/json

{
    "content_type_id": 1,
    "specialty_id": 1,
    "topic_id": 5,
    "language": "en",
    "word_count": 500
}
```

### Content Management

```bash
# Get user's content
GET /api/v1/content
Authorization: Bearer {token}

# Get single content
GET /api/v1/content/{id}
Authorization: Bearer {token}

# Delete content
DELETE /api/v1/content/{id}
Authorization: Bearer {token}
```

---

## 15. Database Schema

### Core Tables

```sql
-- users
users (id, name, email, password, monthly_credits, used_credits, ...)

-- subscriptions
subscriptions (id, name, price, max_content_generations, digistore_product_id, ...)

-- user_subscriptions
user_subscriptions (id, user_id, subscription_id, digistore_order_id, status, ...)

-- specialties
specialties (id, slug, icon, active, sort_order, ...)
specialty_translations (id, specialty_id, locale, name, description, ...)

-- content_types
content_types (id, key, slug, icon, credits_cost, ...)
content_type_translations (id, content_type_id, locale, name, ...)

-- topics
topics (id, specialty_id, slug, icon, active, ...)
topic_translations (id, topic_id, locale, name, description, prompt_hint, ...)

-- generated_contents
generated_contents (
    id, user_id, specialty_id, topic_id, content_type_id,
    input_data, output_text, language, country,
    word_count, credits_used, tokens_used, status,
    version, parent_content_id,
    view_count, share_count, pdf_download_count,
    review_status, reviewed_by, review_notes,
    publishing_status, scheduled_at, published_at,
    ...
)

-- team_workspaces
team_workspaces (id, name, owner_id, settings, ...)

-- team_members
team_members (id, team_id, user_id, role, invitation_token, ...)

-- content_analytics
content_analytics (id, content_id, action_type, platform, user_id, ...)

-- templates
templates (id, user_id, name, content_type_id, settings, is_public, ...)
```

---

## 16. Security & Guardrails

### Input Validation

```php
// GuardrailService validates input before AI processing
$validation = $guardrail->validateInput($input);

// Blocked keywords
'how to diagnose', 'what medication', 'prescribe me',
'dose for', 'dosage for', 'cure for', 'treatment for my'
```

### Output Filtering

```php
// Forbidden words in output
'diagnose', 'prescription', 'dosage', 'medication',
'guaranteed cure', 'self-medicate', 'self-diagnose'

// Forbidden patterns
'/take\s+\d+\s*(mg|ml|tablet)/i'
'/\d+\s*(mg|ml)\s+(of|daily|twice)/i'
'/your\s+diagnosis\s+is/i'

// Required elements
'educational purposes only' // Disclaimer
```

### Content Safety Flow

```
Input → Validate → Generate → Filter → Sanitize → 
Add Disclaimer → Return Safe Content
```

### Rate Limiting

```php
// Implemented via Laravel middleware
Route::middleware(['throttle:content-generation'])->group(function () {
    Route::post('/generate', [ContentController::class, 'generate']);
});
```

---

## Appendix

### Environment Variables

```env
# Application
APP_NAME="AI Medical Content"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=AI_Medical_Content_Generator
DB_USERNAME=root
DB_PASSWORD=secret

# OpenAI
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=2000
OPENAI_TEMPERATURE=0.7

# Digistore24
DIGISTORE24_API_KEY=...
DIGISTORE24_IPN_SIGNATURE_KEY=...
DIGISTORE24_VENDOR_ID=...
DIGISTORE24_SANDBOX=false

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

### Artisan Commands

```bash
# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Queue
php artisan queue:work

# Testing
php artisan test
php artisan test --filter=CreditServiceTest
```

---

<p align="center">
  <strong>AI Medical Content © 2026</strong><br>
  Documentation v1.0
</p>
