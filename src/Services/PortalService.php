<?php

namespace Uspacy\IntegrationsBackendPackage\Services;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Uspacy\IntegrationsBackendPackage\Models\Portal;
use Uspacy\IntegrationsBackendPackage\Trait\HelperTrait;

class PortalService
{
    use HelperTrait;

    /**
     * @param array $fields
     * @return JsonResponse
     */
    public function install(array $fields): JsonResponse
    {
        Portal::create($fields);
        return $this->onSuccess();
    }

    /**
     * @param Portal $portal
     * @return JsonResponse
     */
    public function uninstall(Portal $portal): JsonResponse
    {
        if ($portal?->delete()) {
            return $this->onSuccess('success', Response::HTTP_NO_CONTENT);
        }

        return $this->onError();
    }
}
