<?php

namespace App\Http\Requests\Symposia\Category;

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
            'title' => 'required|string|max:300',
            'chair' => 'required|string|max:300',
            'subtitle' => 'required|string|max:300',
            'card_header_color' => 'nullable|string|max:255'
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