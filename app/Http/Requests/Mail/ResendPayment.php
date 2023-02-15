<?php

namespace App\Http\Requests\Mail;

use Anik\Form\FormRequest;

class ResendPayment extends FormRequest
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
            'member_id' => 'required|integer',
            'payment_id' => 'required|integer',
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