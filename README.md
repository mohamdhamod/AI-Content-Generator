# AI Medical Content Generator

A professional AI-powered medical content generation platform built with Laravel. Generate patient education materials, social media posts, SEO blog articles, and more for healthcare practices.

## About The Project

AI Medical Content Generator is an intelligent platform designed for healthcare professionals to create high-quality, medically accurate content quickly and efficiently.

### Key Features

- **AI-Powered Content Generation**: Generate professional medical content using advanced AI
- **Multiple Medical Specialties**: Dentistry, Dermatology, General Practice, Physiotherapy
- **7 Content Types**: Patient Education, What to Expect, SEO Blog Articles, Social Media Posts, Google Review Replies, Email Follow-ups, Website FAQs
- **Multi-Language Support**: English, Arabic, German, Spanish, French
- **Subscription Management**: Integrated with Digistore24 for subscription billing
- **Safety First**: Built-in compliance with healthcare communication standards

### Content Types

| Type | Description |
|------|-------------|
| Patient Education | Educational handouts for patients about procedures and conditions |
| What to Expect | Pre-procedure information to prepare patients |
| SEO Blog Article | Search-optimized articles for practice websites |
| Social Media Post | Engaging posts for Facebook, Instagram, LinkedIn, TikTok |
| Google Review Reply | Professional responses to patient reviews |
| Email Follow-up | Post-appointment communication templates |
| Website FAQ | Frequently asked questions for practice websites |

### Medical Specialties

- **Dentistry**: Teeth whitening, implants, orthodontics, root canals, and more
- **Dermatology**: Acne treatment, skin cancer awareness, anti-aging, eczema care
- **General Clinic**: Health checkups, vaccinations, chronic disease management
- **Physiotherapy**: Back pain relief, sports injuries, rehabilitation, posture improvement

## Technical Stack

- **Framework**: Laravel 11.x
- **Frontend**: Bootstrap 5, Blade Templates
- **Database**: MySQL
- **Payment**: Digistore24 IPN Integration
- **Authentication**: Laravel Fortify with JWT
- **Multi-Language**: Astrotomic Translatable
- **Permissions**: Spatie Laravel Permission

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js & NPM

### Installation Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd AI-Content-Generator
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install NPM dependencies**
```bash
npm install
npm run build
```

4. **Environment Setup**
```bash
copy .env.example .env
php artisan key:generate
```

5. **Configure Database**

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=AI_Medical_Content_Generator
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Configure Digistore24**

Add to `.env`:
```env
DIGISTORE24_IPN_SIGNATURE_KEY=your_signature_key
DIGISTORE24_API_KEY=your_api_key
```

7. **Run Migrations**
```bash
php artisan migrate
```

8. **Seed Database**
```bash
php artisan db:seed
```

9. **Start Development Server**
```bash
php artisan serve
```

## Subscription Plans

| Plan | Price | Content/Month | Features |
|------|-------|---------------|----------|
| Free Trial | €0 | 10 | 2 content types, 2 specialties |
| Professional | €29 | 100 | All content types, all specialties |
| Clinic Plus | €79 | 500 | Priority support, custom branding |
| Enterprise | €199 | Unlimited | API access, dedicated support |

## User Roles

- **Admin**: Full system access
- **Manager**: Content management, user oversight
- **Subscriber**: Content generation access

## API Documentation

### Digistore24 Webhook

```
POST /api/digistore24/ipn
```

Handles subscription events:
- `on_payment` - New subscription
- `on_payment_missed` - Payment failed
- `on_refund` - Subscription cancelled

## Safety & Compliance

All generated content includes:
- Medical disclaimer requirements
- Non-diagnostic language
- Patient-friendly terminology
- Compliance with healthcare advertising standards

## License

Proprietary Software - All Rights Reserved

## Support

For support, email support@medical-ai-content.com
