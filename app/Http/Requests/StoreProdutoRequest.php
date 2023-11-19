<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class StoreProdutoRequest extends FormRequest
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
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Para retornar erros na API em JSON.
    */

    public function failedValidation(Validator $validator): void
    {
        $response = response()->json($validator->errors(), 422);
        throw new ValidationException($validator, $response);
    }

    /**
     * Tradução das mensagens de erro
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'preco.required' => 'O campo preço é obrigatório',
            'preco.numeric' => 'O campo preço deve ser um número',
            'foto.required' => 'O campo foto é obrigatório',
            'foto.image' => 'O campo foto deve ser uma imagem',
            'foto.mimes' => 'O campo foto deve ser uma imagem do tipo: jpeg, png, jpg, gif, svg',
            'foto.max' => 'O campo foto deve ter no máximo 2048 bytes',
        ];
    }
}
