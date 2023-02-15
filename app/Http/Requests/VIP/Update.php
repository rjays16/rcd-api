<?php

namespace App\Http\Requests\VIP;

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
            'email' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'certificate_name'=> 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'pma_number' => 'nullable|string|max:191',
            'prc_license_number' => 'nullable|string|max:191',
            'prc_expiration_date' => 'nullable|string',
            'is_interested_for_ws' => 'boolean',
            'role'=>'nullable|integer',
            'status'=>'nullable|integer',
            'sub_type' => 'nullable|integer',
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