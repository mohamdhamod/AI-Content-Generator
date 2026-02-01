<?php

namespace Database\Seeders;

use App\Models\GeneratedContent;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Topic;
use App\Models\ContentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Generated Contents Seeder
 * 
 * Creates demo generated content for demo@medical-ai.com user
 * Showcases different content types, specialties, and topics
 * 
 * @author Senior Laravel Architect + AI Product Designer + Senior AI Prompt + Senior Doctor
 */
class GeneratedContentsSeeder extends Seeder
{
    protected User $demoUser;
    protected array $specialties;
    protected array $topics;
    protected array $contentTypes;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find demo user
        $this->demoUser = User::where('email', 'demo@medical-ai.com')->first();
        
        if (!$this->demoUser) {
            $this->command->warn('Demo user not found. Creating...');
            $this->demoUser = User::create([
                'name' => 'Demo User',
                'email' => 'demo@medical-ai.com',
                'password' => bcrypt('demo123456'),
                'email_verified_at' => now(),
                'role' => 'user',
                'monthly_credits' => 1000,
                'used_credits' => 0,
            ]);
        }
        
        // Load data
        $this->loadSpecialtiesAndTopics();
        $this->loadContentTypes();
        
        // Generate demo contents
        $this->command->info('Creating demo generated contents...');
        
        // 1. Patient Education - Dentistry
        $this->createContent(
            'dentistry',
            'Teeth Whitening',
            'patient_education',
            $this->getDentistryPatientEducation(),
            'en',
            'professional',
            450
        );
        
        // 2. SEO Blog - Dermatology
        $this->createContent(
            'dermatology',
            'Acne Treatment',
            'seo_blog_article',
            $this->getDermatologySEOBlog(),
            'en',
            'informative',
            750
        );
        
        // 3. Social Media - General Clinic
        $this->createContent(
            'general_clinic',
            'Seasonal Flu',
            'social_media_post',
            $this->getGeneralClinicSocialMedia(),
            'en',
            'friendly',
            180
        );
        
        // 4. What to Expect - Cardiology
        $this->createContent(
            'cardiology',
            'ECG Test',
            'what_to_expect',
            $this->getCardiologyWhatToExpect(),
            'en',
            'reassuring',
            520
        );
        
        // 5. Email Follow-up - Physiotherapy
        $this->createContent(
            'physiotherapy',
            'Post-Session Care',
            'email_follow_up',
            $this->getPhysiotherapyEmail(),
            'en',
            'empathetic',
            320
        );
        
        // 6. FAQ - Pediatrics
        $this->createContent(
            'pediatrics',
            'Vaccination',
            'website_faq',
            $this->getPediatricsFAQ(),
            'en',
            'educational',
            600
        );
        
        // 7. Google Review Reply - Orthopedics
        $this->createContent(
            'orthopedics',
            'Positive Review',
            'google_review_reply',
            $this->getOrthopedicsReviewReply(),
            'en',
            'grateful',
            120
        );
        
        // 8. Patient Education (Arabic) - ENT
        $this->createContent(
            'ent',
            'Hearing Loss',
            'patient_education',
            $this->getENTPatientEducationArabic(),
            'ar',
            'professional',
            480
        );
        
        // 9. Blog Article - Psychiatry
        $this->createContent(
            'psychiatry',
            'Stress Management',
            'seo_blog_article',
            $this->getPsychiatryBlog(),
            'en',
            'supportive',
            820
        );
        
        // 10. Social Media - Neurology
        $this->createContent(
            'neurology',
            'Brain Health',
            'social_media_post',
            $this->getNeurologySocialMedia(),
            'en',
            'engaging',
            200
        );
        
