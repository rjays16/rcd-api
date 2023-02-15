<?php

namespace App\Http\Requests\Order;

use Anik\Form\FormRequest;

class UpdateStatus extends FormRequest
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
            'convention_member_id' => 'required|integer',
            'order_id' => 'required|integer',
            'status' => 'required|integer',
            'payment_ref' => 'nullable|string',
            'payment_url' => 'nullable|string'
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