<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_country_id'      => 'required|exists:countries,id',
            'origin_port_id'         => 'required|exists:ports,id|different:destination_port_id',
            'destination_country_id' => 'required|exists:countries,id',
            'destination_port_id'    => 'required|exists:ports,id|different:origin_port_id',
            'cargo_type'             => 'required|string|max:100',
            'cargo_description'      => 'nullable|string|max:2000',
            'departure_date'         => 'required|date',
            'estimated_arrival'      => 'required|date|after:departure_date',
            'shipment_status'        => 'required|in:Planning,In Transit,Delivered,Delayed,Cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'origin_port_id.different'      => 'Origin port and destination port must be different.',
            'destination_port_id.different' => 'Origin port and destination port must be different.',
            'estimated_arrival.after'       => 'Estimated arrival must be after the departure date.',
        ];
    }
}
