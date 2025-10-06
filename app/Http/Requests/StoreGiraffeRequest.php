<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiraffeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust logic if only authenticated users can request giraffes
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'giraffe_id' => 'required|exists:giraffes,id',
            'note' => 'nullable|string',
        ];
    }
    public function giraffeRequests()
    {
        return $this->hasMany(GiraffeRequest::class);
    }

}
