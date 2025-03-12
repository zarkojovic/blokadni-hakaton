<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateDocumentRequest extends FormRequest
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
        return [
            'elaboratFile' => 'required|file|mimes:pdf,docx,doc',
            'type'=>'string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'elaboratFile.required' => 'Elaborat file is required',
            'elaboratFile.file' => 'Elaborat must be a file',
            'elaboratFile.mimes' => 'Elaborat must be a file of type: pdf, docx, doc',
        ];
    }
}
