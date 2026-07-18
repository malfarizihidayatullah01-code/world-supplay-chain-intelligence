<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // the route binding might be named differently, let's just use the param
        $routeId = $this->route('shipping_route')->id ?? $this->route('shipping_route') ?? $this->route('shipping_route_id');
        
        return [
            'route_code' => [
                'required', 'string', 'max:50',
                Rule::unique('shipping_routes', 'route_code')->ignore($routeId),
            ],
            'origin_port_id' => 'required|exists:ports,id|different:destination_port_id',
            'destination_port_id' => 'required|exists:ports,id|different:origin_port_id',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
