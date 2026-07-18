<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_name' => 'required|string|max:255|unique:countries,country_name',
            'iso2_code' => 'required|string|size:2|unique:countries,iso2_code',
            'iso3_code' => 'required|string|size:3|unique:countries,iso3_code',
            'capital_city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'sub_region' => 'nullable|string|max:255',
            'currency_code' => 'nullable|string|size:3',
            'currency_name' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
