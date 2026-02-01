<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtiesSeeder extends Seeder
{
    /**
     * Seed the specialties.
     */
    public function run(): void
    {
        $specialties = [
            [
                'key' => 'dentistry',
                'name' => [
                    'en' => 'Dentistry',
                    'ar' => 'طب الأسنان',
                    'de' => 'Zahnmedizin',
                    'es' => 'Odontología',
                    'fr' => 'Dentisterie',
                ],
                'description' => [
                    'en' => 'Dental care and oral health content',
                    'ar' => 'محتوى العناية بالأسنان وصحة الفم',
                    'de' => 'Zahnpflege und Mundgesundheit Inhalte',
                    'es' => 'Contenido de cuidado dental y salud bucal',
                    'fr' => 'Contenu sur les soins dentaires et la santé bucco-dentaire',
                ],
                'icon' => 'fa-tooth',
                'color' => '#4A90D9',
                'topics' => [
                    [
                        'icon' => 'fa-teeth',
                        'name' => [
                            'en' => 'Teeth Whitening',
                            'ar' => 'تبييض الأسنان',
                            'de' => 'Zahnaufhellung',
                            'es' => 'Blanqueamiento Dental',
                            'fr' => 'Blanchiment des Dents'
                        ],
                    ],
                    [
                        'icon' => 'fa-tooth',
                        'name' => [
                            'en' => 'Dental Implants',
                            'ar' => 'زراعة الأسنان',
                            'de' => 'Zahnimplantate',
                            'es' => 'Implantes Dentales',
                            'fr' => 'Implants Dentaires'
                        ],
                    ],
                    [
                        'icon' => 'fa-syringe',
                        'name' => [
                            'en' => 'Root Canal Treatment',
                            'ar' => 'علاج قناة الجذر',
                            'de' => 'Wurzelkanalbehandlung',
                            'es' => 'Tratamiento de Conducto',
                            'fr' => 'Traitement de Canal'
                        ],
                    ],
                    [
                        'icon' => 'fa-crown',
                        'name' => [
                            'en' => 'Dental Crowns',
                            'ar' => 'تيجان الأسنان',
                            'de' => 'Zahnkronen',
                            'es' => 'Coronas Dentales',
                            'fr' => 'Couronnes Dentaires'
                        ],
                    ],
                    [
                        'icon' => 'fa-teeth-open',
                        'name' => [
                            'en' => 'Orthodontics & Braces',
                            'ar' => 'تقويم الأسنان',
                            'de' => 'Kieferorthopädie & Zahnspangen',
                            'es' => 'Ortodoncia y Brackets',
                            'fr' => 'Orthodontie et Appareils'
                        ],
                    ],
                    [
                        'icon' => 'fa-tooth',
                        'name' => [
                            'en' => 'Wisdom Teeth',
                            'ar' => 'أسنان العقل',
                            'de' => 'Weisheitszähne',
                            'es' => 'Muelas del Juicio',
                            'fr' => 'Dents de Sagesse'
                        ],
                    ],
                    [
                        'icon' => 'fa-virus',
                        'name' => [
                            'en' => 'Gum Disease',
                            'ar' => 'أمراض اللثة',
                            'de' => 'Zahnfleischerkrankungen',
                            'es' => 'Enfermedad de las Encías',
                            'fr' => 'Maladie des Gencives'
                        ],
                    ],
                    [
                        'icon' => 'fa-shield',
                        'name' => [
                            'en' => 'Cavity Prevention',
                            'ar' => 'الوقاية من التسوس',
                            'de' => 'Kariesprävention',
                            'es' => 'Prevención de Caries',
                            'fr' => 'Prévention des Caries'
                        ],
                    ],
                    [
                        'icon' => 'fa-pump-soap',
                        'name' => [
                            'en' => 'Dental Hygiene',
                            'ar' => 'نظافة الأسنان',
                            'de' => 'Zahnhygiene',
                            'es' => 'Higiene Dental',
                            'fr' => 'Hygiène Dentaire'
                        ],
                    ],
                    [
                        'icon' => 'fa-child',
                        'name' => [
                            'en' => 'Pediatric Dentistry',
                            'ar' => 'طب أسنان الأطفال',
                            'de' => 'Kinderzahnheilkunde',
                            'es' => 'Odontología Pediátrica',
                            'fr' => 'Dentisterie Pédiatrique'
                        ],
                    ],
                    [
                        'icon' => 'fa-wand-magic-sparkles',
                        'name' => [
                            'en' => 'Cosmetic Dentistry',
                            'ar' => 'طب الأسنان التجميلي',
                            'de' => 'Kosmetische Zahnmedizin',
                            'es' => 'Odontología Cosmética',
                            'fr' => 'Dentisterie Esthétique'
                        ],
                    ],
                    [
                        'icon' => 'fa-face-sad-tear',
                        'name' => [
                            'en' => 'Dental Anxiety',
                            'ar' => 'قلق طبيب الأسنان',
                            'de' => 'Zahnarztangst',
                            'es' => 'Ansiedad Dental',
                            'fr' => 'Anxiété Dentaire'
                        ],
                    ],
                    [
                        'icon' => 'fa-truck-medical',
                        'name' => [
                            'en' => 'Emergency Dental Care',
                            'ar' => 'رعاية الأسنان الطارئة',
                            'de' => 'Zahnärztliche Notfallversorgung',
                            'es' => 'Atención Dental de Emergencia',
                            'fr' => 'Soins Dentaires d\'Urgence'
                        ],
                    ],
                    [
                        'icon' => 'fa-x-ray',
                        'name' => [
                            'en' => 'Dental X-rays',
                            'ar' => 'أشعة الأسنان',
                            'de' => 'Zahnröntgen',
                            'es' => 'Radiografías Dentales',
                            'fr' => 'Radiographies Dentaires'
                        ],
                    ],
                    [
                        'icon' => 'fa-hand-holding-medical',
                        'name' => [
                            'en' => 'Tooth Extraction',
                            'ar' => 'خلع الأسنان',
                            'de' => 'Zahnextraktion',
                            'es' => 'Extracción Dental',
                            'fr' => 'Extraction Dentaire'
                        ],
                    ],
                    [
                        'icon' => 'fa-teeth-open',
                        'name' => [
                            'en' => 'Wisdom Teeth',
                            'ar' => 'ضرس العقل',
                            'de' => 'Weisheitszähne',
                            'es' => 'Muelas del Juicio',
                            'fr' => 'Dents de Sagesse'
                        ],
                    ],
                    [
                        'icon' => 'fa-head-side-cough',
                        'name' => [
                            'en' => 'TMJ Disorders',
                            'ar' => 'اضطرابات المفصل الفكي',
                            'de' => 'Kiefergelenksstörungen',
                            'es' => 'Trastornos de ATM',
                            'fr' => 'Troubles de l\'ATM'
                        ],
                    ],
                    [
                        'icon' => 'fa-snowflake',
                        'name' => [
                            'en' => 'Tooth Sensitivity',
                            'ar' => 'حساسية الأسنان',
                            'de' => 'Zahnempfindlichkeit',
                            'es' => 'Sensibilidad Dental',
                            'fr' => 'Sensibilité Dentaire'
                        ],
                    ],
                    [
                        'icon' => 'fa-circle-exclamation',
                        'name' => [
                            'en' => 'Dental Emergencies',
                            'ar' => 'طوارئ الأسنان',
                            'de' => 'Zahnärztliche Notfälle',
                            'es' => 'Emergencias Dentales',
                            'fr' => 'Urgences Dentaires'
                        ],
                    ],
                    [
                        'icon' => 'fa-magnifying-glass',
                        'name' => [
                            'en' => 'Oral Cancer Screening',
                            'ar' => 'فحص سرطان الفم',
                            'de' => 'Mundkrebsvorsorge',
                            'es' => 'Detección de Cáncer Oral',
                            'fr' => 'Dépistage du Cancer Oral'
                        ],
                    ],
                ],
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'dermatology',
                'name' => [
                    'en' => 'Dermatology',
                    'ar' => 'الأمراض الجلدية',
                    'de' => 'Dermatologie',
                    'es' => 'Dermatología',
                    'fr' => 'Dermatologie',
                ],
                'description' => [
                    'en' => 'Skin health and dermatological care content',
                    'ar' => 'محتوى صحة الجلد والرعاية الجلدية',
                    'de' => 'Hautgesundheit und dermatologische Pflege Inhalte',
                    'es' => 'Contenido de salud de la piel y cuidado dermatológico',
                    'fr' => 'Contenu sur la santé de la peau et les soins dermatologiques',
                ],
                'icon' => 'fa-hand-sparkles',
                'color' => '#E8A87C',
                'topics' => [
                    [
                        'icon' => 'fa-face-flushed',
                        'name' => [
                            'en' => 'Acne Treatment',
                            'ar' => 'علاج حب الشباب',
                            'de' => 'Aknebehandlung',
                            'es' => 'Tratamiento del Acné',
                            'fr' => 'Traitement de l\'Acné'
                        ],
                    ],
                    [
                        'icon' => 'fa-ribbon',
                        'name' => [
                            'en' => 'Skin Cancer Awareness',
                            'ar' => 'التوعية بسرطان الجلد',
                            'de' => 'Hautkrebsaufklärung',
                            'es' => 'Concienciación sobre el Cáncer de Piel',
                            'fr' => 'Sensibilisation au Cancer de la Peau'
                        ],
                    ],
                    [
                        'icon' => 'fa-hand',
                        'name' => [
                            'en' => 'Eczema Care',
                            'ar' => 'العناية بالأكزيما',
                            'de' => 'Ekzempflege',
                            'es' => 'Cuidado del Eczema',
                            'fr' => 'Soins de l\'Eczéma'
                        ],
                    ],
                    [
                        'icon' => 'fa-disease',
                        'name' => [
                            'en' => 'Psoriasis Management',
                            'ar' => 'إدارة الصدفية',
                            'de' => 'Psoriasis-Management',
                            'es' => 'Manejo de la Psoriasis',
                            'fr' => 'Gestion du Psoriasis'
                        ],
                    ],
                    [
                        'icon' => 'fa-wand-magic-sparkles',
                        'name' => [
                            'en' => 'Anti-Aging Skincare',
                            'ar' => 'العناية بالبشرة لمكافحة الشيخوخة',
                            'de' => 'Anti-Aging Hautpflege',
                            'es' => 'Cuidado de la Piel Anti-Envejecimiento',
                            'fr' => 'Soins Anti-Âge'
                        ],
                    ],
                    [
                        'icon' => 'fa-sun',
                        'name' => [
                            'en' => 'Sun Protection',
                            'ar' => 'الحماية من الشمس',
                            'de' => 'Sonnenschutz',
                            'es' => 'Protección Solar',
                            'fr' => 'Protection Solaire'
                        ],
                    ],
                    [
                        'icon' => 'fa-face-smile',
                        'name' => [
                            'en' => 'Rosacea',
                            'ar' => 'الوردية',
                            'de' => 'Rosacea',
                            'es' => 'Rosácea',
                            'fr' => 'Rosacée'
                        ],
                    ],
                    [
                        'icon' => 'fa-head-side',
                        'name' => [
                            'en' => 'Hair Loss',
                            'ar' => 'تساقط الشعر',
                            'de' => 'Haarausfall',
                            'es' => 'Caída del Cabello',
                            'fr' => 'Perte de Cheveux'
                        ],
                    ],
                    [
                        'icon' => 'fa-hand-dots',
                        'name' => [
                            'en' => 'Nail Health',
                            'ar' => 'صحة الأظافر',
                            'de' => 'Nagelgesundheit',
                            'es' => 'Salud de las Uñas',
                            'fr' => 'Santé des Ongles'
                        ],
                    ],
                    [
                        'icon' => 'fa-allergies',
                        'name' => [
                            'en' => 'Skin Allergies',
                            'ar' => 'حساسية الجلد',
                            'de' => 'Hautallergien',
                            'es' => 'Alergias Cutáneas',
                            'fr' => 'Allergies Cutanées'
                        ],
                    ],
                    [
                        'icon' => 'fa-circle',
                        'name' => [
                            'en' => 'Moles and Birthmarks',
                            'ar' => 'الشامات والوحمات',
                            'de' => 'Muttermale und Geburtsmale',
                            'es' => 'Lunares y Marcas de Nacimiento',
                            'fr' => 'Grains de Beauté et Taches de Naissance'
                        ],
                    ],
                    [
                        'icon' => 'fa-palette',
                        'name' => [
                            'en' => 'Hyperpigmentation',
                            'ar' => 'فرط التصبغ',
                            'de' => 'Hyperpigmentierung',
                            'es' => 'Hiperpigmentación',
                            'fr' => 'Hyperpigmentation'
                        ],
                    ],
                    [
                        'icon' => 'fa-droplet',
                        'name' => [
                            'en' => 'Dry Skin Care',
                            'ar' => 'العناية بالبشرة الجافة',
                            'de' => 'Trockene Hautpflege',
                            'es' => 'Cuidado de la Piel Seca',
                            'fr' => 'Soins de la Peau Sèche'
                        ],
                    ],
                    [
                        'icon' => 'fa-feather',
                        'name' => [
                            'en' => 'Sensitive Skin',
                            'ar' => 'البشرة الحساسة',
                            'de' => 'Empfindliche Haut',
                            'es' => 'Piel Sensible',
                            'fr' => 'Peau Sensible'
                        ],
                    ],
                    [
                        'icon' => 'fa-virus',
                        'name' => [
                            'en' => 'Skin Infections',
                            'ar' => 'التهابات الجلد',
                            'de' => 'Hautinfektionen',
                            'es' => 'Infecciones de la Piel',
                            'fr' => 'Infections Cutanées'
                        ],
                    ],
                    [
                        'icon' => 'fa-people-group',
                        'name' => [
                            'en' => 'Vitiligo Treatment',
                            'ar' => 'علاج البهاق',
                            'de' => 'Vitiligo-Behandlung',
                            'es' => 'Tratamiento del Vitíligo',
                            'fr' => 'Traitement du Vitiligo'
                        ],
                    ],
                    [
                        'icon' => 'fa-face-flushed',
                        'name' => [
                            'en' => 'Rosacea Management',
                            'ar' => 'إدارة الوردية',
                            'de' => 'Rosazea-Management',
                            'es' => 'Manejo de la Rosácea',
                            'fr' => 'Gestion de la Rosacée'
                        ],
                    ],
                    [
                        'icon' => 'fa-hand-dots',
                        'name' => [
                            'en' => 'Contact Dermatitis',
                            'ar' => 'التهاب الجلد التماسي',
                            'de' => 'Kontaktdermatitis',
                            'es' => 'Dermatitis por Contacto',
                            'fr' => 'Dermatite de Contact'
                        ],
                    ],
                    [
                        'icon' => 'fa-clock-rotate-left',
                        'name' => [
                            'en' => 'Anti-Aging Skin Care',
                            'ar' => 'العناية بالبشرة المضادة للشيخوخة',
                            'de' => 'Anti-Aging Hautpflege',
                            'es' => 'Cuidado Antienvejecimiento',
                            'fr' => 'Soins Anti-Âge'
                        ],
                    ],
                    [
                        'icon' => 'fa-wand-magic-sparkles',
                        'name' => [
                            'en' => 'Laser Skin Treatments',
                            'ar' => 'علاجات الليزر للبشرة',
                            'de' => 'Laser-Hautbehandlungen',
                            'es' => 'Tratamientos Láser para la Piel',
                            'fr' => 'Traitements Laser de la Peau'
                        ],
                    ],
                ],
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'general_clinic',
                'name' => [
                    'en' => 'General Clinic',
                    'ar' => 'العيادة العامة',
                    'de' => 'Allgemeinmedizin',
                    'es' => 'Clínica General',
                    'fr' => 'Clinique Générale',
                ],
                'description' => [
                    'en' => 'General healthcare and wellness content',
                    'ar' => 'محتوى الرعاية الصحية العامة والعافية',
                    'de' => 'Allgemeine Gesundheitsversorgung und Wellness Inhalte',
                    'es' => 'Contenido de atención médica general y bienestar',
                    'fr' => 'Contenu sur les soins de santé généraux et le bien-être',
                ],
                'icon' => 'fa-stethoscope',
                'color' => '#41B3A3',
                'topics' => [
                    [
                        'icon' => 'fa-calendar-check',
                        'name' => [
                            'en' => 'Annual Health Checkups',
                            'ar' => 'الفحوصات الصحية السنوية',
                            'de' => 'Jährliche Gesundheitsuntersuchungen',
                            'es' => 'Chequeos de Salud Anuales',
                            'fr' => 'Bilans de Santé Annuels'
                        ],
                    ],
                    [
                        'icon' => 'fa-syringe',
                        'name' => [
                            'en' => 'Vaccination Information',
                            'ar' => 'معلومات التطعيم',
                            'de' => 'Impfinformationen',
                            'es' => 'Información sobre Vacunación',
                            'fr' => 'Informations sur la Vaccination'
                        ],
                    ],
                    [
                        'icon' => 'fa-heart-pulse',
                        'name' => [
                            'en' => 'Blood Pressure Management',
                            'ar' => 'إدارة ضغط الدم',
                            'de' => 'Blutdruckmanagement',
                            'es' => 'Control de la Presión Arterial',
                            'fr' => 'Gestion de la Tension Artérielle'
                        ],
                    ],
                    [
                        'icon' => 'fa-droplet',
                        'name' => [
                            'en' => 'Diabetes Awareness',
                            'ar' => 'التوعية بمرض السكري',
                            'de' => 'Diabetes-Aufklärung',
                            'es' => 'Concienciación sobre la Diabetes',
                            'fr' => 'Sensibilisation au Diabète'
                        ],
                    ],
                    [
                        'icon' => 'fa-heart',
                        'name' => [
                            'en' => 'Heart Health',
                            'ar' => 'صحة القلب',
                            'de' => 'Herzgesundheit',
                            'es' => 'Salud Cardíaca',
                            'fr' => 'Santé Cardiaque'
                        ],
                    ],
                    [
                        'icon' => 'fa-lungs',
                        'name' => [
                            'en' => 'Respiratory Health',
                            'ar' => 'صحة الجهاز التنفسي',
                            'de' => 'Atemwegsgesundheit',
                            'es' => 'Salud Respiratoria',
                            'fr' => 'Santé Respiratoire'
                        ],
                    ],
                    [
                        'icon' => 'fa-stomach',
                        'name' => [
                            'en' => 'Digestive Health',
                            'ar' => 'صحة الجهاز الهضمي',
                            'de' => 'Verdauungsgesundheit',
                            'es' => 'Salud Digestiva',
                            'fr' => 'Santé Digestive'
                        ],
                    ],
                    [
                        'icon' => 'fa-bed',
                        'name' => [
                            'en' => 'Sleep Health',
                            'ar' => 'صحة النوم',
                            'de' => 'Schlafgesundheit',
                            'es' => 'Salud del Sueño',
                            'fr' => 'Santé du Sommeil'
                        ],
                    ],
                    [
                        'icon' => 'fa-brain',
                        'name' => [
                            'en' => 'Stress Management',
                            'ar' => 'إدارة التوتر',
                            'de' => 'Stressmanagement',
                            'es' => 'Manejo del Estrés',
                            'fr' => 'Gestion du Stress'
                        ],
                    ],
                    [
                        'icon' => 'fa-apple-whole',
                        'name' => [
                            'en' => 'Nutrition and Diet',
                            'ar' => 'التغذية والنظام الغذائي',
                            'de' => 'Ernährung und Diät',
                            'es' => 'Nutrición y Dieta',
                            'fr' => 'Nutrition et Régime'
                        ],
                    ],
                    [
                        'icon' => 'fa-weight-scale',
                        'name' => [
                            'en' => 'Weight Management',
                            'ar' => 'إدارة الوزن',
                            'de' => 'Gewichtsmanagement',
                            'es' => 'Control del Peso',
                            'fr' => 'Gestion du Poids'
                        ],
                    ],
                    [
                        'icon' => 'fa-virus-slash',
                        'name' => [
                            'en' => 'Cold and Flu Prevention',
                            'ar' => 'الوقاية من نزلات البرد والإنفلونزا',
                            'de' => 'Erkältungs- und Grippeprävention',
                            'es' => 'Prevención de Resfriados y Gripe',
                            'fr' => 'Prévention du Rhume et de la Grippe'
                        ],
                    ],
                    [
                        'icon' => 'fa-allergies',
                        'name' => [
                            'en' => 'Allergy Management',
                            'ar' => 'إدارة الحساسية',
                            'de' => 'Allergiemanagement',
                            'es' => 'Manejo de Alergias',
                            'fr' => 'Gestion des Allergies'
                        ],
                    ],                    [
                        'icon' => 'fa-plane-departure',
                        'name' => [
                            'en' => 'Travel Medicine',
                            'ar' => 'طب السفر',
                            'de' => 'Reisemedizin',
                            'es' => 'Medicina del Viajero',
                            'fr' => 'Médecine des Voyages'
                        ],
                    ],
                    [
                        'icon' => 'fa-bed',
                        'name' => [
                            'en' => 'Insomnia Treatment',
                            'ar' => 'علاج الأرق',
                            'de' => 'Schlaflosigkeitsbehandlung',
                            'es' => 'Tratamiento del Insomnio',
                            'fr' => 'Traitement de l\'Insomnie'
                        ],
                    ],
                    [
                        'icon' => 'fa-battery-quarter',
                        'name' => [
                            'en' => 'Chronic Fatigue',
                            'ar' => 'الإرهاق المزمن',
                            'de' => 'Chronische Müdigkeit',
                            'es' => 'Fatiga Crónica',
                            'fr' => 'Fatigue Chronique'
                        ],
                    ],
                    [
                        'icon' => 'fa-droplet-slash',
                        'name' => [
                            'en' => 'Dehydration Prevention',
                            'ar' => 'الوقاية من الجفاف',
                            'de' => 'Dehydrierungsprävention',
                            'es' => 'Prevención de Deshidratación',
                            'fr' => 'Prévention de la Déshydratation'
                        ],
                    ],
                    [
                        'icon' => 'fa-smoking-ban',
                        'name' => [
                            'en' => 'Smoking Cessation',
                            'ar' => 'الإقلاع عن التدخين',
                            'de' => 'Raucherentwöhnung',
                            'es' => 'Dejar de Fumar',
                            'fr' => 'Arrêt du Tabac'
                        ],
                    ],                    [
                        'icon' => 'fa-head-side-virus',
                        'name' => [
                            'en' => 'Mental Health Awareness',
                            'ar' => 'التوعية بالصحة النفسية',
                            'de' => 'Psychische Gesundheit',
                            'es' => 'Concienciación sobre Salud Mental',
                            'fr' => 'Sensibilisation à la Santé Mentale'
                        ],
                    ],
                    [
                        'icon' => 'fa-shield-heart',
                        'name' => [
                            'en' => 'Preventive Care',
                            'ar' => 'الرعاية الوقائية',
                            'de' => 'Präventive Versorgung',
                            'es' => 'Cuidados Preventivos',
                            'fr' => 'Soins Préventifs'
                        ],
                    ],
                ],
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'physiotherapy',
                'name' => [
                    'en' => 'Physiotherapy',
                    'ar' => 'العلاج الطبيعي',
                    'de' => 'Physiotherapie',
                    'es' => 'Fisioterapia',
                    'fr' => 'Physiothérapie',
                ],
                'description' => [
                    'en' => 'Physical therapy and rehabilitation content',
                    'ar' => 'محتوى العلاج الطبيعي وإعادة التأهيل',
                    'de' => 'Physiotherapie und Rehabilitationsinhalte',
                    'es' => 'Contenido de fisioterapia y rehabilitación',
                    'fr' => 'Contenu sur la physiothérapie et la réhabilitation',
                ],
                'icon' => 'fa-person-walking',
                'color' => '#9B59B6',
                'topics' => [
                    ['icon' => 'fa-person-falling-burst', 'name' => ['en' => 'Back Pain Relief', 'ar' => 'تخفيف آلام الظهر', 'de' => 'Rückenschmerzlinderung', 'es' => 'Alivio del Dolor de Espalda', 'fr' => 'Soulagement des Douleurs Dorsales']],
                    ['icon' => 'fa-futbol', 'name' => ['en' => 'Sports Injury Recovery', 'ar' => 'التعافي من الإصابات الرياضية', 'de' => 'Sportverletzungserholung', 'es' => 'Recuperación de Lesiones Deportivas', 'fr' => 'Récupération des Blessures Sportives']],
                    ['icon' => 'fa-hospital', 'name' => ['en' => 'Post-Surgery Rehabilitation', 'ar' => 'إعادة التأهيل بعد الجراحة', 'de' => 'Rehabilitation nach Operationen', 'es' => 'Rehabilitación Postoperatoria', 'fr' => 'Rééducation Post-Chirurgicale']],
                    ['icon' => 'fa-bone', 'name' => ['en' => 'Joint Pain Management', 'ar' => 'إدارة آلام المفاصل', 'de' => 'Gelenkschmerzmanagement', 'es' => 'Manejo del Dolor Articular', 'fr' => 'Gestion de la Douleur Articulaire']],
                    ['icon' => 'fa-person-running', 'name' => ['en' => 'Mobility Exercises', 'ar' => 'تمارين الحركة', 'de' => 'Mobilitätsübungen', 'es' => 'Ejercicios de Movilidad', 'fr' => 'Exercices de Mobilité']],
                    ['icon' => 'fa-person-praying', 'name' => ['en' => 'Posture Correction', 'ar' => 'تصحيح الوضعية', 'de' => 'Haltungskorrektur', 'es' => 'Corrección de Postura', 'fr' => 'Correction de la Posture']],
                    ['icon' => 'fa-head-side-cough', 'name' => ['en' => 'Neck Pain Treatment', 'ar' => 'علاج آلام الرقبة', 'de' => 'Nackenschmerzbehandlung', 'es' => 'Tratamiento del Dolor de Cuello', 'fr' => 'Traitement de la Douleur au Cou']],
                    ['icon' => 'fa-hand-fist', 'name' => ['en' => 'Arthritis Management', 'ar' => 'إدارة التهاب المفاصل', 'de' => 'Arthritis-Management', 'es' => 'Manejo de la Artritis', 'fr' => 'Gestion de l\'Arthrite']],
                    ['icon' => 'fa-scale-balanced', 'name' => ['en' => 'Balance Training', 'ar' => 'تدريب التوازن', 'de' => 'Gleichgewichtstraining', 'es' => 'Entrenamiento de Equilibrio', 'fr' => 'Entraînement à l\'Équilibre']],
                    ['icon' => 'fa-dumbbell', 'name' => ['en' => 'Muscle Strengthening', 'ar' => 'تقوية العضلات', 'de' => 'Muskelstärkung', 'es' => 'Fortalecimiento Muscular', 'fr' => 'Renforcement Musculaire']],
                    ['icon' => 'fa-child-reaching', 'name' => ['en' => 'Stretching Routines', 'ar' => 'روتين الإطالة', 'de' => 'Dehnübungen', 'es' => 'Rutinas de Estiramiento', 'fr' => 'Routines d\'Étirement']],
                    ['icon' => 'fa-kit-medical', 'name' => ['en' => 'Chronic Pain Management', 'ar' => 'إدارة الألم المزمن', 'de' => 'Chronisches Schmerzmanagement', 'es' => 'Manejo del Dolor Crónico', 'fr' => 'Gestion de la Douleur Chronique']],
                    ['icon' => 'fa-desktop', 'name' => ['en' => 'Workplace Ergonomics', 'ar' => 'بيئة العمل المريحة', 'de' => 'Arbeitsplatzergonomie', 'es' => 'Ergonomía en el Lugar de Trabajo', 'fr' => 'Ergonomie au Travail']],
                    ['icon' => 'fa-shield-virus', 'name' => ['en' => 'Injury Prevention', 'ar' => 'الوقاية من الإصابات', 'de' => 'Verletzungsprävention', 'es' => 'Prevención de Lesiones', 'fr' => 'Prévention des Blessures']],
                    ['icon' => 'fa-person-cane', 'name' => ['en' => 'Elderly Care Exercises', 'ar' => 'تمارين رعاية كبار السن', 'de' => 'Übungen für Senioren', 'es' => 'Ejercicios para el Cuidado de Ancianos', 'fr' => 'Exercices pour les Soins aux Personnes Âgées']],
                    ['icon' => 'fa-child', 'name' => ['en' => 'Pediatric Physiotherapy', 'ar' => 'العلاج الطبيعي للأطفال', 'de' => 'Kinderphysiotherapie', 'es' => 'Fisioterapia Pediátrica', 'fr' => 'Physiothérapie Pédiatrique']],
                    ['icon' => 'fa-lungs', 'name' => ['en' => 'Respiratory Therapy', 'ar' => 'العلاج التنفسي', 'de' => 'Atemtherapie', 'es' => 'Terapia Respiratoria', 'fr' => 'Thérapie Respiratoire']],
                    ['icon' => 'fa-venus', 'name' => ['en' => 'Women\'s Health Physio', 'ar' => 'العلاج الطبيعي للنساء', 'de' => 'Frauengesundheit Physiotherapie', 'es' => 'Fisioterapia de Salud Femenina', 'fr' => 'Physiothérapie de Santé Féminine']],
                ],
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'key' => 'cardiology',
                'name' => [
                    'en' => 'Cardiology',
                    'ar' => 'أمراض القلب',
                    'de' => 'Kardiologie',
                    'es' => 'Cardiología',
                    'fr' => 'Cardiologie',
                ],
                'description' => [
                    'en' => 'Heart health and cardiovascular care content',
                    'ar' => 'محتوى صحة القلب والرعاية القلبية الوعائية',
                    'de' => 'Inhalte zur Herzgesundheit und kardiovaskulären Versorgung',
                    'es' => 'Contenido de salud cardíaca y cuidado cardiovascular',
                    'fr' => 'Contenu sur la santé cardiaque et les soins cardiovasculaires',
                ],
                'icon' => 'fa-heart-pulse',
                'color' => '#E74C3C',
                'topics' => [
                    ['icon' => 'fa-heart', 'name' => ['en' => 'Hypertension', 'ar' => 'ارتفاع ضغط الدم', 'de' => 'Bluthochdruck', 'es' => 'Hipertensión', 'fr' => 'Hypertension']],
                    ['icon' => 'fa-heart-circle-bolt', 'name' => ['en' => 'Heart Failure', 'ar' => 'فشل القلب', 'de' => 'Herzinsuffizienz', 'es' => 'Insuficiencia Cardíaca', 'fr' => 'Insuffisance Cardiaque']],
                    ['icon' => 'fa-heart-pulse', 'name' => ['en' => 'Arrhythmia', 'ar' => 'اضطراب النظم', 'de' => 'Herzrhythmusstörungen', 'es' => 'Arritmia', 'fr' => 'Arythmie']],
                    ['icon' => 'fa-stethoscope', 'name' => ['en' => 'Chest Pain Evaluation', 'ar' => 'تقييم ألم الصدر', 'de' => 'Abklärung von Brustschmerzen', 'es' => 'Evaluación del Dolor Torácico', 'fr' => 'Évaluation de la Douleur Thoracique']],
                    ['icon' => 'fa-vials', 'name' => ['en' => 'Cholesterol Management', 'ar' => 'إدارة الكوليسترول', 'de' => 'Cholesterinmanagement', 'es' => 'Manejo del Colesterol', 'fr' => 'Gestion du Cholestérol']],
                    ['icon' => 'fa-person-walking', 'name' => ['en' => 'Cardiac Rehabilitation', 'ar' => 'إعادة التأهيل القلبي', 'de' => 'Kardiologische Rehabilitation', 'es' => 'Rehabilitación Cardíaca', 'fr' => 'Réadaptation Cardiaque']],
                    ['icon' => 'fa-heart-circle-plus', 'name' => ['en' => 'Heart-Healthy Lifestyle', 'ar' => 'نمط حياة صحي للقلب', 'de' => 'Herzgesunder Lebensstil', 'es' => 'Estilo de Vida Saludable para el Corazón', 'fr' => 'Mode de Vie Sain pour le Cœur']],
                    ['icon' => 'fa-heart-circle-check', 'name' => ['en' => 'Preventive Cardiology', 'ar' => 'الوقاية القلبية', 'de' => 'Präventive Kardiologie', 'es' => 'Cardiología Preventiva', 'fr' => 'Cardiologie Préventive']],
                    ['icon' => 'fa-heart-crack', 'name' => ['en' => 'Heart Attack Awareness', 'ar' => 'التوعية بالنوبات القلبية', 'de' => 'Herzinfarkt-Aufklärung', 'es' => 'Concienciación sobre Ataques Cardíacos', 'fr' => 'Sensibilisation aux Crises Cardiaques']],
                    ['icon' => 'fa-heart-circle-bolt', 'name' => ['en' => 'Pacemakers & Devices', 'ar' => 'أجهزة تنظيم ضربات القلب', 'de' => 'Herzschrittmacher und Geräte', 'es' => 'Marcapasos y Dispositivos', 'fr' => 'Stimulateurs Cardiaques et Dispositifs']],
                    ['icon' => 'fa-heart-pulse', 'name' => ['en' => 'Cardiac Testing', 'ar' => 'فحوصات القلب', 'de' => 'Herztests', 'es' => 'Pruebas Cardíacas', 'fr' => 'Tests Cardiaques']],
                    ['icon' => 'fa-heart', 'name' => ['en' => 'Heart Valve Disease', 'ar' => 'أمراض صمامات القلب', 'de' => 'Herzklappenerkrankungen', 'es' => 'Enfermedad de Válvulas Cardíacas', 'fr' => 'Maladie des Valves Cardiaques']],
                ],
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'key' => 'pediatrics',
                'name' => [
                    'en' => 'Pediatrics',
                    'ar' => 'طب الأطفال',
                    'de' => 'Pädiatrie',
                    'es' => 'Pediatría',
                    'fr' => 'Pédiatrie',
                ],
                'description' => [
                    'en' => 'Children health and pediatric care content',
                    'ar' => 'محتوى صحة الأطفال والرعاية الطبية للأطفال',
                    'de' => 'Inhalte zur Kindergesundheit und pädiatrischen Versorgung',
                    'es' => 'Contenido de salud infantil y atención pediátrica',
                    'fr' => 'Contenu sur la santé des enfants et les soins pédiatriques',
                ],
                'icon' => 'fa-baby',
                'color' => '#3498DB',
                'topics' => [
                    ['icon' => 'fa-syringe', 'name' => ['en' => 'Childhood Vaccines', 'ar' => 'لقاحات الأطفال', 'de' => 'Kinderimpfungen', 'es' => 'Vacunas Infantiles', 'fr' => 'Vaccins pour Enfants']],
                    ['icon' => 'fa-ruler-combined', 'name' => ['en' => 'Growth and Development', 'ar' => 'النمو والتطور', 'de' => 'Wachstum und Entwicklung', 'es' => 'Crecimiento y Desarrollo', 'fr' => 'Croissance et Développement']],
                    ['icon' => 'fa-utensils', 'name' => ['en' => 'Child Nutrition', 'ar' => 'تغذية الأطفال', 'de' => 'Kinderernährung', 'es' => 'Nutrición Infantil', 'fr' => 'Nutrition Infantile']],
                    ['icon' => 'fa-child', 'name' => ['en' => 'Common Childhood Illnesses', 'ar' => 'أمراض الطفولة الشائعة', 'de' => 'Häufige Kinderkrankheiten', 'es' => 'Enfermedades Comunes de la Infancia', 'fr' => 'Maladies Infantiles Courantes']],
                    ['icon' => 'fa-brain', 'name' => ['en' => 'Behavioral Health', 'ar' => 'الصحة السلوكية', 'de' => 'Verhaltensgesundheit', 'es' => 'Salud Conductual', 'fr' => 'Santé Comportementale']],
                    ['icon' => 'fa-bed', 'name' => ['en' => 'Sleep for Kids', 'ar' => 'نوم الأطفال', 'de' => 'Schlaf bei Kindern', 'es' => 'Sueño para Niños', 'fr' => 'Sommeil pour Enfants']],
                    ['icon' => 'fa-shield-heart', 'name' => ['en' => 'Child Safety', 'ar' => 'سلامة الأطفال', 'de' => 'Kindersicherheit', 'es' => 'Seguridad Infantil', 'fr' => 'Sécurité des Enfants']],
                    ['icon' => 'fa-notes-medical', 'name' => ['en' => 'Well-Child Visits', 'ar' => 'زيارات متابعة الطفل', 'de' => 'Vorsorgeuntersuchungen', 'es' => 'Visitas de Control Infantil', 'fr' => 'Visites de Suivi de l\'Enfant']],
                    ['icon' => 'fa-baby-carriage', 'name' => ['en' => 'Newborn Care', 'ar' => 'رعاية المواليد', 'de' => 'Neugeborenenversorgung', 'es' => 'Cuidado del Recién Nacido', 'fr' => 'Soins du Nouveau-né']],
                    ['icon' => 'fa-person-breastfeeding', 'name' => ['en' => 'Breastfeeding Support', 'ar' => 'دعم الرضاعة الطبيعية', 'de' => 'Stillunterstützung', 'es' => 'Apoyo a la Lactancia', 'fr' => 'Soutien à l\'Allaitement']],
                    ['icon' => 'fa-puzzle-piece', 'name' => ['en' => 'Autism Spectrum', 'ar' => 'طيف التوحد', 'de' => 'Autismus-Spektrum', 'es' => 'Espectro Autista', 'fr' => 'Spectre Autistique']],
                    ['icon' => 'fa-users', 'name' => ['en' => 'Teenage Health', 'ar' => 'صحة المراهقين', 'de' => 'Jugendgesundheit', 'es' => 'Salud Adolescente', 'fr' => 'Santé des Adolescents']],
                ],
                'active' => true,
                'sort_order' => 6,
            ],
            [
                'key' => 'ophthalmology',
                'name' => [
                    'en' => 'Ophthalmology',
                    'ar' => 'طب العيون',
                    'de' => 'Augenheilkunde',
                    'es' => 'Oftalmología',
                    'fr' => 'Ophtalmologie',
                ],
                'description' => [
                    'en' => 'Eye health and vision care content',
                    'ar' => 'محتوى صحة العين ورعاية البصر',
                    'de' => 'Inhalte zur Augengesundheit und Sehpflege',
                    'es' => 'Contenido de salud ocular y cuidado de la visión',
                    'fr' => 'Contenu sur la santé des yeux et les soins de la vision',
                ],
                'icon' => 'fa-eye',
                'color' => '#2ECC71',
                'topics' => [
                    ['icon' => 'fa-eye', 'name' => ['en' => 'Vision Screening', 'ar' => 'فحص النظر', 'de' => 'Sehtest', 'es' => 'Evaluación de la Visión', 'fr' => 'Dépistage de la Vision']],
                    ['icon' => 'fa-eye-dropper', 'name' => ['en' => 'Dry Eye Treatment', 'ar' => 'علاج جفاف العين', 'de' => 'Behandlung trockener Augen', 'es' => 'Tratamiento del Ojo Seco', 'fr' => 'Traitement de la Sécheresse Oculaire']],
                    ['icon' => 'fa-glasses', 'name' => ['en' => 'Refractive Errors', 'ar' => 'عيوب الإبصار', 'de' => 'Fehlsichtigkeit', 'es' => 'Errores Refractivos', 'fr' => 'Erreurs de Réfraction']],
                    ['icon' => 'fa-eye-low-vision', 'name' => ['en' => 'Cataract Care', 'ar' => 'علاج المياه البيضاء', 'de' => 'Kataraktversorgung', 'es' => 'Cuidado de Cataratas', 'fr' => 'Soins des Cataractes']],
                    ['icon' => 'fa-eye-low-vision', 'name' => ['en' => 'Glaucoma Awareness', 'ar' => 'التوعية بالجلوكوما', 'de' => 'Glaukom-Aufklärung', 'es' => 'Concienciación sobre el Glaucoma', 'fr' => 'Sensibilisation au Glaucome']],
                    ['icon' => 'fa-eye', 'name' => ['en' => 'Eye Allergy Relief', 'ar' => 'تخفيف حساسية العين', 'de' => 'Linderung von Augenallergien', 'es' => 'Alivio de Alergias Oculares', 'fr' => 'Soulagement des Allergies Oculaires']],
                    ['icon' => 'fa-shield', 'name' => ['en' => 'Eye Safety', 'ar' => 'سلامة العين', 'de' => 'Augensicherheit', 'es' => 'Seguridad Ocular', 'fr' => 'Sécurité Oculaire']],
                    ['icon' => 'fa-sun', 'name' => ['en' => 'UV Protection for Eyes', 'ar' => 'حماية العين من الأشعة فوق البنفسجية', 'de' => 'UV-Schutz für die Augen', 'es' => 'Protección UV para los Ojos', 'fr' => 'Protection UV pour les Yeux']],
                    ['icon' => 'fa-circle-dot', 'name' => ['en' => 'Contact Lens Care', 'ar' => 'العناية بالعدسات اللاصقة', 'de' => 'Kontaktlinsenpflege', 'es' => 'Cuidado de Lentes de Contacto', 'fr' => 'Soins des Lentilles de Contact']],
                    ['icon' => 'fa-desktop', 'name' => ['en' => 'Digital Eye Strain', 'ar' => 'إجهاد العين الرقمي', 'de' => 'Digitale Augenbelastung', 'es' => 'Fatiga Visual Digital', 'fr' => 'Fatigue Oculaire Numérique']],
                    ['icon' => 'fa-droplet', 'name' => ['en' => 'Diabetic Eye Disease', 'ar' => 'أمراض العين السكري', 'de' => 'Diabetische Augenerkrankung', 'es' => 'Enfermedad Ocular Diabética', 'fr' => 'Maladie Oculaire Diabétique']],
                    ['icon' => 'fa-circle', 'name' => ['en' => 'Eye Floaters', 'ar' => 'الذبابة الطائرة', 'de' => 'Glaskörpertrübungen', 'es' => 'Moscas Volantes', 'fr' => 'Corps Flottants']],
                ],
                'active' => true,
                'sort_order' => 7,
            ],
            [
                'key' => 'orthopedics',
                'name' => [
                    'en' => 'Orthopedics',
                    'ar' => 'جراحة العظام',
                    'de' => 'Orthopädie',
                    'es' => 'Ortopedia',
                    'fr' => 'Orthopédie',
                ],
                'description' => [
                    'en' => 'Bone, joint, and musculoskeletal system care',
                    'ar' => 'رعاية العظام والمفاصل والجهاز العضلي الهيكلي',
                    'de' => 'Knochen-, Gelenk- und Bewegungsapparatversorgung',
                    'es' => 'Cuidado de huesos, articulaciones y sistema musculoesquelético',
                    'fr' => 'Soins des os, articulations et système musculo-squelettique',
                ],
                'icon' => 'fa-bone',
                'color' => '#95A5A6',
                'topics' => [
                    ['icon' => 'fa-bone-break', 'name' => ['en' => 'Fracture Management', 'ar' => 'علاج الكسور', 'de' => 'Frakturbehandlung', 'es' => 'Manejo de Fracturas', 'fr' => 'Gestion des Fractures']],
                    ['icon' => 'fa-circle-nodes', 'name' => ['en' => 'Joint Replacement', 'ar' => 'استبدال المفاصل', 'de' => 'Gelenkersatz', 'es' => 'Reemplazo de Articulaciones', 'fr' => 'Remplacement Articulaire']],
                    ['icon' => 'fa-hands-holding-circle', 'name' => ['en' => 'Osteoporosis Care', 'ar' => 'رعاية هشاشة العظام', 'de' => 'Osteoporoseversorgung', 'es' => 'Cuidado de la Osteoporosis', 'fr' => 'Soins de l\'Ostéoporose']],
                    ['icon' => 'fa-handshake-angle', 'name' => ['en' => 'Carpal Tunnel Syndrome', 'ar' => 'متلازمة النفق الرسغي', 'de' => 'Karpaltunnelsyndrom', 'es' => 'Síndrome del Túnel Carpiano', 'fr' => 'Syndrome du Canal Carpien']],
                    ['icon' => 'fa-user-injured', 'name' => ['en' => 'Sports Injuries', 'ar' => 'إصابات رياضية', 'de' => 'Sportverletzungen', 'es' => 'Lesiones Deportivas', 'fr' => 'Blessures Sportives']],
                    ['icon' => 'fa-street-view', 'name' => ['en' => 'Scoliosis Treatment', 'ar' => 'علاج الجنف', 'de' => 'Skoliosebehandlung', 'es' => 'Tratamiento de Escoliosis', 'fr' => 'Traitement de la Scoliose']],
                    ['icon' => 'fa-wheelchair', 'name' => ['en' => 'Mobility Aids', 'ar' => 'مساعدات الحركة', 'de' => 'Mobilitätshilfen', 'es' => 'Ayudas para la Movilidad', 'fr' => 'Aides à la Mobilité']],
                    ['icon' => 'fa-person-walking-with-cane', 'name' => ['en' => 'Hip Problems', 'ar' => 'مشاكل الورك', 'de' => 'Hüftprobleme', 'es' => 'Problemas de Cadera', 'fr' => 'Problèmes de Hanche']],
                    ['icon' => 'fa-shoe-prints', 'name' => ['en' => 'Foot & Ankle Care', 'ar' => 'رعاية القدم والكاحل', 'de' => 'Fuß- und Knöchelpflege', 'es' => 'Cuidado de Pie y Tobillo', 'fr' => 'Soins du Pied et de la Cheville']],
                    ['icon' => 'fa-hand-back-fist', 'name' => ['en' => 'Tendon Injuries', 'ar' => 'إصابات الأوتار', 'de' => 'Sehnenverletzungen', 'es' => 'Lesiones de Tendones', 'fr' => 'Blessures des Tendons']],
                ],
                'active' => true,
                'sort_order' => 8,
            ],
            [
                'key' => 'ent',
                'name' => [
                    'en' => 'ENT (Ear, Nose, Throat)',
                    'ar' => 'الأنف والأذن والحنجرة',
                    'de' => 'HNO (Hals-Nasen-Ohren)',
                    'es' => 'Otorrinolaringología',
                    'fr' => 'ORL (Oto-Rhino-Laryngologie)',
                ],
                'description' => [
                    'en' => 'Ear, nose, throat, and related head and neck care',
                    'ar' => 'رعاية الأذن والأنف والحنجرة والرأس والعنق',
                    'de' => 'Hals-, Nasen-, Ohren- und Kopf-Hals-Versorgung',
                    'es' => 'Cuidado de oído, nariz, garganta y cabeza-cuello',
                    'fr' => 'Soins de l\'oreille, du nez, de la gorge et de la tête-cou',
                ],
                'icon' => 'fa-head-side-mask',
                'color' => '#E67E22',
                'topics' => [
                    ['icon' => 'fa-ear-listen', 'name' => ['en' => 'Hearing Loss', 'ar' => 'فقدان السمع', 'de' => 'Hörverlust', 'es' => 'Pérdida Auditiva', 'fr' => 'Perte Auditive']],
                    ['icon' => 'fa-head-side-virus', 'name' => ['en' => 'Sinusitis Treatment', 'ar' => 'علاج التهاب الجيوب الأنفية', 'de' => 'Sinusitis-Behandlung', 'es' => 'Tratamiento de Sinusitis', 'fr' => 'Traitement de la Sinusite']],
                    ['icon' => 'fa-head-side-cough', 'name' => ['en' => 'Tonsillitis Care', 'ar' => 'رعاية التهاب اللوزتين', 'de' => 'Mandelentzündung', 'es' => 'Cuidado de Amigdalitis', 'fr' => 'Soins d\'Amygdalite']],
                    ['icon' => 'fa-wind', 'name' => ['en' => 'Allergic Rhinitis', 'ar' => 'التهاب الأنف التحسسي', 'de' => 'Allergische Rhinitis', 'es' => 'Rinitis Alérgica', 'fr' => 'Rhinite Allergique']],
                    ['icon' => 'fa-volume-xmark', 'name' => ['en' => 'Voice Disorders', 'ar' => 'اضطرابات الصوت', 'de' => 'Stimmstörungen', 'es' => 'Trastornos de la Voz', 'fr' => 'Troubles de la Voix']],
                    ['icon' => 'fa-head-side-cough-slash', 'name' => ['en' => 'Sleep Apnea', 'ar' => 'انقطاع النفس النومي', 'de' => 'Schlafapnoe', 'es' => 'Apnea del Sueño', 'fr' => 'Apnée du Sommeil']],
                    ['icon' => 'fa-face-dizzy', 'name' => ['en' => 'Vertigo & Dizziness', 'ar' => 'الدوار والدوخة', 'de' => 'Schwindel', 'es' => 'Vértigo y Mareo', 'fr' => 'Vertige et Étourdissement']],
                    ['icon' => 'fa-lungs', 'name' => ['en' => 'Nosebleeds', 'ar' => 'نزيف الأنف', 'de' => 'Nasenbluten', 'es' => 'Hemorragias Nasales', 'fr' => 'Saignements de Nez']],
                    ['icon' => 'fa-ear-deaf', 'name' => ['en' => 'Ear Infections', 'ar' => 'التهابات الأذن', 'de' => 'Ohrinfektionen', 'es' => 'Infecciones del Oído', 'fr' => 'Infections de l\'Oreille']],
                    ['icon' => 'fa-user-nurse', 'name' => ['en' => 'Throat Cancer Awareness', 'ar' => 'التوعية بسرطان الحنجرة', 'de' => 'Kehlkopfkrebs-Aufklärung', 'es' => 'Concienciación sobre Cáncer de Garganta', 'fr' => 'Sensibilisation au Cancer de la Gorge']],
                ],
                'active' => true,
                'sort_order' => 9,
            ],
            [
                'key' => 'psychiatry',
                'name' => [
                    'en' => 'Psychiatry',
                    'ar' => 'الطب النفسي',
                    'de' => 'Psychiatrie',
                    'es' => 'Psiquiatría',
                    'fr' => 'Psychiatrie',
                ],
                'description' => [
                    'en' => 'Mental health, emotional well-being, and psychiatric care',
                    'ar' => 'الصحة النفسية والعافية العاطفية والرعاية النفسية',
                    'de' => 'Psychische Gesundheit, emotionales Wohlbefinden und psychiatrische Versorgung',
                    'es' => 'Salud mental, bienestar emocional y atención psiquiátrica',
                    'fr' => 'Santé mentale, bien-être émotionnel et soins psychiatriques',
                ],
                'icon' => 'fa-brain',
                'color' => '#9B59B6',
                'topics' => [
                    ['icon' => 'fa-face-frown', 'name' => ['en' => 'Depression Support', 'ar' => 'دعم الاكتئاب', 'de' => 'Depressionsunterstützung', 'es' => 'Apoyo para la Depresión', 'fr' => 'Soutien à la Dépression']],
                    ['icon' => 'fa-heart-pulse', 'name' => ['en' => 'Anxiety Management', 'ar' => 'إدارة القلق', 'de' => 'Angstbewältigung', 'es' => 'Manejo de la Ansiedad', 'fr' => 'Gestion de l\'Anxiété']],
                    ['icon' => 'fa-bolt', 'name' => ['en' => 'Stress Relief', 'ar' => 'تخفيف التوتر', 'de' => 'Stressabbau', 'es' => 'Alivio del Estrés', 'fr' => 'Soulagement du Stress']],
                    ['icon' => 'fa-bed-pulse', 'name' => ['en' => 'Sleep Disorders', 'ar' => 'اضطرابات النوم', 'de' => 'Schlafstörungen', 'es' => 'Trastornos del Sueño', 'fr' => 'Troubles du Sommeil']],
                    ['icon' => 'fa-people-arrows', 'name' => ['en' => 'Bipolar Disorder', 'ar' => 'اضطراب ثنائي القطب', 'de' => 'Bipolare Störung', 'es' => 'Trastorno Bipolar', 'fr' => 'Trouble Bipolaire']],
                    ['icon' => 'fa-brain-circuit', 'name' => ['en' => 'ADHD Care', 'ar' => 'رعاية فرط الحركة', 'de' => 'ADHS-Versorgung', 'es' => 'Cuidado del TDAH', 'fr' => 'Soins du TDAH']],
                    ['icon' => 'fa-hand-holding-heart', 'name' => ['en' => 'PTSD Support', 'ar' => 'دعم اضطراب ما بعد الصدمة', 'de' => 'PTBS-Unterstützung', 'es' => 'Apoyo para TEPT', 'fr' => 'Soutien au SSPT']],
                    ['icon' => 'fa-user-shield', 'name' => ['en' => 'Eating Disorders', 'ar' => 'اضطرابات الأكل', 'de' => 'Essstörungen', 'es' => 'Trastornos Alimentarios', 'fr' => 'Troubles Alimentaires']],
                    ['icon' => 'fa-comments', 'name' => ['en' => 'Therapy Options', 'ar' => 'خيارات العلاج النفسي', 'de' => 'Therapieoptionen', 'es' => 'Opciones de Terapia', 'fr' => 'Options de Thérapie']],
                    ['icon' => 'fa-face-smile-beam', 'name' => ['en' => 'Mental Wellness Tips', 'ar' => 'نصائح الصحة النفسية', 'de' => 'Tipps für mentales Wohlbefinden', 'es' => 'Consejos de Bienestar Mental', 'fr' => 'Conseils de Bien-Être Mental']],
                ],
                'active' => true,
                'sort_order' => 10,
            ],
            [
                'key' => 'neurology',
                'name' => [
                    'en' => 'Neurology',
                    'ar' => 'الأمراض العصبية',
                    'de' => 'Neurologie',
                    'es' => 'Neurología',
                    'fr' => 'Neurologie',
                ],
                'description' => [
                    'en' => 'Brain, nervous system, and neurological disorders care',
                    'ar' => 'رعاية الدماغ والجهاز العصبي والاضطرابات العصبية',
                    'de' => 'Gehirn-, Nervensystem- und neurologische Störungsversorgung',
                    'es' => 'Cuidado del cerebro, sistema nervioso y trastornos neurológicos',
                    'fr' => 'Soins du cerveau, du système nerveux et des troubles neurologiques',
                ],
                'icon' => 'fa-brain',
                'color' => '#8E44AD',
                'topics' => [
                    ['icon' => 'fa-head-side-virus', 'name' => ['en' => 'Stroke Awareness', 'ar' => 'التوعية بالسكتة الدماغية', 'de' => 'Schlaganfall-Aufklärung', 'es' => 'Concienciación sobre ACV', 'fr' => 'Sensibilisation à l\'AVC']],
                    ['icon' => 'fa-bolt', 'name' => ['en' => 'Epilepsy Management', 'ar' => 'إدارة الصرع', 'de' => 'Epilepsie-Management', 'es' => 'Manejo de la Epilepsia', 'fr' => 'Gestion de l\'Épilepsie']],
                    ['icon' => 'fa-head-side-cough', 'name' => ['en' => 'Migraine Treatment', 'ar' => 'علاج الصداع النصفي', 'de' => 'Migränebehandlung', 'es' => 'Tratamiento de Migraña', 'fr' => 'Traitement de la Migraine']],
                    ['icon' => 'fa-person-walking-dashed-line-arrow-right', 'name' => ['en' => 'Parkinson\'s Disease', 'ar' => 'مرض باركنسون', 'de' => 'Parkinson-Krankheit', 'es' => 'Enfermedad de Parkinson', 'fr' => 'Maladie de Parkinson']],
                    ['icon' => 'fa-brain', 'name' => ['en' => 'Alzheimer\'s Care', 'ar' => 'رعاية الزهايمر', 'de' => 'Alzheimer-Versorgung', 'es' => 'Cuidado del Alzheimer', 'fr' => 'Soins d\'Alzheimer']],
                    ['icon' => 'fa-virus-slash', 'name' => ['en' => 'Multiple Sclerosis', 'ar' => 'التصلب المتعدد', 'de' => 'Multiple Sklerose', 'es' => 'Esclerosis Múltiple', 'fr' => 'Sclérose en Plaques']],
                    ['icon' => 'fa-hand-dots', 'name' => ['en' => 'Neuropathy', 'ar' => 'اعتلال الأعصاب الطرفية', 'de' => 'Neuropathie', 'es' => 'Neuropatía', 'fr' => 'Neuropathie']],
                    ['icon' => 'fa-face-dizzy', 'name' => ['en' => 'Dizziness & Balance', 'ar' => 'الدوخة واضطرابات التوازن', 'de' => 'Schwindel und Gleichgewicht', 'es' => 'Mareo y Equilibrio', 'fr' => 'Vertiges et Équilibre']],
                    ['icon' => 'fa-bed', 'name' => ['en' => 'Sleep Disorders', 'ar' => 'اضطرابات النوم العصبية', 'de' => 'Neurologische Schlafstörungen', 'es' => 'Trastornos del Sueño', 'fr' => 'Troubles du Sommeil']],
                    ['icon' => 'fa-memory', 'name' => ['en' => 'Memory Problems', 'ar' => 'مشاكل الذاكرة', 'de' => 'Gedächtnisprobleme', 'es' => 'Problemas de Memoria', 'fr' => 'Problèmes de Mémoire']],
                    ['icon' => 'fa-face-grimace', 'name' => ['en' => 'Facial Nerve Disorders', 'ar' => 'اضطرابات أعصاب الوجه', 'de' => 'Gesichtsnervstörungen', 'es' => 'Trastornos del Nervio Facial', 'fr' => 'Troubles du Nerf Facial']],
                    ['icon' => 'fa-hand-fist', 'name' => ['en' => 'Movement Disorders', 'ar' => 'اضطرابات الحركة', 'de' => 'Bewegungsstörungen', 'es' => 'Trastornos del Movimiento', 'fr' => 'Troubles du Mouvement']],
                ],
                'active' => true,
                'sort_order' => 11,
            ],
        ];

        foreach ($specialties as $specialtyData) {
            $translations = [
                'name' => $specialtyData['name'],
                'description' => $specialtyData['description'],
            ];
            $topicsData = $specialtyData['topics'] ?? [];
            unset($specialtyData['name'], $specialtyData['description'], $specialtyData['topics']);

            // Add slug from key
            $specialtyData['slug'] = $specialtyData['key'];

            $specialty = Specialty::updateOrCreate(
                ['key' => $specialtyData['key']],
                $specialtyData
            );

            foreach (array_keys(config('languages', [])) as $locale) {
                $specialty->translateOrNew($locale)->name = $translations['name'][$locale] ?? $translations['name']['en'];
                $specialty->translateOrNew($locale)->description = $translations['description'][$locale] ?? $translations['description']['en'];
            }

            $specialty->save();

            // Create topics for this specialty
            $sortOrder = 1;
            foreach ($topicsData as $topicItem) {
                $topic = Topic::updateOrCreate(
                    [
                        'specialty_id' => $specialty->id,
                        'sort_order' => $sortOrder,
                    ],
                    [
                        'specialty_id' => $specialty->id,
                        'icon' => $topicItem['icon'] ?? 'fa-circle',
                        'sort_order' => $sortOrder,
                        'active' => true,
                    ]
                );

                // Save translations
                foreach (array_keys(config('languages')) as $locale) {
                    $topicName = $topicItem['name'][$locale] ?? $topicItem['name']['en'];
                    $specialtyName = $translations['name'][$locale] ?? $translations['name']['en'];
                    $topic->translateOrNew($locale)->name = $topicName;
                    $topic->translateOrNew($locale)->description = $topicItem['description'][$locale] ?? null;
                    $topic->translateOrNew($locale)->prompt_hint = $this->buildPromptHint($locale, $topicName, $specialtyName);
                }
                $topic->save();
                
                $sortOrder++;
            }
        }
    }

    /**
     * Build a localized prompt hint for a topic.
     * Enhanced with medical expertise and AI optimization.
     */
    protected function buildPromptHint(string $locale, string $topicName, string $specialtyName): string
    {
        return match ($locale) {
            'ar' => "أنت طبيب استشاري متخصص في {$specialtyName}. اكتب محتوى طبي دقيق، موثوق، وسهل الفهم عن '{$topicName}' موجه للمرضى والأسر.\n\n📋 يجب أن يشمل المحتوى:\n• التعريف والمعلومات الأساسية\n• الأسباب والعوامل المؤثرة\n• الأعراض والعلامات التحذيرية\n• طرق التشخيص\n• خيارات العلاج الحديثة (طبية وجراحية)\n• نصائح الوقاية والرعاية الذاتية\n• متى يجب استشارة الطبيب فوراً\n• نصائح عملية للحياة اليومية\n\n✨ أسلوب الكتابة:\n• لغة واضحة وبسيطة بدون مصطلحات معقدة\n• نقاط مرتبة ومنظمة\n• لهجة طمأنة وتشجيع\n• معلومات قائمة على الأدلة العلمية\n• تجنب المبالغة أو الترويج التجاري",
            
            'de' => "Sie sind ein Facharzt für {$specialtyName}. Erstellen Sie präzise, vertrauenswürdige und leicht verständliche medizinische Inhalte über '{$topicName}' für Patienten und Familien.\n\n📋 Der Inhalt sollte Folgendes umfassen:\n• Definition und grundlegende Informationen\n• Ursachen und Risikofaktoren\n• Symptome und Warnzeichen\n• Diagnoseverfahren\n• Moderne Behandlungsoptionen (medizinisch und chirurgisch)\n• Präventionstipps und Selbstpflege\n• Wann sofort ein Arzt aufzusuchen ist\n• Praktische Alltagstipps\n\n✨ Schreibstil:\n• Klare, einfache Sprache ohne komplexe Fachbegriffe\n• Strukturierte Aufzählungspunkte\n• Beruhigender und ermutigender Ton\n• Evidenzbasierte Informationen\n• Keine Übertreibungen oder kommerziellen Werbungen",
            
            'es' => "Eres médico especialista en {$specialtyName}. Crea contenido médico preciso, confiable y fácil de entender sobre '{$topicName}' dirigido a pacientes y familias.\n\n📋 El contenido debe incluir:\n• Definición e información básica\n• Causas y factores de riesgo\n• Síntomas y señales de advertencia\n• Métodos de diagnóstico\n• Opciones de tratamiento modernas (médicas y quirúrgicas)\n• Consejos de prevención y autocuidado\n• Cuándo buscar atención médica inmediata\n• Consejos prácticos para la vida diaria\n\n✨ Estilo de escritura:\n• Lenguaje claro y simple sin términos complejos\n• Puntos organizados y estructurados\n• Tono tranquilizador y alentador\n• Información basada en evidencia científica\n• Evitar exageraciones o promoción comercial",
            
            'fr' => "Vous êtes médecin spécialiste en {$specialtyName}. Créez un contenu médical précis, fiable et facile à comprendre sur '{$topicName}' destiné aux patients et aux familles.\n\n📋 Le contenu doit inclure:\n• Définition et informations de base\n• Causes et facteurs de risque\n• Symptômes et signes d'avertissement\n• Méthodes de diagnostic\n• Options de traitement modernes (médicales et chirurgicales)\n• Conseils de prévention et d'auto-soins\n• Quand consulter un médecin immédiatement\n• Conseils pratiques pour la vie quotidienne\n\n✨ Style d'écriture:\n• Langage clair et simple sans termes complexes\n• Points organisés et structurés\n• Ton rassurant et encourageant\n• Informations fondées sur des preuves scientifiques\n• Éviter les exagérations ou la promotion commerciale",
            
            default => "You are a medical specialist in {$specialtyName}. Create accurate, trustworthy, and easy-to-understand medical content about '{$topicName}' for patients and families.\n\n📋 Content should include:\n• Definition and basic information\n• Causes and risk factors\n• Symptoms and warning signs\n• Diagnostic methods\n• Modern treatment options (medical and surgical)\n• Prevention tips and self-care\n• When to seek immediate medical attention\n• Practical daily life tips\n\n✨ Writing style:\n• Clear, simple language without complex medical jargon\n• Organized bullet points\n• Reassuring and encouraging tone\n• Evidence-based information\n• Avoid exaggerations or commercial promotion",
        };
    }
}
