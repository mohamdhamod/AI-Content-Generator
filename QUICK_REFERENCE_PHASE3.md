# 🚀 Quick Reference Guide - Phase 3 Features

## SEO Analysis Feature

### How to Use:
1. Open any generated content detail page
2. Click **"SEO Analysis"** button (violet gradient)
3. Enter:
   - **Focus Keyword**: Main keyword to rank for (e.g., "diabetes treatment")
   - **Meta Description**: 150-160 character summary
4. Click **"Analyze SEO"**
5. Wait 2-3 seconds for analysis
6. Review results:
   - Overall Score (0-100)
   - Grade (A-F)
   - 8 category scores
   - Recommendations

### API Endpoint:
```bash
POST /en/generate/result/123/seo/analyze
Content-Type: application/json

{
  "focus_keyword": "diabetes treatment",
  "meta_description": "Comprehensive guide to diabetes treatment options including medication, diet, and lifestyle changes."
}
```

### Response:
```json
{
  "success": true,
  "data": {
    "overall_score": 85,
    "grade": "B",
    "scores": {
      "keyword_density": 90,
      "content_length": 85,
      "readability": 75,
      "headings": 80,
      "meta_description": 100,
      "keyword_placement": 85,
      "uniqueness": 95,
      "medical_terms": 70
    },
    "recommendations": [
      "Add more H2 headings for better structure",
      "Consider simplifying complex sentences"
    ],
    "statistics": {
      "word_count": 1250,
      "sentence_count": 45,
      "paragraph_count": 12
    }
  }
}
```

---

## Content Calendar Feature

### How to Use:
1. Open any generated content detail page
2. Click **"Schedule"** button (pink gradient)
3. Select:
   - **Date & Time**: Future datetime
   - **Platforms**: Check desired platforms (Facebook, Twitter, LinkedIn, Instagram, Blog, Website)
   - **Notes**: Optional publishing instructions
4. Choose action:
   - **Schedule**: Set for future date
   - **Publish Now**: Immediate publishing

### Calendar View:
```bash
GET /en/generate/calendar?start_date=2026-02-01&end_date=2026-02-28&status=scheduled
```

### Response:
```json
{
  "success": true,
  "data": {
    "calendar": [
      {
        "date": "2026-02-15",
        "contents": [
          {
            "id": 123,
            "title": "Diabetes Treatment Guide",
            "specialty": "Endocrinology",
            "status": "scheduled",
            "scheduled_at": "2026-02-15 09:00:00",
            "platforms": ["blog", "facebook"]
          }
        ]
      }
    ],
    "statistics": {
      "total": 15,
      "by_status": {
        "draft": 3,
        "scheduled": 8,
        "published": 4,
        "archived": 0
      }
    }
  }
}
```

---

## Database Fields Reference

### generated_contents Table

**SEO Fields:**
- `seo_title` VARCHAR(255) - Optimized title
- `seo_meta_description` TEXT - Meta description
- `seo_focus_keyword` VARCHAR(255) - Target keyword
- `seo_score_data` JSON - Full analysis results
- `seo_overall_score` INTEGER - Score 0-100
- `last_seo_check` TIMESTAMP - Last analysis date

**Calendar Fields:**
- `publishing_status` ENUM(draft, scheduled, published, archived)
- `scheduled_at` TIMESTAMP - When to publish
- `publishing_notes` TEXT - Editorial notes
- `published_platforms` JSON - Array of platforms

---

## Model Methods

### GeneratedContent Model

```php
// SEO Methods
$content->getSeoGrade();        // Returns: 'A', 'B', 'C', 'D', 'F'
$content->isSeoStale();         // Returns: true if >7 days old

// Calendar Methods
$content->isScheduled();        // Returns: true if status = 'scheduled'
$content->isPublished();        // Returns: true if status = 'published'
$content->isOverdue();          // Returns: true if scheduled date passed
```

---

## Rate Limits

All Phase 3 endpoints use existing `content-generation` limiter:
- **Limit**: 10 requests per minute
- **Applies to**:
  - SEO analysis
  - Schedule content
  - Reschedule
  - Publish
  - Batch operations

---

## JavaScript Functions

### SEO Analysis
```javascript
analyzeSeo()                    // Analyze current content
displaySeoResults(data)         // Show results
resetSeoAnalysis()              // Back to form
getScoreColor(score)            // Returns: 'bg-success', 'bg-warning', 'bg-danger'
getGradeClass(grade)            // Returns CSS class for grade badge
```

### Calendar
```javascript
scheduleContent()               // Schedule for future
publishNow()                    // Publish immediately
performPublish()                // Execute publishing
```

---

## Testing Commands

### Run Migration
```bash
php artisan migrate
```

### Verify Routes
```bash
php artisan route:list --path=seo
php artisan route:list --path=calendar
```

### Clear Caches
```bash
php artisan optimize:clear
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

## Common Issues & Solutions

### Issue: "SEO analysis failed"
**Solution**: Check that:
1. Focus keyword is provided
2. Content has sufficient length (>100 words)
3. OpenAI API key is valid

### Issue: "Cannot schedule in past"
**Solution**: Select a future date/time

### Issue: "Calendar not loading"
**Solution**: 
1. Check date range filters
2. Verify user has scheduled content
3. Check browser console for errors

### Issue: "SEO score is 0"
**Solution**: 
1. Ensure focus keyword is entered
2. Check content has headings
3. Verify content length (min 300 words recommended)

---

## Best Practices

### SEO Optimization
1. **Keyword Density**: Aim for 1-3%
2. **Content Length**: 1000-1500 words ideal
3. **Headings**: Use H1 (once), H2 (2-10), H3 (2-5)
4. **Meta Description**: Exactly 150-160 characters
5. **First Paragraph**: Include focus keyword
6. **Medical Terms**: Use 5-10 professional terms

### Content Scheduling
1. **Plan Ahead**: Schedule 1-2 weeks in advance
2. **Consistent Times**: Same time slots work best
3. **Platform Selection**: Choose based on content type
4. **Publishing Notes**: Include platform-specific instructions
5. **Review Overdue**: Check overdue widget daily

---

## Support

### Documentation
- [PHASE_3_DOCUMENTATION.md](PHASE_3_DOCUMENTATION.md) - Complete technical docs
- [MULTI_EXPERT_EVALUATION.md](MULTI_EXPERT_EVALUATION.md) - Expert assessments
- [FINAL_PRODUCT_SUMMARY.md](FINAL_PRODUCT_SUMMARY.md) - Product overview

### Contact
- Technical Issues: Check `storage/logs/laravel.log`
- Feature Requests: GitHub Issues
- Bug Reports: Include error message + steps to reproduce

---

## Version Info

**Current Version**: 3.0.0  
**Phase**: 3 Complete  
**Status**: Production Ready  
**Score**: 9.25/10  

**Last Updated**: January 31, 2026
