<?php

namespace App\Http\Requests\Fees;

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
            'type' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'year' => 'required|date_format:Y',
            'scope' => 'required|boolean',
            'amount' => 'required|numeric',
            'status' => 'required|boolean',
            'uses_late_amount' => 'required|boolean',
            'late_amount' => 'required_if:uses_late_amount,true|numeric',
            'late_amount_starts_on' => 'required_if:uses_late_amount,true|date_format:Y-m-d|nullable',
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