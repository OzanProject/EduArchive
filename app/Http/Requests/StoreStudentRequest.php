<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'nik' => [
                'nullable',
                'string',
                Rule::unique('students', 'nik')->where('status_kelulusan', 'Aktif')
            ],
            'nisn' => [
                'nullable',
                'string',
                Rule::unique('students', 'nisn')->where('status_kelulusan', 'Aktif')
            ],
            'foto_profil' => 'nullable|image|max:2048',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'nullable|string|max:255',
            'year_in' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }
}
