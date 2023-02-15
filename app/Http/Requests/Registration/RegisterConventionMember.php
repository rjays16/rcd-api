<?php

namespace App\Http\Requests\Registration;

use Anik\Form\FormRequest;

class RegisterConventionMember extends FormRequest
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
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',

            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'certificate_name'=> 'required|nullable|string|max:255',

            'country' => 'nullable|string|max:100',
            'pma_number' => 'nullable|string|max:191',
            'pds_number' => 'nullable|string|max:191',
            'prc_license_number' => 'nullable|string|max:191',
            'prc_expiration_date' => 'nullable|string',

            'is_interested_for_ws' => 'boolean',
            'ws_to_attend'=>'nullable|integer',

            'role'=>'nullable|integer',
            'status'=>'nullable|integer',
            'training_institution' => 'nullable|integer',
            'is_good_standing' => 'boolean',

            'type' => 'required|integer',
            
            'payment_method' => 'required|integer',
            'amount' => 'nullable|integer',
            'intl_amount' => 'nullable|integer',
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
