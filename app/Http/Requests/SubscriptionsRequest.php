<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'max_content_generations' => ['required', 'integer', 'min:1'],
            'digistore_product_id' => ['nullable', 'string', 'max:255'],
            'digistore_checkout_url' => ['nullable', 'url', 'max:500'],
            'features' => ['nullable', 'string'], // JSON string
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('translation.subscription.form.validation.name'),
            'price.required' => __('translation.subscription.form.validation.price'),
            'price.numeric' => __('translation.subscription.form.validation.price_numeric'),
            'duration_months.required' => __('translation.subscription.form.validation.duration_months'),
            'duration_months.min' => __('translation.subscription.form.validation.duration_months_min'),
            'max_content_generations.required' => __('translation.subscription.form.validation.max_content_generations'),
            'max_content_generations.min' => __('translation.subscription.form.validation.max_content_generations_min'),
        ];
    }
}
