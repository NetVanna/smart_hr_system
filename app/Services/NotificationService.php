<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    /**
     * Send a push notification via Firebase Cloud Messaging (FCM) HTTP v1
     */
    public function sendPushNotification($fcmToken, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::error("FCM Error: Failed to generate access token.");
            return false;
        }

        $projectId = config('services.fcm.project_id');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => array_map('strval', $data), // FCM v1 requires all data values to be strings
                ],
            ]);

            if (!$response->successful()) {
                Log::error("FCM v1 Error: " . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("FCM Request Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Manually generate an OAuth2 Access Token using JWT (No Google SDK needed)
     */
    protected function getAccessToken()
    {
        // Cache token for 55 minutes (it lasts 60)
        return Cache::remember('fcm_access_token', 3300, function () {
            $credentialsPath = base_path(config('services.fcm.credentials_file'));

            if (!file_exists($credentialsPath)) {
                Log::error("FCM Credentials file not found at: " . $credentialsPath);
                return null;
            }

            $credentials = json_decode(file_get_contents($credentialsPath), true);
            $now = time();

            // 1. Create JWT Header
            $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
            
            // 2. Create JWT Claim Set
            $claim = json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/cloud-platform',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ]);

            // 3. Base64Url Encode
            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlClaim = $this->base64UrlEncode($claim);

            // 4. Sign and Encode
            $signature = '';
            openssl_sign(
                $base64UrlHeader . "." . $base64UrlClaim,
                $signature,
                $credentials['private_key'],
                'SHA256'
            );
            $base64UrlSignature = $this->base64UrlEncode($signature);

            $jwt = $base64UrlHeader . "." . $base64UrlClaim . "." . $base64UrlSignature;

            // 5. Exchange JWT for Access Token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error("FCM Token Exchange Failed: " . $response->body());
            return null;
        });
    }

    private function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
