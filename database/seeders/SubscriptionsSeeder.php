<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;

class SubscriptionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'price' => 0,
                'currency' => 'USD',
                'duration_months' => 1,
                'max_content_generations' => 5,
                'digistore_product_id' => null,
                'digistore_checkout_url' => null,
                'sort_order' => 1,
                'name' => [
                    'en' => 'Free Trial',
                    'ar' => 'تجربة مجانية',
                    'de' => 'Kostenlose Testversion',
                ],
                'description' => [
                    'en' => 'Perfect for trying our AI medical content generator. Limited to 5 content generations per month.',
                    'ar' => 'مثالية لتجربة مولد المحتوى الطبي بالذكاء الاصطناعي. محدودة بـ 5 عمليات توليد محتوى شهرياً.',
                    'de' => 'Perfekt zum Ausprobieren unseres KI-Generators für medizinische Inhalte. Begrenzt auf 5 Inhaltsgenerierungen pro Monat.',
                ],
                'features' => [
                    ['text' => ['en' => '5 content generations/month', 'ar' => '5 عمليات توليد محتوى شهرياً', 'de' => '5 Inhaltsgenerierungen/Monat'], 'icon' => 'bi-lightning', 'highlighted' => false],
                    ['text' => ['en' => '2 content types', 'ar' => 'نوعان من المحتوى', 'de' => '2 Inhaltstypen'], 'icon' => 'bi-file-text', 'highlighted' => false],
                    ['text' => ['en' => '2 medical specialties', 'ar' => 'تخصصان طبيان', 'de' => '2 medizinische Fachgebiete'], 'icon' => 'bi-hospital', 'highlighted' => false],
                    ['text' => ['en' => 'Basic support', 'ar' => 'دعم أساسي', 'de' => 'Basis-Support'], 'icon' => 'bi-headset', 'highlighted' => false],
                ],
            ],
            [
                'id' => 2,
                'price' => 49.00,
                'currency' => 'USD',
                'duration_months' => 1,
                'max_content_generations' => 100,
                'digistore_product_id' => null,
                'digistore_checkout_url' => null,
                'sort_order' => 2,
                'name' => [
                    'en' => 'Professional',
                    'ar' => 'الاحترافية',
                    'de' => 'Professionell',
                ],
                'description' => [
                    'en' => 'Ideal for small clinics. Generate up to 100 pieces of medical content monthly.',
                    'ar' => 'مثالية للعيادات الصغيرة. توليد حتى 100 محتوى طبي شهرياً.',
                    'de' => 'Ideal für kleine Praxen. Generieren Sie bis zu 100 medizinische Inhalte monatlich.',
                ],
                'features' => [
                    ['text' => ['en' => '100 content generations/month', 'ar' => '100 عملية توليد محتوى شهرياً', 'de' => '100 Inhaltsgenerierungen/Monat'], 'icon' => 'bi-lightning-fill', 'highlighted' => true],
                    ['text' => ['en' => 'All content types', 'ar' => 'جميع أنواع المحتوى', 'de' => 'Alle Inhaltstypen'], 'icon' => 'bi-files', 'highlighted' => false],
                    ['text' => ['en' => 'All medical specialties', 'ar' => 'جميع التخصصات الطبية', 'de' => 'Alle medizinischen Fachgebiete'], 'icon' => 'bi-hospital', 'highlighted' => false],
                    ['text' => ['en' => '5 languages', 'ar' => '5 لغات', 'de' => '5 Sprachen'], 'icon' => 'bi-translate', 'highlighted' => false],
                    ['text' => ['en' => 'Email support', 'ar' => 'دعم بالبريد الإلكتروني', 'de' => 'E-Mail-Support'], 'icon' => 'bi-envelope', 'highlighted' => false],
                ],
            ],
            [
                'id' => 3,
                'price' => 99.00,
                'currency' => 'USD',
                'duration_months' => 1,
                'max_content_generations' => 500,
                'digistore_product_id' => null,
                'digistore_checkout_url' => null,
                'sort_order' => 3,
                'name' => [
                    'en' => 'Clinic Plus',
                    'ar' => 'العيادة بلس',
                    'de' => 'Praxis Plus',
                ],
                'description' => [
                    'en' => 'For busy clinics and medical groups. Priority support and custom branding.',
                    'ar' => 'للعيادات المشغولة والمجموعات الطبية. دعم أولوية وعلامة تجارية مخصصة.',
                    'de' => 'Für vielbeschäftigte Praxen und medizinische Gruppen. Prioritäts-Support und individuelle Branding.',
                ],
                'features' => [
                    ['text' => ['en' => '500 content generations/month', 'ar' => '500 عملية توليد محتوى شهرياً', 'de' => '500 Inhaltsgenerierungen/Monat'], 'icon' => 'bi-lightning-fill', 'highlighted' => true],
                    ['text' => ['en' => 'All content types', 'ar' => 'جميع أنواع المحتوى', 'de' => 'Alle Inhaltstypen'], 'icon' => 'bi-files', 'highlighted' => false],
                    ['text' => ['en' => 'All medical specialties', 'ar' => 'جميع التخصصات الطبية', 'de' => 'Alle medizinischen Fachgebiete'], 'icon' => 'bi-hospital', 'highlighted' => false],
                    ['text' => ['en' => '5 languages', 'ar' => '5 لغات', 'de' => '5 Sprachen'], 'icon' => 'bi-translate', 'highlighted' => false],
                    ['text' => ['en' => 'Priority support', 'ar' => 'دعم أولوية', 'de' => 'Prioritäts-Support'], 'icon' => 'bi-star-fill', 'highlighted' => true],
                    ['text' => ['en' => 'Custom branding', 'ar' => 'علامة تجارية مخصصة', 'de' => 'Individuelles Branding'], 'icon' => 'bi-palette', 'highlighted' => false],
                ],
            ],
            [
                'id' => 4,
                'price' => 299.00,
                'currency' => 'USD',
                'duration_months' => 1,
                'max_content_generations' => 5000,
                'digistore_product_id' => null,
                'digistore_checkout_url' => null,
                'sort_order' => 4,
                'name' => [
                    'en' => 'Enterprise',
                    'ar' => 'المؤسسات',
                    'de' => 'Enterprise',
                ],
                'description' => [
                    'en' => 'High-volume content generation for hospital networks and large organizations.',
                    'ar' => 'توليد محتوى بكميات كبيرة لشبكات المستشفيات والمنظمات الكبيرة.',
                    'de' => 'Hochvolumige Inhaltsgenerierung für Krankenhausnetzwerke und große Organisationen.',
                ],
                'features' => [
                    ['text' => ['en' => '5,000 content generations/month', 'ar' => '5,000 عملية توليد محتوى شهرياً', 'de' => '5.000 Inhaltsgenerierungen/Monat'], 'icon' => 'bi-lightning-fill', 'highlighted' => true],
                    ['text' => ['en' => 'All content types', 'ar' => 'جميع أنواع المحتوى', 'de' => 'Alle Inhaltstypen'], 'icon' => 'bi-files', 'highlighted' => false],
                    ['text' => ['en' => 'All medical specialties', 'ar' => 'جميع التخصصات الطبية', 'de' => 'Alle medizinischen Fachgebiete'], 'icon' => 'bi-hospital', 'highlighted' => false],
                    ['text' => ['en' => 'All languages', 'ar' => 'جميع اللغات', 'de' => 'Alle Sprachen'], 'icon' => 'bi-translate', 'highlighted' => false],
                    ['text' => ['en' => 'Priority support', 'ar' => 'دعم أولوية', 'de' => 'Prioritäts-Support'], 'icon' => 'bi-star-fill', 'highlighted' => true],
                    ['text' => ['en' => 'Custom branding', 'ar' => 'علامة تجارية مخصصة', 'de' => 'Individuelles Branding'], 'icon' => 'bi-palette', 'highlighted' => false],
                    ['text' => ['en' => 'API access', 'ar' => 'الوصول إلى API', 'de' => 'API-Zugang'], 'icon' => 'bi-code-slash', 'highlighted' => true],
                    ['text' => ['en' => 'Dedicated account manager', 'ar' => 'مدير حساب مخصص', 'de' => 'Dedizierter Account Manager'], 'icon' => 'bi-person-badge', 'highlighted' => false],
                ],
            ],
        ];

        foreach ($data as $item) {
            $features = $item['features'] ?? [];
            unset($item['features']);

            $model = Subscription::updateOrCreate(
                ['id' => $item['id']],
                [
                    'price' => $item['price'],
                    'currency' => $item['currency'],
                    'duration_months' => $item['duration_months'],
                    'max_content_generations' => $item['max_content_generations'],
                    'digistore_product_id' => $item['digistore_product_id'],
                    'digistore_checkout_url' => $item['digistore_checkout_url'],
                    'sort_order' => $item['sort_order'],
                    'active' => 1,
                ]
            );

            foreach (['name', 'description'] as $field) {
                foreach ($item[$field] as $locale => $translation) {
                    $model->translateOrNew($locale)->{$field} = $translation;
                }
            }

            $model->save();

            // Seed features
            $sortOrder = 1;
            foreach ($features as $featureData) {
                $feature = SubscriptionFeature::updateOrCreate(
                    [
                        'subscription_id' => $model->id,
                        'sort_order' => $sortOrder,
                    ],
                    [
                        'icon' => $featureData['icon'] ?? 'bi-check',
                        'is_highlighted' => $featureData['highlighted'] ?? false,
                        'active' => true,
                    ]
                );

                foreach ($featureData['text'] as $locale => $text) {
                    $feature->translateOrNew($locale)->feature_text = $text;
                }
                $feature->save();

                $sortOrder++;
            }
        }
    }
}
