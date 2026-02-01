<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use App\Models\ConfigTitle;
use Illuminate\Database\Seeder;


class ConfigTitleTextSeeder extends Seeder
{
    public function run()
    {
        $list = [

            [
                'id' => 1,
                'title' => [
                    'en' => 'Copyright',
                    'ar' => 'حقوق النشر',
                    'fr' => 'Droits d\'auteur',
                    'es' => 'Derechos de autor',
                    'de' => 'Urheberrecht',
                ],
                'description' => [
                    'en' => 'Copyright: AI Medical Content',
                    'ar' => 'حقوق النشر: مولد المحتوى الطبي بالذكاء الاصطناعي',
                    'fr' => 'Droits d\'auteur : Générateur de contenu médical IA',
                    'es' => 'Derechos de autor: Generador de contenido médico IA',
                    'de' => 'Urheberrecht: KI-Medizinischer Content-Generator',
                ],
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::COPYRIGHT,
            ],

            // About Us page content
            [
                'id' => 2,
                'title' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                    'fr' => 'À propos de nous',
                    'es' => 'Sobre nosotros',
                    'de' => 'Über uns',
                ],
                'description' => [
                    'en' => 'AI Medical Content is your intelligent partner for creating professional healthcare content. Generate patient education materials, social media posts, SEO articles, and more for your dental, dermatology, or general practice.',
                    'ar' => 'مولد المحتوى الطبي بالذكاء الاصطناعي هو شريكك الذكي لإنشاء محتوى رعاية صحية احترافي. أنشئ مواد تثقيفية للمرضى ومنشورات وسائل التواصل الاجتماعي ومقالات SEO والمزيد لعيادتك.',
                    'fr' => 'Le générateur de contenu médical IA est votre partenaire intelligent pour créer du contenu de santé professionnel. Générez des matériaux éducatifs pour patients, des publications sur les réseaux sociaux, des articles SEO et plus encore.',
                    'es' => 'El generador de contenido médico con IA es su socio inteligente para crear contenido de salud profesional. Genere materiales educativos para pacientes, publicaciones en redes sociales, artículos SEO y más.',
                    'de' => 'Der KI-Medizinische Content-Generator ist Ihr intelligenter Partner für die Erstellung professioneller Gesundheitsinhalte. Erstellen Sie Patientenaufklärungsmaterialien, Social-Media-Beiträge, SEO-Artikel und mehr.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HERO,
            ],
            [
                'id' => 3,
                'title' => [
                    'en' => 'About AI Medical Content',
                    'ar' => 'عن مولد المحتوى الطبي',
                    'fr' => 'À propos du générateur médical IA',
                    'es' => 'Sobre el generador de contenido médico',
                    'de' => 'Über den KI-Medical-Generator',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_TITLE,
            ],
            [
                'id' => 4,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'Our AI-powered platform helps healthcare professionals create engaging, accurate content in minutes. From patient education handouts to social media campaigns, we provide specialty-specific prompts that maintain medical accuracy while being patient-friendly.',
                    'ar' => 'تساعد منصتنا المدعومة بالذكاء الاصطناعي المتخصصين في الرعاية الصحية على إنشاء محتوى جذاب ودقيق في دقائق. من نشرات تثقيف المرضى إلى حملات وسائل التواصل الاجتماعي، نقدم قوالب خاصة بكل تخصص.',
                    'fr' => 'Notre plateforme alimentée par l\'IA aide les professionnels de la santé à créer un contenu engageant et précis en quelques minutes. Des brochures d\'éducation des patients aux campagnes sur les réseaux sociaux.',
                    'es' => 'Nuestra plataforma impulsada por IA ayuda a los profesionales de la salud a crear contenido atractivo y preciso en minutos. Desde folletos educativos para pacientes hasta campañas en redes sociales.',
                    'de' => 'Unsere KI-gestützte Plattform hilft Gesundheitsfachleuten, in wenigen Minuten ansprechende, genaue Inhalte zu erstellen. Von Patientenaufklärungsbroschüren bis hin zu Social-Media-Kampagnen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_1,
            ],
            [
                'id' => 5,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'We believe that every medical practice deserves professional content without the complexity or high costs of traditional content creation.',
                    'ar' => 'نؤمن بأن كل عيادة طبية تستحق محتوى احترافي دون تعقيد أو تكاليف عالية.',
                    'fr' => 'Nous croyons que chaque cabinet médical mérite un contenu professionnel sans la complexité ou les coûts élevés de la création de contenu traditionnelle.',
                    'es' => 'Creemos que cada consultorio médico merece contenido profesional sin la complejidad ni los altos costos de la creación de contenido tradicional.',
                    'de' => 'Wir glauben, dass jede medizinische Praxis professionelle Inhalte verdient, ohne die Komplexität oder hohen Kosten der traditionellen Content-Erstellung.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_2,
            ],
            [
                'id' => 6,
                'title' => [
                    'en' => 'AI-Powered Medical Content Creation',
                    'ar' => 'إنشاء محتوى طبي بالذكاء الاصطناعي',
                    'fr' => 'Création de contenu médical par IA',
                    'es' => 'Creación de contenido médico con IA',
                    'de' => 'KI-gestützte medizinische Content-Erstellung',
                ],
                'description' => [
                    'en' => 'Generate patient education, social media posts, SEO articles, Google review replies, and more — all tailored to your medical specialty.',
                    'ar' => 'أنشئ مواد تثقيفية للمرضى ومنشورات وسائل التواصل الاجتماعي ومقالات SEO وردود على مراجعات Google والمزيد — كلها مصممة لتخصصك الطبي.',
                    'fr' => 'Générez des documents éducatifs pour les patients, des publications sur les réseaux sociaux, des articles SEO, des réponses aux avis Google et plus encore — le tout adapté à votre spécialité médicale.',
                    'es' => 'Genere materiales educativos para pacientes, publicaciones en redes sociales, artículos SEO, respuestas a reseñas de Google y más — todo adaptado a su especialidad médica.',
                    'de' => 'Erstellen Sie Patientenaufklärung, Social-Media-Beiträge, SEO-Artikel, Google-Bewertungsantworten und mehr — alles auf Ihre medizinische Fachrichtung zugeschnitten.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HIGHLIGHT,
            ],
            [
                'id' => 7,
                'title' => [
                    'en' => 'What We Offer',
                    'ar' => 'ماذا نقدم',
                    'fr' => 'Ce que nous offrons',
                    'es' => 'Lo que ofrecemos',
                    'de' => 'Was wir anbieten',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_OFFER_TITLE,
            ],
            [
                'id' => 8,
                'title' => [
                    'en' => 'Our Vision',
                    'ar' => 'رؤيتنا',
                    'fr' => 'Notre vision',
                    'es' => 'Nuestra visión',
                    'de' => 'Unsere Vision',
                ],
                'description' => [
                    'en' => 'To empower every healthcare provider with AI-driven tools that make professional content creation accessible, efficient, and medically accurate.',
                    'ar' => 'تمكين كل مقدم رعاية صحية بأدوات مدعومة بالذكاء الاصطناعي تجعل إنشاء المحتوى الاحترافي متاحًا وفعالًا ودقيقًا طبيًا.',
                    'fr' => 'Donner à chaque prestataire de soins de santé les moyens d\'utiliser des outils pilotés par l\'IA qui rendent la création de contenu professionnel accessible, efficace et médicalement précise.',
                    'es' => 'Empoderar a cada proveedor de atención médica con herramientas impulsadas por IA que hacen que la creación de contenido profesional sea accesible, eficiente y médicamente precisa.',
                    'de' => 'Jeden Gesundheitsdienstleister mit KI-gesteuerten Tools auszustatten, die professionelle Content-Erstellung zugänglich, effizient und medizinisch korrekt machen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_VISION,
            ],
            [
                'id' => 9,
                'title' => [
                    'en' => 'Our Mission',
                    'ar' => 'رسالتنا',
                    'fr' => 'Notre mission',
                    'es' => 'Nuestra misión',
                    'de' => 'Unsere Mission',
                ],
                'description' => [
                    'en' => 'To simplify medical content creation for healthcare professionals with specialty-specific prompts, multilingual support, and compliance with healthcare communication standards.',
                    'ar' => 'تبسيط إنشاء المحتوى الطبي للمتخصصين في الرعاية الصحية مع قوالب خاصة بكل تخصص ودعم متعدد اللغات والامتثال لمعايير الاتصال الصحي.',
                    'fr' => 'Simplifier la création de contenu médical pour les professionnels de la santé avec des modèles spécifiques à chaque spécialité, un support multilingue et le respect des normes de communication en santé.',
                    'es' => 'Simplificar la creación de contenido médico para profesionales de la salud con plantillas específicas de especialidad, soporte multilingüe y cumplimiento de los estándares de comunicación sanitaria.',
                    'de' => 'Die Erstellung medizinischer Inhalte für Gesundheitsfachleute zu vereinfachen, mit fachspezifischen Vorlagen, mehrsprachiger Unterstützung und Einhaltung der Kommunikationsstandards im Gesundheitswesen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_MISSION,
            ],
            [
                'id' => 10,
                'title' => [
                    'en' => 'Why AI Medical Content?',
                    'ar' => 'لماذا مولد المحتوى الطبي؟',
                    'fr' => 'Pourquoi le générateur de contenu médical IA?',
                    'es' => '¿Por qué el generador de contenido médico IA?',
                    'de' => 'Warum der KI-Medical-Content-Generator?',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_TITLE,
            ],
            [
                'id' => 11,
                'title' => [
                    'en' => 'Intuitive Interface',
                    'ar' => 'واجهة سهلة الاستخدام',
                    'fr' => 'Interface intuitive',
                    'es' => 'Interfaz intuitiva',
                    'de' => 'Intuitive Benutzeroberfläche',
                ],
                'description' => [
                    'en' => 'A clean, user-friendly interface that helps you select your specialty, content type, and generate professional content in seconds.',
                    'ar' => 'واجهة نظيفة وسهلة الاستخدام تساعدك على اختيار تخصصك ونوع المحتوى وإنشاء محتوى احترافي في ثوانٍ.',
                    'fr' => 'Une interface propre et conviviale qui vous aide à sélectionner votre spécialité, le type de contenu et à générer du contenu professionnel en quelques secondes.',
                    'es' => 'Una interfaz limpia y fácil de usar que le ayuda a seleccionar su especialidad, tipo de contenido y generar contenido profesional en segundos.',
                    'de' => 'Eine saubere, benutzerfreundliche Oberfläche, die Ihnen hilft, Ihre Fachrichtung und den Inhaltstyp auszuwählen und in Sekunden professionelle Inhalte zu erstellen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SIMPLE_UI,
            ],
            [
                'id' => 12,
                'title' => [
                    'en' => 'Specialty-Specific Prompts',
                    'ar' => 'قوالب خاصة بكل تخصص',
                    'fr' => 'Modèles spécifiques par spécialité',
                    'es' => 'Plantillas específicas por especialidad',
                    'de' => 'Fachspezifische Vorlagen',
                ],
                'description' => [
                    'en' => 'Pre-built prompts for dentistry, dermatology, general practice, physiotherapy, and more — ensuring medically accurate and relevant content.',
                    'ar' => 'قوالب جاهزة لطب الأسنان والأمراض الجلدية والطب العام والعلاج الطبيعي والمزيد — لضمان محتوى دقيق طبيًا وذي صلة.',
                    'fr' => 'Modèles prédéfinis pour la dentisterie, la dermatologie, la médecine générale, la physiothérapie et plus encore — garantissant un contenu médicalement précis et pertinent.',
                    'es' => 'Plantillas predefinidas para odontología, dermatología, medicina general, fisioterapia y más — asegurando contenido médicamente preciso y relevante.',
                    'de' => 'Vordefinierte Vorlagen für Zahnmedizin, Dermatologie, Allgemeinmedizin, Physiotherapie und mehr — für medizinisch korrekte und relevante Inhalte.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SMART_TOOLS,
            ],
            [
                'id' => 13,
                'title' => [
                    'en' => 'Professional Quality',
                    'ar' => 'جودة احترافية',
                    'fr' => 'Qualité professionnelle',
                    'es' => 'Calidad profesional',
                    'de' => 'Professionelle Qualität',
                ],
                'description' => [
                    'en' => 'Content that maintains medical accuracy while being patient-friendly, with built-in safety rules and compliance guidelines.',
                    'ar' => 'محتوى يحافظ على الدقة الطبية مع كونه ودودًا للمرضى، مع قواعد أمان مدمجة وإرشادات الامتثال.',
                    'fr' => 'Un contenu qui maintient la précision médicale tout en étant adapté aux patients, avec des règles de sécurité et des directives de conformité intégrées.',
                    'es' => 'Contenido que mantiene la precisión médica mientras es amigable para el paciente, con reglas de seguridad incorporadas y directrices de cumplimiento.',
                    'de' => 'Inhalte, die medizinische Genauigkeit bewahren und gleichzeitig patientenfreundlich sind, mit eingebauten Sicherheitsregeln und Compliance-Richtlinien.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY,
            ],
            [
                'id' => 14,
                'title' => [
                    'en' => 'For Clinics of All Sizes',
                    'ar' => 'لجميع أحجام العيادات',
                    'fr' => 'Pour les cliniques de toutes tailles',
                    'es' => 'Para clínicas de todos los tamaños',
                    'de' => 'Für Praxen aller Größen',
                ],
                'description' => [
                    'en' => 'Whether you\'re a solo practitioner or a multi-location clinic, our platform scales to meet your content needs.',
                    'ar' => 'سواء كنت طبيبًا منفردًا أو عيادة متعددة الفروع، منصتنا تتكيف لتلبية احتياجاتك من المحتوى.',
                    'fr' => 'Que vous soyez un praticien solo ou une clinique multi-sites, notre plateforme s\'adapte à vos besoins en contenu.',
                    'es' => 'Ya sea que sea un profesional independiente o una clínica con múltiples ubicaciones, nuestra plataforma se adapta a sus necesidades de contenido.',
                    'de' => 'Ob Einzelpraxis oder Klinik mit mehreren Standorten — unsere Plattform passt sich Ihren Content-Bedürfnissen an.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES,
            ],
            [
                'id' => 15,
                'title' => [
                    'en' => 'Continuous Support',
                    'ar' => 'دعم مستمر',
                    'fr' => 'Support continu',
                    'es' => 'Soporte continuo',
                    'de' => 'Kontinuierlicher Support',
                ],
                'description' => [
                    'en' => 'Regular updates with new specialties, content types, and language support. Our team is always here to help.',
                    'ar' => 'تحديثات منتظمة مع تخصصات جديدة وأنواع محتوى ودعم لغات جديدة. فريقنا موجود دائمًا للمساعدة.',
                    'fr' => 'Mises à jour régulières avec de nouvelles spécialités, types de contenu et support linguistique. Notre équipe est toujours là pour vous aider.',
                    'es' => 'Actualizaciones regulares con nuevas especialidades, tipos de contenido y soporte de idiomas. Nuestro equipo siempre está aquí para ayudar.',
                    'de' => 'Regelmäßige Updates mit neuen Fachrichtungen, Inhaltstypen und Sprachunterstützung. Unser Team ist immer für Sie da.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT,
            ],
            [
                'id' => 16,
                'title' => [
                    'en' => 'Start Creating Content Today',
                    'ar' => 'ابدأ إنشاء المحتوى اليوم',
                    'fr' => 'Commencez à créer du contenu aujourd\'hui',
                    'es' => 'Comience a crear contenido hoy',
                    'de' => 'Beginnen Sie heute mit der Content-Erstellung',
                ],
                'description' => [
                    'en' => 'Select your specialty, choose a content type, and let AI generate professional healthcare content for your practice.',
                    'ar' => 'اختر تخصصك، حدد نوع المحتوى، ودع الذكاء الاصطناعي ينشئ محتوى رعاية صحية احترافي لعيادتك.',
                    'fr' => 'Sélectionnez votre spécialité, choisissez un type de contenu et laissez l\'IA générer du contenu de santé professionnel pour votre cabinet.',
                    'es' => 'Seleccione su especialidad, elija un tipo de contenido y deje que la IA genere contenido de salud profesional para su consultorio.',
                    'de' => 'Wählen Sie Ihre Fachrichtung, wählen Sie einen Inhaltstyp und lassen Sie KI professionelle Gesundheitsinhalte für Ihre Praxis erstellen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA,
            ],
            [
                'id' => 17,
                'title' => [
                    'en' => 'Get Started Free',
                    'ar' => 'ابدأ مجانًا',
                    'fr' => 'Commencez gratuitement',
                    'es' => 'Comience gratis',
                    'de' => 'Kostenlos starten',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA_BUTTON,
            ],
            [
                'id' => 18,
                'title' => [
                    'en' => 'Contact',
                    'ar' => 'التواصل',
                    'fr' => 'Contact',
                    'es' => 'Contacto',
                    'de' => 'Kontakt',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_TITLE,
            ],
            [
                'id' => 19,
                'title' => [
                    'en' => 'Phone',
                    'ar' => 'الهاتف',
                    'fr' => 'Téléphone',
                    'es' => 'Teléfono',
                    'de' => 'Telefon',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_LABEL,
            ],
            [
                'id' => 20,
                'title' => [
                    'en' => 'Email',
                    'ar' => 'البريد الإلكتروني',
                    'fr' => 'E-mail',
                    'es' => 'Correo electrónico',
                    'de' => 'E-Mail',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_LABEL,
            ],
            [
                'id' => 21,
                'title' => [
                    'en' => 'Not available',
                    'ar' => 'غير متوفر',
                    'fr' => 'Non disponible',
                    'es' => 'No disponible',
                    'de' => 'Nicht verfügbar',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_NOT_AVAILABLE,
            ],
            [
                'id' => 22,
                'title' => [
                    'en' => 'Call by phone',
                    'ar' => 'اتصال هاتفي',
                    'fr' => 'Appeler par téléphone',
                    'es' => 'Llamar por teléfono',
                    'de' => 'Telefonisch anrufen',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_ARIA,
            ],
            [
                'id' => 23,
                'title' => [
                    'en' => 'Send email',
                    'ar' => 'إرسال بريد إلكتروني',
                    'fr' => 'Envoyer un e-mail',
                    'es' => 'Enviar correo electrónico',
                    'de' => 'E-Mail senden',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_ARIA,
            ],
            [
                'id' => 24,
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                    'fr' => 'Politique de confidentialité',
                    'es' => 'Política de privacidad',
                    'de' => 'Datenschutzrichtlinie',
                ],
                'description' => [
                    'en' => '<p>At AI Medical Content, we respect your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you use our medical content generation platform.</p>

<h5>Information We Collect:</h5>
<ul>
<li>Account information (name, email, phone number)</li>
<li>Practice information (clinic name, specialty, location)</li>
<li>Content generation history and preferences</li>
<li>Payment information (processed securely through Digistore24)</li>
<li>Device and usage information</li>
</ul>

<h5>How We Use Your Information:</h5>
<ul>
<li>To provide and improve our content generation services</li>
<li>To personalize content recommendations based on your specialty</li>
<li>To communicate with you about your account and services</li>
<li>To process subscriptions and payments through Digistore24</li>
<li>To send updates about new features and content types (with your consent)</li>
</ul>

<h5>Data Protection:</h5>
<p>We implement appropriate security measures to protect your personal and practice information against unauthorized access, alteration, disclosure, or destruction. All data transmission is encrypted using SSL/TLS protocols.</p>

<h5>Healthcare Content Disclaimer:</h5>
<p>The content generated by our platform is intended for educational and marketing purposes only. It should be reviewed by qualified healthcare professionals before publication and does not constitute medical advice, diagnosis, or treatment recommendations.</p>

<h5>Your Rights:</h5>
<p>You have the right to access, correct, or delete your personal information. You can also export your generated content history at any time. Contact us to exercise these rights.</p>

<h5>Contact Us:</h5>
<p>If you have any questions about this Privacy Policy, please contact our support team.</p>',
                    'ar' => '<p>في مولد المحتوى الطبي بالذكاء الاصطناعي، نحترم خصوصيتك ونلتزم بحماية معلوماتك الشخصية. توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية بياناتك عند استخدام منصتنا لإنشاء المحتوى الطبي.</p>

<h5>المعلومات التي نجمعها:</h5>
<ul>
<li>معلومات الحساب (الاسم، البريد الإلكتروني، رقم الهاتف)</li>
<li>معلومات الممارسة (اسم العيادة، التخصص، الموقع)</li>
<li>سجل إنشاء المحتوى والتفضيلات</li>
<li>معلومات الدفع (تتم معالجتها بشكل آمن عبر Digistore24)</li>
<li>معلومات الجهاز والاستخدام</li>
</ul>

<h5>كيف نستخدم معلوماتك:</h5>
<ul>
<li>لتقديم وتحسين خدمات إنشاء المحتوى</li>
<li>لتخصيص توصيات المحتوى بناءً على تخصصك</li>
<li>للتواصل معك بشأن حسابك وخدماتك</li>
<li>لمعالجة الاشتراكات والمدفوعات عبر Digistore24</li>
<li>لإرسال تحديثات حول الميزات وأنواع المحتوى الجديدة (بموافقتك)</li>
</ul>

<h5>حماية البيانات:</h5>
<p>نطبق إجراءات أمنية مناسبة لحماية معلوماتك الشخصية ومعلومات ممارستك ضد الوصول غير المصرح به أو التعديل أو الكشف أو التدمير.</p>

<h5>إخلاء المسؤولية عن المحتوى الصحي:</h5>
<p>المحتوى الذي تنتجه منصتنا مخصص للأغراض التعليمية والتسويقية فقط. يجب مراجعته من قبل متخصصين مؤهلين قبل النشر ولا يشكل نصيحة طبية أو تشخيصًا أو توصيات علاجية.</p>

<h5>حقوقك:</h5>
<p>لديك الحق في الوصول إلى معلوماتك الشخصية أو تصحيحها أو حذفها. تواصل معنا لممارسة هذه الحقوق.</p>

<h5>تواصل معنا:</h5>
<p>إذا كانت لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى التواصل مع فريق الدعم.</p>',
                    'fr' => '<p>Chez AI Medical Content, nous respectons votre vie privée et nous nous engageons à protéger vos informations personnelles. Cette politique de confidentialité explique comment nous collectons, utilisons et protégeons vos données.</p>

<h5>Informations que nous collectons:</h5>
<ul>
<li>Informations de compte (nom, email, numéro de téléphone)</li>
<li>Informations de pratique (nom de la clinique, spécialité, emplacement)</li>
<li>Historique de génération de contenu et préférences</li>
<li>Informations de paiement (traitées de manière sécurisée via Digistore24)</li>
</ul>

<h5>Comment nous utilisons vos informations:</h5>
<ul>
<li>Pour fournir et améliorer nos services de génération de contenu</li>
<li>Pour personnaliser les recommandations de contenu selon votre spécialité</li>
<li>Pour traiter les abonnements et paiements via Digistore24</li>
</ul>

<h5>Avertissement sur le contenu de santé:</h5>
<p>Le contenu généré par notre plateforme est destiné uniquement à des fins éducatives et marketing et ne constitue pas un avis médical.</p>

<h5>Nous contacter:</h5>
<p>Si vous avez des questions, veuillez contacter notre équipe d\'assistance.</p>',
                    'es' => '<p>En AI Medical Content, respetamos su privacidad y nos comprometemos a proteger su información personal. Esta Política de Privacidad explica cómo recopilamos, usamos y protegemos sus datos.</p>

<h5>Información que recopilamos:</h5>
<ul>
<li>Información de cuenta (nombre, correo electrónico, número de teléfono)</li>
<li>Información de práctica (nombre de clínica, especialidad, ubicación)</li>
<li>Historial de generación de contenido y preferencias</li>
<li>Información de pago (procesada de forma segura a través de Digistore24)</li>
</ul>

<h5>Cómo usamos su información:</h5>
<ul>
<li>Para proporcionar y mejorar nuestros servicios de generación de contenido</li>
<li>Para personalizar recomendaciones de contenido según su especialidad</li>
<li>Para procesar suscripciones y pagos a través de Digistore24</li>
</ul>

<h5>Aviso sobre contenido de salud:</h5>
<p>El contenido generado por nuestra plataforma está destinado únicamente a fines educativos y de marketing y no constituye asesoramiento médico.</p>

<h5>Contáctenos:</h5>
<p>Si tiene alguna pregunta, comuníquese con nuestro equipo de soporte.</p>',
                    'de' => '<p>Bei AI Medical Content respektieren wir Ihre Privatsphäre und verpflichten uns, Ihre persönlichen Daten zu schützen. Diese Datenschutzrichtlinie erklärt, wie wir Ihre Daten erheben, verwenden und schützen.</p>

<h5>Informationen, die wir erheben:</h5>
<ul>
<li>Kontoinformationen (Name, E-Mail, Telefonnummer)</li>
<li>Praxisinformationen (Klinikname, Fachrichtung, Standort)</li>
<li>Content-Generierungsverlauf und Präferenzen</li>
<li>Zahlungsinformationen (sicher verarbeitet über Digistore24)</li>
</ul>

<h5>Wie wir Ihre Informationen verwenden:</h5>
<ul>
<li>Um unsere Content-Generierungsdienste bereitzustellen und zu verbessern</li>
<li>Um Content-Empfehlungen basierend auf Ihrer Fachrichtung zu personalisieren</li>
<li>Um Abonnements und Zahlungen über Digistore24 zu verarbeiten</li>
</ul>

<h5>Haftungsausschluss für Gesundheitsinhalte:</h5>
<p>Der von unserer Plattform generierte Inhalt dient nur zu Bildungs- und Marketingzwecken und stellt keine medizinische Beratung dar.</p>

<h5>Kontaktieren Sie uns:</h5>
<p>Bei Fragen kontaktieren Sie bitte unser Support-Team.</p>',
                ],
                'page' => ConfigEnum::PRIVACY_POLICY,
                'key' => ConfigEnum::PRIVACY_POLICY,
            ],
            [
                'id' => 25,
                'title' => [
                    'en' => 'Terms, Conditions and Agreements',
                    'ar' => 'الشروط والأحكام والاتفاقيات',
                    'fr' => 'Termes, conditions et accords',
                    'es' => 'Términos, condiciones y acuerdos',
                    'de' => 'Allgemeine Geschäftsbedingungen',
                ],
                'description' => [
                    'en' => '<p>Welcome to AI Medical Content. By using our platform for generating medical and healthcare content, you agree to the following terms and conditions.</p>

<h5>1. Acceptance of Terms</h5>
<p>By accessing or using our services, you agree to be bound by these Terms and Conditions. If you do not agree, please do not use our platform.</p>

<h5>2. User Account</h5>
<ul>
<li>You must provide accurate and complete information when creating an account</li>
<li>You are responsible for maintaining the confidentiality of your account credentials</li>
<li>You must be a licensed healthcare professional or authorized representative of a healthcare practice to use certain features</li>
</ul>

<h5>3. Subscriptions and Payments</h5>
<ul>
<li>All subscriptions are processed through Digistore24</li>
<li>Subscription fees are billed in advance on a monthly or annual basis</li>
<li>Prices are subject to change with 30 days notice</li>
<li>Refund policies are governed by Digistore24\'s terms</li>
</ul>

<h5>4. Content Generation and Usage</h5>
<ul>
<li>Generated content is provided for educational and marketing purposes only</li>
<li>You are responsible for reviewing and verifying all generated content before publication</li>
<li>Content should not be used as medical advice, diagnosis, or treatment recommendations</li>
<li>You retain rights to content you generate, but grant us license to improve our services</li>
</ul>

<h5>5. Healthcare Content Disclaimer</h5>
<ul>
<li>All generated content must be reviewed by qualified healthcare professionals</li>
<li>We do not guarantee medical accuracy of generated content</li>
<li>You are solely responsible for ensuring compliance with healthcare regulations in your jurisdiction</li>
<li>Generated content should include appropriate disclaimers when published</li>
</ul>

<h5>6. User Conduct</h5>
<ul>
<li>Users must not misuse the platform or generate harmful content</li>
<li>You must not use the platform to generate false medical claims</li>
<li>We reserve the right to suspend accounts that violate these terms</li>
</ul>

<h5>7. Limitation of Liability</h5>
<p>AI Medical Content is not liable for any damages arising from the use of generated content. Healthcare providers are responsible for the accuracy and appropriateness of content they publish.</p>

<h5>8. Changes to Terms</h5>
<p>We reserve the right to modify these terms at any time. Continued use of our services constitutes acceptance of modified terms.</p>

<h5>Contact Us:</h5>
<p>For questions about these terms, please contact our support team.</p>',
                    'ar' => '<p>مرحباً بك في مولد المحتوى الطبي بالذكاء الاصطناعي. باستخدام منصتنا لإنشاء محتوى طبي ورعاية صحية، فإنك توافق على الشروط والأحكام التالية.</p>

<h5>1. قبول الشروط</h5>
<p>من خلال الوصول إلى خدماتنا أو استخدامها، فإنك توافق على الالتزام بهذه الشروط والأحكام.</p>

<h5>2. حساب المستخدم</h5>
<ul>
<li>يجب تقديم معلومات دقيقة وكاملة عند إنشاء حساب</li>
<li>أنت مسؤول عن الحفاظ على سرية بيانات حسابك</li>
<li>يجب أن تكون متخصصًا مرخصًا في الرعاية الصحية لاستخدام ميزات معينة</li>
</ul>

<h5>3. الاشتراكات والمدفوعات</h5>
<ul>
<li>تتم معالجة جميع الاشتراكات عبر Digistore24</li>
<li>يتم دفع رسوم الاشتراك مقدمًا على أساس شهري أو سنوي</li>
<li>الأسعار قابلة للتغيير مع إشعار مسبق بـ 30 يومًا</li>
</ul>

<h5>4. إنشاء المحتوى واستخدامه</h5>
<ul>
<li>يتم توفير المحتوى المُنشأ للأغراض التعليمية والتسويقية فقط</li>
<li>أنت مسؤول عن مراجعة والتحقق من كل المحتوى المُنشأ قبل النشر</li>
<li>لا ينبغي استخدام المحتوى كنصيحة طبية أو تشخيص أو توصيات علاجية</li>
</ul>

<h5>5. إخلاء المسؤولية عن المحتوى الصحي</h5>
<ul>
<li>يجب مراجعة كل المحتوى المُنشأ من قبل متخصصين مؤهلين</li>
<li>نحن لا نضمن الدقة الطبية للمحتوى المُنشأ</li>
<li>أنت المسؤول الوحيد عن ضمان الامتثال لأنظمة الرعاية الصحية</li>
</ul>

<h5>6. سلوك المستخدم</h5>
<ul>
<li>يجب على المستخدمين عدم إساءة استخدام المنصة أو إنشاء محتوى ضار</li>
<li>نحتفظ بالحق في تعليق الحسابات التي تنتهك هذه الشروط</li>
</ul>

<h5>7. حدود المسؤولية</h5>
<p>مولد المحتوى الطبي غير مسؤول عن أي أضرار ناتجة عن استخدام المحتوى المُنشأ.</p>

<h5>تواصل معنا:</h5>
<p>للاستفسارات حول هذه الشروط، يرجى التواصل مع فريق الدعم.</p>',
                    'fr' => '<p>Bienvenue sur AI Medical Content. En utilisant notre plateforme, vous acceptez les termes et conditions suivants.</p>

<h5>1. Acceptation des conditions</h5>
<p>En accédant à nos services, vous acceptez d\'être lié par ces conditions.</p>

<h5>2. Compte utilisateur</h5>
<ul>
<li>Vous devez fournir des informations exactes lors de la création d\'un compte</li>
<li>Vous êtes responsable de la confidentialité de vos identifiants</li>
</ul>

<h5>3. Abonnements et paiements</h5>
<ul>
<li>Tous les abonnements sont traités via Digistore24</li>
<li>Les frais d\'abonnement sont facturés à l\'avance</li>
</ul>

<h5>4. Génération et utilisation du contenu</h5>
<ul>
<li>Le contenu généré est fourni à des fins éducatives et marketing uniquement</li>
<li>Vous êtes responsable de la vérification de tout contenu avant publication</li>
</ul>

<h5>5. Avertissement sur le contenu de santé</h5>
<ul>
<li>Tout contenu doit être revu par des professionnels qualifiés</li>
<li>Nous ne garantissons pas l\'exactitude médicale du contenu</li>
</ul>

<h5>Nous contacter:</h5>
<p>Pour toute question, veuillez contacter notre équipe.</p>',
                    'es' => '<p>Bienvenido a AI Medical Content. Al usar nuestra plataforma, acepta los siguientes términos y condiciones.</p>

<h5>1. Aceptación de términos</h5>
<p>Al acceder a nuestros servicios, acepta estar sujeto a estos términos.</p>

<h5>2. Cuenta de usuario</h5>
<ul>
<li>Debe proporcionar información precisa al crear una cuenta</li>
<li>Es responsable de la confidencialidad de sus credenciales</li>
</ul>

<h5>3. Suscripciones y pagos</h5>
<ul>
<li>Todas las suscripciones se procesan a través de Digistore24</li>
<li>Las tarifas de suscripción se facturan por adelantado</li>
</ul>

<h5>4. Generación y uso de contenido</h5>
<ul>
<li>El contenido generado se proporciona solo con fines educativos y de marketing</li>
<li>Es responsable de verificar todo el contenido antes de publicar</li>
</ul>

<h5>5. Aviso sobre contenido de salud</h5>
<ul>
<li>Todo el contenido debe ser revisado por profesionales calificados</li>
<li>No garantizamos la precisión médica del contenido</li>
</ul>

<h5>Contáctenos:</h5>
<p>Para preguntas, comuníquese con nuestro equipo.</p>',
                    'de' => '<p>Willkommen beim AI Medical Content. Durch die Nutzung unserer Plattform akzeptieren Sie folgende Bedingungen.</p>

<h5>1. Annahme der Bedingungen</h5>
<p>Durch den Zugriff auf unsere Dienste stimmen Sie diesen Bedingungen zu.</p>

<h5>2. Benutzerkonto</h5>
<ul>
<li>Sie müssen genaue Informationen bei der Kontoerstellung angeben</li>
<li>Sie sind für die Vertraulichkeit Ihrer Zugangsdaten verantwortlich</li>
</ul>

<h5>3. Abonnements und Zahlungen</h5>
<ul>
<li>Alle Abonnements werden über Digistore24 abgewickelt</li>
<li>Abonnementgebühren werden im Voraus berechnet</li>
</ul>

<h5>4. Content-Generierung und Nutzung</h5>
<ul>
<li>Generierte Inhalte dienen nur zu Bildungs- und Marketingzwecken</li>
<li>Sie sind für die Überprüfung aller Inhalte vor der Veröffentlichung verantwortlich</li>
</ul>

<h5>5. Haftungsausschluss für Gesundheitsinhalte</h5>
<ul>
<li>Alle Inhalte müssen von qualifizierten Fachleuten überprüft werden</li>
<li>Wir garantieren keine medizinische Genauigkeit der Inhalte</li>
</ul>

<h5>Kontaktieren Sie uns:</h5>
<p>Bei Fragen kontaktieren Sie bitte unser Team.</p>',
                ],
                'page' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
                'key' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
            ],
        ];


        foreach ($list as $item) {
            $newService = ConfigTitle::updateOrCreate([
                'id' => $item['id'],
            ],[
                'page' => $item['page'],
                'key' => $item['key'],
            ]);
            foreach ($item['title'] as $locale => $translation) {
                $newService->translateOrNew($locale)->title = $translation;
            }
            foreach ($item['description'] as $locale => $translation) {
                $newService->translateOrNew($locale)->description = $translation;
            }
            $newService->save();
        }

    }
}