        $this->command->info('✓ Created 10 demo generated contents for demo@medical-ai.com');
    }
    
    /**
     * Load specialties and topics
     */
    protected function loadSpecialtiesAndTopics(): void
    {
        $specialtiesList = Specialty::with('activeTopics')->get();
        
        foreach ($specialtiesList as $specialty) {
            $this->specialties[$specialty->key] = $specialty;
            foreach ($specialty->activeTopics as $topic) {
                $this->topics[$specialty->key][] = $topic;
            }
        }
    }
    
    /**
     * Load content types
     */
    protected function loadContentTypes(): void
    {
        $types = ContentType::where('active', true)->get();
        
        foreach ($types as $type) {
            $this->contentTypes[$type->key] = $type;
        }
    }
    
    /**
     * Create generated content
     */
    protected function createContent(
        string $specialtyKey,
        string $topicName,
        string $contentTypeKey,
        string $outputText,
        string $language,
        string $tone,
        int $wordCount
    ): void {
        $specialty = $this->specialties[$specialtyKey] ?? null;
        $contentType = $this->contentTypes[$contentTypeKey] ?? null;
        
        if (!$specialty || !$contentType) {
            $this->command->warn("Skipping: {$specialtyKey} / {$contentTypeKey}");
            return;
        }
        
        // Find topic by name
        $topic = collect($this->topics[$specialtyKey] ?? [])
            ->first(fn($t) => str_contains(strtolower($t->name), strtolower($topicName)));
        
        GeneratedContent::create([
            'user_id' => $this->demoUser->id,
            'specialty_id' => $specialty->id,
            'topic_id' => $topic?->id,
            'content_type_id' => $contentType->id,
            'input_data' => [
                'topic' => $topicName,
                'language' => $language,
                'tone' => $tone,
                'word_count' => $wordCount,
                'target_audience' => 'patients',
                'country' => 'United States',
            ],
            'output_text' => $outputText,
            'language' => $language,
            'country' => 'United States',
            'word_count' => $wordCount,
            'credits_used' => $contentType->credits_cost,
            'tokens_used' => (int)($wordCount * 1.3),
            'status' => 'completed',
            'created_at' => now()->subDays(rand(1, 30)),
        ]);
    }
    
    /**
     * Sample content: Dentistry Patient Education
     */
    protected function getDentistryPatientEducation(): string
    {
        return <<<'EOT'
# Teeth Whitening Options: A Comprehensive Guide

Teeth whitening has become one of the most popular cosmetic dental procedures. Understanding your options can help you make an informed decision about brightening your smile.

## Professional In-Office Whitening

Professional whitening performed at a dental clinic offers the fastest and most dramatic results. The procedure typically involves:

- Application of a protective barrier to your gums
- Use of professional-strength whitening gel (usually containing hydrogen peroxide)
- Activation with a special light or laser
- Treatment duration of 60-90 minutes

Results are immediate, with teeth typically becoming 3-8 shades lighter in a single session.

## At-Home Professional Kits

Your dentist can provide custom-fitted trays and professional whitening gel for home use. These kits offer:

- Custom trays molded to fit your teeth perfectly
- Professional-grade whitening solution
- Gradual whitening over 1-2 weeks
- Convenience of treatment at your own pace

## Over-the-Counter Products

Drugstore whitening products include strips, trays, and toothpastes. While more affordable, they generally provide modest results and take longer to show effects.

## Important Considerations

Before pursuing any whitening treatment:

- Schedule a dental checkup to ensure your oral health is good
- Discuss any tooth sensitivity concerns with your dentist
- Understand that whitening doesn't work on crowns, veneers, or fillings
- Be aware that results vary based on the type and extent of staining

## Maintaining Your Results

To keep your smile bright:

- Practice good oral hygiene
- Avoid or limit staining foods and beverages
- Don't smoke or use tobacco products
- Consider touch-up treatments as recommended by your dentist

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Dermatology SEO Blog
     */
    protected function getDermatologySEOBlog(): string
    {
        return <<<'EOT'
# Understanding Acne Treatment Methods: Your Complete Guide

Acne affects millions of people worldwide, but understanding the available treatment options can help you achieve clearer, healthier skin. This comprehensive guide explores the most effective acne treatment methods available today.

## What Causes Acne?

Acne develops when hair follicles become clogged with oil, dead skin cells, and bacteria. Factors contributing to acne include:

- Hormonal changes during puberty, menstruation, or stress
- Certain medications
- Diet (though this connection is still being studied)
- Genetics
- Skin care products that are too oily

## Over-the-Counter Treatments

Many people find success with OTC acne products containing:

### Benzoyl Peroxide
This ingredient kills acne-causing bacteria and helps remove excess oil and dead skin cells. Available in strengths from 2.5% to 10%.

### Salicylic Acid
Helps prevent pores from becoming clogged and can help break down existing acne lesions. Common in cleansers and spot treatments.

### Alpha Hydroxy Acids (AHAs)
Help remove dead skin cells and reduce inflammation. Best for milder forms of acne.

## Professional Treatment Options

When OTC products aren't effective, dermatologists can prescribe stronger treatments:

### Topical Retinoids
Derived from Vitamin A, these medications prevent clogged pores and have anti-inflammatory properties.

### Oral Antibiotics
May be prescribed for moderate to severe acne to reduce bacteria and fight inflammation.

### Hormonal Treatments
For women whose acne is related to hormonal fluctuations, birth control pills or anti-androgen medications may help.

### Isotretinoin
A powerful medication for severe acne that significantly reduces oil gland size and oil production.

## Professional Procedures

Dermatologists may also recommend:

- Chemical peels
- Light and laser therapies
- Extraction procedures for comedones
- Steroid injections for severe cystic acne

## Developing an Effective Skincare Routine

A proper skincare routine is essential for managing acne:

1. **Cleanse** gently twice daily with a mild cleanser
2. **Treat** with medicated products as directed
3. **Moisturize** with a non-comedogenic moisturizer
4. **Protect** your skin with oil-free sunscreen daily

## When to See a Dermatologist

Consider professional help if:

- OTC treatments aren't working after 12 weeks
- Your acne is severe or painful
- Acne is leaving scars
- Your acne is affecting your self-esteem

## Conclusion

Effective acne treatment requires patience, consistency, and often a combination of approaches. What works for one person may not work for another, so finding the right treatment plan may take time.

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: General Clinic Social Media
     */
    protected function getGeneralClinicSocialMedia(): string
    {
        return <<<'EOT'
🍂 Flu Season is Here! Protect Yourself and Your Loved Ones 🛡️

As temperatures drop, it's time to think about flu prevention! Here are 5 simple ways to stay healthy this season:

✅ Get your annual flu vaccine
✅ Wash your hands frequently with soap and water
✅ Avoid touching your face
✅ Stay home if you're feeling unwell
✅ Maintain a healthy lifestyle with good sleep and nutrition

💉 Flu shots are now available! Call us today to schedule your appointment.

Remember: Prevention is always better than cure! 💪

#FluPrevention #StayHealthy #WellnessTips #FluSeason #HealthyLiving

---

*This content is for educational purposes only. Consult your healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Cardiology What to Expect
     */
    protected function getCardiologyWhatToExpect(): string
    {
        return <<<'EOT'
# What to Expect During Your ECG Test

An electrocardiogram (ECG or EKG) is a simple, painless test that records your heart's electrical activity. Here's what you need to know:

## Before Your Test

**Preparation:**
- Wear comfortable, loose-fitting clothing
- Avoid oily or greasy skin lotions on the day of your test
- Continue taking your medications unless instructed otherwise
- The test typically takes 5-10 minutes

## During the Test

**What Happens:**

1. **Getting Ready**: You'll be asked to remove clothing from your upper body and lie down on an examination table

2. **Electrode Placement**: Small adhesive electrodes (typically 10) will be placed on your chest, arms, and legs. These sensors detect your heart's electrical signals

3. **The Recording**: You'll need to lie still and breathe normally while the machine records your heart's activity. The actual recording takes less than a minute

4. **Completion**: Once finished, the electrodes are removed. There's no recovery time needed

## What You'll Experience

- The test is completely painless
- You may feel slight pulling when electrodes are removed
- Some people with sensitive skin might experience minor irritation
- There are no needles or pain involved

## After the Test

**What's Next:**

- You can resume normal activities immediately
- Your doctor will review the results with you, usually within a few days
- The ECG shows your heart's rhythm, rate, and electrical activity pattern
- Your doctor will explain what the results mean for you

## Why This Test is Important

An ECG helps detect:
- Irregular heart rhythms (arrhythmias)
- Heart muscle damage
- Heart enlargement
- Effects of medications or devices on your heart
- Signs of previous or current heart attack

## Questions to Ask Your Doctor

Feel free to ask:
- Why is this test being ordered?
- What specific information are you looking for?
- When will I get the results?
- What happens if the results show something abnormal?

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Physiotherapy Email Follow-up
     */
    protected function getPhysiotherapyEmail(): string
    {
        return <<<'EOT'
Subject: Post-Session Care Instructions - Important Recovery Tips

Dear Patient,

Thank you for your physiotherapy session today. We're committed to helping you achieve the best possible recovery, and proper post-session care is essential.

**Immediate Post-Session Care (First 24 Hours):**

• Apply ice packs to treated areas for 15-20 minutes every 2-3 hours if you experience any soreness
• Stay well-hydrated by drinking plenty of water
• Avoid strenuous activities and heavy lifting
• Take rest breaks throughout the day

**Home Exercise Program:**

Please continue with the exercises we practiced during your session:

1. Gentle stretching exercises (as demonstrated) - 3 times daily
2. Strengthening exercises - Once daily, or as instructed
3. Posture awareness throughout your daily activities

**What to Expect:**

• Mild soreness or fatigue is normal and should subside within 24-48 hours
• Gradual improvement in mobility and pain levels over the coming days
• Results typically accumulate with consistent session attendance and home exercises

**Important Reminders:**

✓ Complete your home exercises as instructed
✓ Keep your next appointment: [Date/Time]
✓ Contact us if you experience unusual pain or symptoms
✓ Wear comfortable, appropriate clothing for your sessions

**Need to Reschedule?**

Please give us at least 24 hours' notice to reschedule your appointment.

**Questions or Concerns?**

Don't hesitate to call us at [Phone Number] if you have any questions about your treatment or experience unexpected symptoms.

We look forward to seeing you at your next session!

Best regards,
Your Physiotherapy Team

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Pediatrics FAQ
     */
    protected function getPediatricsFAQ(): string
    {
        return <<<'EOT'
# Vaccination Schedule: Frequently Asked Questions

## General Questions

**Q: Why are vaccines important for my child?**
A: Vaccines protect children from serious diseases by training their immune system to recognize and fight specific infections. Vaccination not only protects your child but also helps protect others in the community, especially those too young or too sick to be vaccinated.

**Q: Are vaccines safe for children?**
A: Yes, vaccines are very safe. They undergo extensive testing before approval and are continuously monitored for safety. Serious side effects are extremely rare, and the benefits far outweigh the risks.

**Q: What is the recommended vaccination schedule?**
A: The CDC provides a standard schedule that typically starts at birth. Your pediatrician will guide you through the specific timeline, which includes vaccines at:
- Birth
- 2, 4, and 6 months
- 12-15 months
- 18 months
- 4-6 years
- 11-12 years
- Annual flu vaccines

## Common Concerns

**Q: Can my child get too many vaccines at once?**
A: No, children's immune systems are strong enough to handle multiple vaccines. Combination vaccines are safe and reduce the number of shots needed.

**Q: What are common side effects?**
A: Most side effects are mild and temporary:
- Low-grade fever
- Soreness or redness at injection site
- Mild fussiness
- Slight drowsiness

These typically resolve within 24-48 hours.

**Q: What should I do if my child has a reaction?**
A: For mild reactions:
- Give plenty of fluids
- Apply a cool, wet cloth to sore injection sites
- Use age-appropriate pain reliever if recommended by your doctor

Contact your pediatrician immediately if your child develops:
- High fever (over 104°F)
- Behavioral changes
- Allergic reactions (difficulty breathing, hives)

## Scheduling

**Q: What if we miss a vaccination appointment?**
A: Don't worry! Your pediatrician can help get your child back on schedule. It's never too late to catch up on missed vaccines.

**Q: Can we vaccinate if my child has a cold?**
A: Generally, yes. Mild illness usually doesn't prevent vaccination. However, consult with your pediatrician about any concerns.

**Q: Do we need vaccines if we travel?**
A: Depending on your destination, additional vaccines may be recommended. Discuss travel plans with your pediatrician at least 4-6 weeks before departure.

## Special Circumstances

**Q: What if my child has allergies?**
A: Inform your pediatrician about any known allergies. Some vaccines contain small amounts of substances that might cause reactions in allergic children, but alternatives are often available.

**Q: Are vaccines covered by insurance?**
A: Most insurance plans cover routine childhood vaccinations. The Vaccines for Children (VFC) program provides free vaccines for eligible families.

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Orthopedics Google Review Reply
     */
    protected function getOrthopedicsReviewReply(): string
    {
        return <<<'EOT'
Thank you so much for taking the time to share your positive experience! We're delighted to hear that your treatment went well and that you're happy with the results.

Your recovery and satisfaction are our top priorities, and it's wonderful to know that our team was able to meet your expectations. We truly appreciate your trust in choosing us for your orthopedic care.

If you have any questions or concerns as you continue your recovery, please don't hesitate to reach out. We're here to support you every step of the way.

Wishing you continued healing and wellness!

Warm regards,
[Clinic Name] Orthopedics Team

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: ENT Patient Education (Arabic)
     */
    protected function getENTPatientEducationArabic(): string
    {
        return <<<'EOT'
# الوقاية من فقدان السمع: دليل شامل

## مقدمة

يعد السمع حاسة ثمينة تتطلب العناية والحماية. فقدان السمع يمكن أن يؤثر بشكل كبير على نوعية حياتك، ولكن العديد من حالات فقدان السمع يمكن الوقاية منها باتباع الإرشادات الصحيحة.

## الأسباب الشائعة لفقدان السمع

### التعرض للضوضاء العالية
- الاستماع إلى الموسيقى بصوت عالٍ
- العمل في بيئات صاخبة
- حضور الحفلات الموسيقية والفعاليات الصاخبة

### عوامل أخرى
- التقدم في العمر
- العدوى والالتهابات
- بعض الأدوية
- إصابات الرأس

## نصائح للوقاية

### 1. حماية أذنيك من الضوضاء

**خفض مستوى الصوت:**
- استخدم قاعدة 60/60: لا تتجاوز 60% من الحد الأقصى للصوت لأكثر من 60 دقيقة
- اختر سماعات الرأس التي تعزل الضوضاء الخارجية
- ابتعد عن مصادر الصوت العالية

**استخدم أدوات الحماية:**
- ارتدِ سدادات الأذن في البيئات الصاخبة
- استخدم واقيات الأذن إذا كنت تعمل في بيئة صناعية

### 2. العناية بصحة أذنيك

**نظافة الأذن:**
- لا تستخدم أعواد القطن داخل قناة الأذن
- اترك شمع الأذن يخرج طبيعياً
- راجع الطبيب إذا شعرت بانسداد

**تجنب إصابة الأذن:**
- لا تدخل أشياء صلبة في أذنك
- جفف أذنيك برفق بعد السباحة
- تجنب التغيرات المفاجئة في الضغط

### 3. الفحوصات الدورية

- قم بفحص السمع بانتظام، خاصة بعد سن ال 50
- راجع الطبيب فوراً إذا لاحظت أي تغيير في السمع
- لا تتجاهل علامات فقدان السمع المبكرة

## علامات التحذير

اتصل بطبيبك إذا لاحظت:

✓ صعوبة في سماع المحادثات
✓ الحاجة إلى رفع صوت التلفاز
✓ طنين مستمر في الأذنين
✓ ألم أو إفرازات من الأذن
✓ دوخة أو مشاكل في التوازن

## نصائح لأنماط الحياة

### في المنزل
- اختر الأجهزة المنزلية الهادئة
- أبقِ صوت التلفاز والموسيقى عند مستوى معقول
- امنح أذنيك فترات راحة من الضوضاء

### في العمل
- استخدم معدات الحماية المناسبة
- تحدث إلى صاحب العمل عن تقليل الضوضاء
- خذ فترات راحة من البيئات الصاخبة

### أثناء الترفيه
- ابتعد عن مكبرات الصوت في الحفلات
- استخدم حماية السمع في الأحداث الرياضية
- قلل من وقت استخدام سماعات الرأس

## الخلاصة

حماية سمعك استثمار في صحتك المستقبلية. باتباع هذه الإرشادات البسيطة، يمكنك الحفاظ على سمع جيد لسنوات قادمة.

---

*هذا المحتوى للأغراض التعليمية فقط ولا يحل محل الاستشارة الطبية المهنية. استشر دائماً مقدم رعاية صحية مؤهل للحصول على المشورة الطبية.*
EOT;
    }
    
    /**
     * Sample content: Psychiatry Blog
     */
    protected function getPsychiatryBlog(): string
    {
        return <<<'EOT'
# Effective Stress Management: Strategies for Modern Life

In today's fast-paced world, stress has become an almost constant companion. Understanding how to manage stress effectively is crucial for maintaining both mental and physical health.

## Understanding Stress

Stress is your body's natural response to challenges and demands. While some stress is normal and can even be motivating, chronic stress can have serious health consequences:

- Increased risk of anxiety and depression
- Weakened immune system
- Sleep disturbances
- Digestive problems
- Heart health issues

## Recognizing Stress Symptoms

**Physical signs:**
- Headaches or muscle tension
- Fatigue
- Changes in appetite
- Difficulty sleeping

**Emotional signs:**
- Irritability or anger
- Feeling overwhelmed
- Difficulty concentrating
- Mood swings

## Proven Stress Management Techniques

### 1. Mindfulness and Meditation

Practicing mindfulness helps you stay present and reduces anxiety about the future:

- Start with 5-10 minutes of daily meditation
- Use guided meditation apps
- Practice deep breathing exercises
- Try body scan relaxation

### 2. Physical Activity

Regular exercise is one of the most effective stress relievers:

- Aim for 30 minutes of moderate activity most days
- Choose activities you enjoy
- Even short walks can help
- Consider yoga or tai chi for mind-body benefits

### 3. Healthy Sleep Habits

Quality sleep is essential for stress management:

- Maintain a consistent sleep schedule
- Create a relaxing bedtime routine
- Limit screen time before bed
- Keep your bedroom cool and dark

### 4. Social Connection

Strong relationships provide emotional support:

- Spend time with supportive friends and family
- Join groups with shared interests
- Consider support groups if dealing with specific stressors
- Don't hesitate to reach out when you need help

### 5. Time Management

Better organization can reduce stress:

- Prioritize tasks and set realistic goals
- Learn to say no to non-essential commitments
- Break large projects into smaller steps
- Take regular breaks throughout your day

### 6. Healthy Lifestyle Choices

What you consume affects how you feel:

- Limit caffeine and alcohol
- Eat a balanced, nutritious diet
- Stay hydrated
- Avoid smoking

### 7. Relaxation Techniques

Incorporate relaxation into your daily routine:

- Progressive muscle relaxation
- Guided imagery
- Aromatherapy
- Listening to calming music

## When to Seek Professional Help

Consider talking to a mental health professional if:

- Stress interferes with daily activities
- You feel overwhelmed most of the time
- You're using unhealthy coping mechanisms
- You experience symptoms of depression or anxiety
- Physical symptoms persist

## Developing Your Personal Strategy

Everyone's stress triggers and effective coping methods are different. Experiment with various techniques to find what works best for you:

1. **Identify your stressors**: Keep a stress journal to recognize patterns
2. **Try different techniques**: Give each method a fair trial
3. **Be consistent**: Regular practice is key to seeing results
4. **Adjust as needed**: Your needs may change over time

## Building Resilience

Stress management isn't just about reducing stress—it's also about building resilience:

- Maintain a positive outlook
- Accept that change is part of life
- Learn from challenges
- Take care of yourself physically and emotionally
- Stay connected to your values and purpose

## Conclusion

Managing stress is a skill that improves with practice. By incorporating these strategies into your daily life, you can reduce stress's negative impact and improve your overall well-being. Remember, it's okay to ask for help when you need it.

---

*This content is for educational purposes only and does not replace professional medical consultation. Always consult with a qualified healthcare provider for medical advice.*
EOT;
    }
    
    /**
     * Sample content: Neurology Social Media
     */
    protected function getNeurologySocialMedia(): string
    {
        return <<<'EOT'
🧠 5 Simple Ways to Keep Your Brain Healthy! 🌟

Your brain is your most important organ—let's take care of it! Here are science-backed tips for optimal brain health:

1️⃣ **Stay Physically Active** 🏃‍♀️
Regular exercise increases blood flow to your brain and promotes new brain cell growth.

2️⃣ **Challenge Your Mind** 🧩
Learn new skills, do puzzles, or pick up a new hobby. Keep that brain working!

3️⃣ **Get Quality Sleep** 😴
7-9 hours of sleep helps your brain consolidate memories and clear toxins.

4️⃣ **Eat Brain-Healthy Foods** 🥗
Think omega-3s, antioxidants, and plenty of fruits and vegetables.

5️⃣ **Stay Socially Connected** 👥
Meaningful social interactions keep your brain engaged and reduce stress.

💡 Small changes today can make a big difference tomorrow!

What's your favorite brain-healthy habit? Share below! ⬇️

#BrainHealth #Neurology #HealthyLiving #WellnessTips #HealthyBrain #MentalWellness

---

*This content is for educational purposes only. Consult your healthcare provider for medical advice.*
EOT;
    }
}
