<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferDogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dog_id' => 'required|exists:dogs,id',
            'new_owner_email' => 'required|email|exists:users,email',
        ];
    }
}
