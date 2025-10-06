<?php
// app/Http/Requests/TransferCatRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferCatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cat_id' => 'required|exists:cats,id',
            'new_owner_email' => 'required|email|exists:users,email',        ];
    }
}
