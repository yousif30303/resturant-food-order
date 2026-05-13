<?php

namespace App\Http\Requests;

use App\Services\Shared\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function imageRules(string $key): array
    {
        return FileUploadService::imageRules($key);
    }
}
