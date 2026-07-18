<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShippingRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_code' => 'required|string|max:50|unique:shipping_routes,route_code',
            'origin_port_id' => 'required|exists:ports,id|different:destination_port_id',
            'destination_port_id' => 'required|exists:ports,id|different:origin_port_id',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
