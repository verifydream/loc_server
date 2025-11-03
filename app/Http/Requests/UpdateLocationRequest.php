<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locationId = $this->route('location');
        
        return [
            'location_code' => ['required', 'unique:locations,location_code,' . $locationId, 'max:10'],
            'location_name' => ['required', 'max:100'],
            'online_url' => ['required', 'url'],
        ];
    }
}
