<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nip' => trim($this->nip) === '-' || empty(trim($this->nip)) ? null : trim($this->nip),
            'nuptk' => trim($this->nuptk) === '-' || empty(trim($this->nuptk)) ? null : trim($this->nuptk),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID teacher dari parameter route
        $teacherId = $this->route('teacher');

        return [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => [
                'nullable',
                'string',
                Rule::unique('teachers')->ignore($teacherId)->where(function ($query) {
                    return $query->where('tenant_id', tenant('id'));
                }),
            ],
            'nuptk' => [
                'nullable',
                'string',
                Rule::unique('teachers')->ignore($teacherId)->where(function ($query) {
                    return $query->where('tenant_id', tenant('id'));
                }),
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:255',
            'status_kepegawaian' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'gelar_depan' => 'nullable|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ];
    }
}
