<?php

namespace Uspacy\IntegrationsBackendPackage\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Uspacy\IntegrationsBackendPackage\Http\Middleware\JwtDomain;
use Uspacy\IntegrationsBackendPackage\Http\Requests\BaseFormRequest;
use Uspacy\IntegrationsBackendPackage\Http\Requests\Portal\InstallPortalRequest;
use Uspacy\IntegrationsBackendPackage\Services\PortalService;
use Illuminate\Http\JsonResponse;

class PortalController extends Controller
{
    /**
     * Create a new PortalController instance.
     * @return void
     */
    public function __construct(public PortalService $service)
    {
        $this->middleware(JwtDomain::class);
    }

    /**
     * @param InstallPortalRequest $request
     * @return JsonResponse
     */
    public function install(InstallPortalRequest $request): JsonResponse
    {
        $validatedFields = $request->validated();
        $validatedFields['domain'] = $request->attributes->get('domain');
        return $this->service->install($validatedFields);
    }

    /**
     * @param BaseFormRequest $request
     * @return JsonResponse
     */
    public function uninstall(BaseFormRequest $request): JsonResponse
    {
        return $this->service->uninstall($request->attributes->get('portal'));
    }
}
