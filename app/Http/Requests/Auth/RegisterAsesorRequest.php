<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAsesorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'instansi_asal' => ['required', 'string', 'max:255'],
            'no_registrasi_bnsp' => ['required', 'string', 'max:100'],
            'bidang_kompetensi' => ['required', 'string', 'max:255'],
            'sertifikat' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'password' => ['required', 'string', 'min:8'],
            'agree' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email ini sudah terdaftar.',
            'sertifikat.required' => 'Sertifikat asesor wajib diunggah.',
            'sertifikat.mimes' => 'Format file harus PDF, JPG, atau PNG.',
            'sertifikat.max' => 'Ukuran file maksimal 5MB.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'agree.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ];
    }
}