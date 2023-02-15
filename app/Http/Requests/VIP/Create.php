<?php

namespace App\Http\Requests\VIP;

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
            'email' => 'required|string|max:255',
            
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'certificate_name'=> 'required|string|max:255',

            'country' => 'nullable|string|max:100',

            'pma_number' => 'nullable|string|max:191',
            'pds_number' => 'nullable|string|max:191',
            'prc_license_number' => 'nullable|string|max:191',

            'is_interested_for_ws' => 'boolean',
            'ws_to_attend'=>'nullable|integer',
            
            'status'=>'nullable|integer',
            'training_institution' => 'nullable|integer',
            'resident_certificate' => 'nullable|string|max:255',
            'is_good_standing' => 'boolean',
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

