<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'specialty_id' => 'nullable|exists:specialties,id',
            'content_type_id' => 'required|exists:content_types,id',
            'topic' => 'required|string|min:5|max:500',
            'language' => 'required|string|max:10',
            'tone' => 'nullable|string|max:50',
            'word_count' => 'nullable|integer|min:100|max:2000',
            'target_audience' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:50',
            'additional_instructions' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'content_type_id.required' => __('translation.content_generator.validation.content_type_required'),
            'topic.required' => __('translation.content_generator.validation.topic_required'),
            'topic.min' => __('translation.content_generator.validation.topic_min'),
            'topic.max' => __('translation.content_generator.validation.topic_max'),
            'language.required' => __('translation.content_generator.validation.language_required'),
            'word_count.min' => __('translation.content_generator.validation.word_count_min'),
            'word_count.max' => __('translation.content_generator.validation.word_count_max'),
        ];
    }
}
