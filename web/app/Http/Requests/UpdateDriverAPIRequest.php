<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverAPIRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|max:255',
            'phone' => 'sometimes|string|max:40',
            'password' => 'required|string|min:6|max:255',
            'driver_license' => 'image',
            'driver' => 'required|array',
            'driver.vehicle_type_id' => 'required|exists:vehicle_types,id',
            'driver.brand' => 'required|string',
            'driver.model' => 'required|string',
            'driver.plate' => 'required|string',
            'driver.vehicle_document' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'driver' => json_decode($this->driver, true),
        ]);
    }
}
