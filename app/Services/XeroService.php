<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Models\XeroToken;
use XeroPHP\Application;

class XeroService
{
    private GenericProvider $provider;
    private string $accessTokenKey = 'xero_access_token';

    public function __construct()
    {
        $xero_client_id = env('XERO_CLIENT_ID');
        $xero_client_secret = env('XERO_CLIENT_SECRET');

        $this->provider = new GenericProvider([
            'clientId' => $xero_client_id,
            'clientSecret' => $xero_client_secret,
            'redirectUri' => config('services.xero.redirect_uri'),
            'urlAuthorize' => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken' => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation',
        ]);
    }

    public function ensureValidToken(): array
    {
        $tokenRecord = Cache::rememberForever('xero_token', function () {
            return XeroToken::firstOrFail();
        });

        $accessToken = Cache::get($this->accessTokenKey);
        $tenantId = $tokenRecord->tenant_id;

        $response = Http::withToken($accessToken)
            ->withHeaders(['Xero-tenant-id' => $tenantId])
            ->get('https://api.xero.com/api.xro/2.0/Organisations');

        if ($response->status() === 401) {
            $this->refreshToken();
            $accessToken = Cache::get($this->accessTokenKey);
        }

        return [
            'tenant_id' => $tenantId,
            'access_token' => $accessToken,
        ];
    }
    public function getAuthorizationUrl(string $state): string
    {
        return $this->provider->getAuthorizationUrl(
            [
                'state' => $state,
                'scope' => config('services.xero.scopes')
            ]
        );
    }
    public function getXeroInstance(): Application
    {
        $token = $this->ensureValidToken();
        return new Application($token['access_token'], $token['tenant_id']);
    }

    public function getTenantId(string $accessToken): string
    {
        $response = Http::withToken($accessToken)->get('https://api.xero.com/connections');
        if ($response->failed()) {
            throw new \Exception('Failed to retrieve tenant ID');
        }
        return $response->json()[0]['tenantId'];
    }

    public function getTokenData(string $code): array
    {
        $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);
        return [
            'access_token' => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'token_expires_at' => now()->addMinutes(28),
        ];
    }

    public function refreshToken(): void
    {
        try {
            $tokenRecord = XeroToken::firstOrFail();
            $newToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $tokenRecord->refresh_token,
            ]);

            $token = $newToken->getToken();
            $expires = $newToken->getExpires();

            $tokenRecord->update([
                'refresh_token' => $newToken->getRefreshToken(),
            ]);

            Cache::put($this->accessTokenKey . '_expires', $expires);
            Cache::put($this->accessTokenKey, $token, $expires);
        } catch (\Exception $e) {
            error_log('Token refresh failed: ' . $e->getMessage());
        }
    }

    public function saveInitialToken($tokenData): void
    {
        XeroToken::updateOrCreate(['id' => 1], [
            'tenant_id' => $tokenData['tenant_id'],
            'refresh_token' => $tokenData['refresh_token'],
            'reauthenticate_at' => $tokenData['reauthenticate_at'],
        ]);
    }
}
