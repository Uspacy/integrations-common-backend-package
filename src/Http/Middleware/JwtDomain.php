<?php

namespace Uspacy\IntegrationsBackendPackage\Http\Middleware;

use Uspacy\IntegrationsBackendPackage\Models\Portal;
use Uspacy\IntegrationsBackendPackage\Trait\HelperTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtDomain
{
    use HelperTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwt = $request->bearerToken();
        
        $isInstallRoute = $request->route()->named('portals_install');
        
        $domain = $this->getDomainFromJwt($jwt, config('app.client_secret'), $isInstallRoute);

        if (empty($domain)) {
            $this->onError(__('portal.empty_portal_domain'));
        }

        $portal = Portal::where('domain', $domain)->first();

        if ($isInstallRoute) {
            if ($portal) {
                $this->onError(__('portal.installed'));
            }

            $request->attributes->add([
                'domain' => $domain
            ]);

            return $next($request);
        }

        if (!$portal) {
            $this->onError(__('portal.not_installed'));
        }
    
        $request->attributes->add([
            'portal' => $portal
        ]);

        return $next($request);
    }

    /**
     * @param string $jwt
     * @param string $clientSecret
     * @return string|null
     */
    protected function getDomainFromJwt(string $jwt, string $clientSecret): ?string
    {
        $signingKey = InMemory::plainText($clientSecret);

        try {
            $parsedToken = (new JwtFacade())->parse(
                $jwt,
                new Constraint\SignedWith(new Sha256(), $signingKey),
                new Constraint\StrictValidAt(
                    new FrozenClock(new \DateTimeImmutable())
                )
            );

            $domain = $parsedToken->headers()->get('domain');
        }
        catch (\Throwable $th) {
            $appName = config('api.code');

            Log::error(
                "App@{$appName} parse token for portal", 
                [
                    'message' => $th->getMessage(),
                    'jwt' => $jwt,
                ]
            );

            $this->onError(__('portal.invalid_credentials'));
        }

        return $domain;
    }
}
