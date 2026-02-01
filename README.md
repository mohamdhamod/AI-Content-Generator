# AI Medical Content

<p align="center">
  <img src="public/images/logo.svg" alt="AI Medical Content Logo" width="120">
</p>

<p align="center">
  <strong>AI-Powered Medical Content Generation Platform</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#configuration">Configuration</a> •
  <a href="#usage">Usage</a> •
  <a href="#api">API</a>
</p>

---

## 🏥 About

**AI Medical Content** is a professional platform designed for healthcare professionals to create high-quality, medically accurate content quickly and efficiently. Powered by GPT-4o, it generates patient education materials, social media posts, SEO blog articles, and more.

## ✨ Features

### Content Generation
- 🤖 **AI-Powered**: Advanced GPT-4o model for high-quality medical content
- 📝 **8 Content Types**: Patient Education, What to Expect, SEO Blogs, Social Media, Google Review Replies, Email Follow-ups, Website FAQs, University Lectures
- 🏥 **4 Medical Specialties**: Dentistry, Dermatology, General Practice, Physiotherapy
- 🌍 **Multi-Language**: English, Arabic, German, Spanish, French

### Advanced Features
- 📊 **SEO Scoring**: Real-time SEO analysis with recommendations
- 📱 **Social Media Preview**: Live preview for Facebook, LinkedIn, Instagram, Twitter
- 📅 **Content Calendar**: Schedule and manage content publishing
- 👥 **Team Collaboration**: Invite team members, assign tasks, review workflow
- 📈 **Analytics Dashboard**: Track content performance and usage
- 🎨 **AI Refinement**: Enhance content with tone and style adjustments
- 📤 **Export Options**: PDF and PowerPoint export capabilities
- 💾 **Templates**: Save and reuse content templates

### Safety & Compliance
- ✅ Medical disclaimer requirements
- ✅ Non-diagnostic language guardrails
- ✅ Patient-friendly terminology
- ✅ Healthcare advertising compliance

## 🛠 Technical Stack

| Component | Technology |
|-----------|------------|
| Framework | Laravel 11.x |
| Frontend | Bootstrap 5, Vite, Blade |
| Database | MySQL 8.0 |
| AI Model | OpenAI GPT-4o |
| Payment | Digistore24 |
| Authentication | Laravel Fortify + Sanctum |
| Translations | Astrotomic Translatable |
| Permissions | Spatie Laravel Permission |

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer 2.x
- MySQL >= 8.0
- Node.js >= 18.x & NPM

### Quick Start

```bash
# Clone repository
git clone https://github.com/your-org/AI-Content-Generator.git
cd AI-Content-Generator

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Build assets
npm run build

# Database setup
php artisan migrate --seed

# Start server
php artisan serve
```

## ⚙️ Configuration

### Environment Variables

```env
# Application
APP_NAME="AI Medical Content"
APP_URL=http://localhost

# Database
DB_DATABASE=AI_Medical_Content_Generator
DB_USERNAME=root
DB_PASSWORD=your_password

# OpenAI (Required)
OPENAI_API_KEY=sk-your-api-key
OPENAI_MODEL=gpt-4o

# Digistore24 Payment (Required for subscriptions)
DIGISTORE24_API_KEY=your_api_key
DIGISTORE24_IPN_SIGNATURE_KEY=your_signature_key
DIGISTORE24_VENDOR_ID=your_vendor_id

# Email (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
```

### Digistore24 Setup

1. Create vendor account at [digistore24.com](https://www.digistore24.com)
2. Create products for each subscription plan
3. Configure IPN webhook URL: `https://your-domain.com/webhooks/digistore24`
4. Add product IDs in admin panel

## 💰 Subscription Plans

| Plan | Price | Credits/Month | Features |
|------|-------|---------------|----------|
| **Free** | $0 | 5 | Basic content types |
| **Professional** | $49 | 100 | All features, all specialties |
| **Clinic Plus** | $99 | 500 | Priority support, team collaboration |
| **Enterprise** | $299 | 5,000 | API access, dedicated support |

## 👥 User Roles

| Role | Permissions |
|------|-------------|
| **Admin** | Full system access, user management, settings |
| **Manager** | Content management, team oversight |
| **Subscriber** | Content generation, personal templates |

## 🔌 API Documentation

### Authentication

```bash
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

### Generate Content

```bash
POST /api/v1/content/generate
Authorization: Bearer {token}
Content-Type: application/json

{
  "content_type_id": 1,
  "specialty_id": 1,
  "topic_id": 1,
  "language": "en",
  "word_count": 500
}
```

### Webhooks

#### Digistore24 IPN
```
POST /webhooks/digistore24
```

Events handled:
- `on_payment` - New/renewed subscription
- `on_payment_missed` - Payment failed
- `on_refund` - Subscription cancelled
- `on_chargeback` - Payment disputed

## 📁 Project Structure

```
AI-Content-Generator/
├── app/
│   ├── Http/Controllers/     # Web & API controllers
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic services
│   │   ├── ContentGeneratorService.php
│   │   ├── CreditService.php
│   │   ├── Digistore24Service.php
│   │   ├── GuardrailService.php
│   │   ├── OpenAIService.php
│   │   └── SeoScoringService.php
│   └── ...
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Data seeders
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   ├── lang/                 # Translations
│   └── views/                # Blade templates
├── routes/
│   ├── api.php               # API routes
│   ├── web.php               # Web routes
│   └── dashboard.php         # Admin routes
└── tests/                    # Unit & Feature tests
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=CreditServiceTest
php artisan test --filter=GuardrailServiceTest

# With coverage
php artisan test --coverage
```

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up SSL certificate
- [ ] Configure Digistore24 webhook URL
- [ ] Set up queue worker for background jobs
- [ ] Configure email settings
- [ ] Run `npm run build` for optimized assets
- [ ] Run `php artisan optimize`

## 📄 License

Proprietary Software - All Rights Reserved © 2026

## 📞 Support

- **Email**: support@aimedicalcontent.com
- **Documentation**: [docs.aimedicalcontent.com](https://docs.aimedicalcontent.com)

---

<p align="center">
  Made with ❤️ for Healthcare Professionals
</p>
