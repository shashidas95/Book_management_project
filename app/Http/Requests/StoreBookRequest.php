<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title'           => ['required', 'string', 'max:255'],
            'author'          => ['required', 'string', 'max:255'],
            'isbn'            => ['required', 'string', 'max:20', 'unique:books,isbn'],
            'published_year'  => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'available_copies' => ['required', 'integer', 'min:0'],
            'total_copies'    => ['required', 'integer', 'min:1', 'gte:available_copies'],
        ];
    }
}
