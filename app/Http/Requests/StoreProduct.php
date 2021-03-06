<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
            'category_id' => ['required', 'int', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'metadata' => ['nullable', 'json'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'metadata' => json_encode($this->metadata),
        ]);
    }
}
