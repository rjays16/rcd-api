<?php

namespace App\Http\Requests\Plenary;

use Anik\Form\FormRequest;

class Create extends FormRequest
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */

    protected $validator;

    public function setContainer($app)
    {
        $this->app = $app;
    }

    protected function authorize(): bool
    {
        return true;
    }

    protected function rules(): array
    {
        return [
            'date' => 'required|string',
            'title' => 'required|string|max:255',
            'speaker_description' => 'required|string|max:255',
            'starts_at' => 'nullable|string',
            'ends_at' => 'nullable|string',
            'header_color' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        return [];
    }

    public function validated(): array
    {
        return $this->validator->validated();
    }
}