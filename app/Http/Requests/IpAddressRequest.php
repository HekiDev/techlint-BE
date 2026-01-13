<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IpAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ipAddress = $this->route('ipAddress');

        return [
            'address' => [
                'required',
                'ip',
                Rule::unique('ip_addresses')
                    ->ignore($ipAddress?->id),
            ],
            'label' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'address.required' => 'The IP address field is required.',
            'address.ip' => 'The IP address field must be a valid IP address.',
            'address.unique' => 'The IP address already exists.',
            'label.required' => 'The :attribute field is required.',
            'label.max' => 'The :attribute field must not be greater than :max characters.',
            'comment.max' => 'The :attribute field must not be greater than :max characters.',
        ];
    }
}
