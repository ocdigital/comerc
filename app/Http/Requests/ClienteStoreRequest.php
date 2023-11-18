<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class ClienteStoreRequest extends FormRequest
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
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'required|string',
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string',
            'complemento' => 'nullable|string',
            'bairro' => 'required|string',
            'cep' => 'required|string'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] = 'required|email|unique:clientes,email,' . $this->route('cliente');
        }

    }

    public function failedValidation(Validator $validator): void
    {
        $response = response()->json($validator->errors(), 422);
        throw new ValidationException($validator, $response);
    }



    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'O campo email já está cadastrado',
            'telefone.required' => 'O campo telefone é obrigatório',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório',
            'data_nascimento.date' => 'O campo data de nascimento deve ser uma data válida',
            'endereco.required' => 'O campo endereço é obrigatório',
            'bairro.required' => 'O campo bairro é obrigatório',
            'cep.required' => 'O campo cep é obrigatório',
        ];
    }
}
