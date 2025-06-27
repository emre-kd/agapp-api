<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:40|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|string|max:20|unique:users|regex:/^\S*$/u',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Optional im
            'community_code' => [ 'exists:communities,code'], // <-- burası önemli


        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılmış.',
            'email.max' => 'E-posta en fazla 40 karakter olabilir.',

            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',

            'username.required' => 'Kullanıcı adı zorunludur.',
            'username.unique' => 'Bu kullanıcı adı zaten alınmış.',
            'username.regex' => 'Kullanıcı adı boşluk veya boşluk karakterleri içeremez.',
            'username.max' => 'Kullanıcı adı en fazla 20 karakter olabilir.',

            'community_code.exists' => 'Böyle bir topluluk bulunamadı.',

        ];
    }
}
