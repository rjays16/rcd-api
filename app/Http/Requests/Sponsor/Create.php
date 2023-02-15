<?php

namespace App\Http\Requests\Sponsor;

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
            'name' => 'required|string|max:255',
            'rep_name' => 'nullable|string|max:255',            
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:700',
            'phone' => 'nullable|string|max:255',
            'company_email' => 'nullable|string|max:255',
            'kuula_iframe' => 'nullable|string',
            'email' => 'required|string|max:255',
            'sponsor_type_id' => 'required|integer',
            'booth_design_id' => 'nullable|integer',
            'announcement' => 'nullable|string|max:150',
            'interactive_profile' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
            'rep_phone' => 'nullable|string|max:191',
            'rep_landline' => 'nullable|string|max:191',
            'slug' => 'nullable|string|max:191',
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