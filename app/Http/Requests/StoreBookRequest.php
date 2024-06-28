<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'publication_year' => 'required',
            'genre' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'author.required' => 'The author field is required.',
            'author.string' => 'The author must be a string.',
            'author.max' => 'The author may not be greater than 255 characters.',

            'description.string' => 'The description must be a string.',

            'publication_year.required' => 'The publication year field is required.',

            'genre.required' => 'The genre field is required.',
            'genre.string' => 'The genre must be a string.',
            'genre.max' => 'The genre may not be greater than 255 characters.',

            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price field',

            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity field must be an integer.',

            'images.image' => 'The images must be an image.',
            'images.mimes' => 'The images must be a file of type: jpeg, png, jpg, gif, svg.',
            'images.uploaded' => 'The images may not be greater than 2MB.',
            'images.required' => 'The images field is required.'
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
