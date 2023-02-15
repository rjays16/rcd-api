<?php

namespace App\Http\Requests\Symposia;

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
            'author' => 'required|string|max:300',
            'thumbnail' => 'required|string|max:300',
            'video' => 'nullable|string|max:500',
            'category_id' => 'required|integer',
            'card_title' => 'required|string|max:300',
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