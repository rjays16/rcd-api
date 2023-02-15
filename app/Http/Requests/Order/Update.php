<?php

namespace App\Http\Requests\Order;

use Anik\Form\FormRequest;

class Update extends FormRequest
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
            'order_id' => 'required|integer',
            'convention_member_id' => 'required|integer',
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