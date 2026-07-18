<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $portId = $this->route('port')->id ?? $this->route('port');
        
        return [
            'country_id' => 'required|exists:countries,id',
            'port_code' => [
                'required', 'string', 'max:20',
                Rule::unique('ports', 'port_code')->ignore($portId),
            ],
            'port_name' => 'required|string|max:200',
            'city' => 'required|string|max:150',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
