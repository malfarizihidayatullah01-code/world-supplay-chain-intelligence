<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countryId = $this->route('country')->id ?? $this->route('country');
        
        return [
            'country_name' => [
                'required', 'string', 'max:255',
                Rule::unique('countries', 'country_name')->ignore($countryId),
            ],
            'iso2_code' => [
                'required', 'string', 'size:2',
                Rule::unique('countries', 'iso2_code')->ignore($countryId),
            ],
            'iso3_code' => [
                'required', 'string', 'size:3',
                Rule::unique('countries', 'iso3_code')->ignore($countryId),
            ],
            'capital_city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'sub_region' => 'nullable|string|max:255',
            'currency_code' => 'nullable|string|size:3',
            'currency_name' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
