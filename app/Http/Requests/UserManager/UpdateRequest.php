<?php

namespace App\Http\Requests\UserManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


class UpdateRequest extends FormRequest
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
        $userId = $this->route('id');

        Log::info('User ID from route:', ['userId' => $userId]);


        return [
            'name'      =>  'nullable|string|max:255',
            'avatar'    =>  'nullable|image|mimes:png,jpg',
            'email'     =>  'nullable|email|unique:users,email,' . $userId,
            'password'  =>  'nullable|min:8',
        ];
    }
}
