<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class PageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['nullable', 'max:2000'],
            'meta' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'type' => ['nullable', 'boolean'],
            'published' => ['nullable', 'boolean'],
        ];
    }
}