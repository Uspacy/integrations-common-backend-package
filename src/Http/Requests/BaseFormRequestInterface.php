<?php


namespace Uspacy\IntegrationsBackendPackage\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

interface BaseFormRequestInterface
{
    public function failedValidation(Validator $validator): array;
}
