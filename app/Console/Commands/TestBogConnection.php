<?php

namespace App\Console\Commands;

use App\Services\BogPaymentService;
use Illuminate\Console\Command;

class TestBogConnection extends Command
{
    protected $signature = 'bog:test';
    protected $description = 'Test BOG API connection and authentication';

    public function handle(BogPaymentService $bogService)
    {
        $this->info('Testing BOG API connection...');
        $this->newLine();

        // Check configuration
        $this->info('Configuration:');
        $this->line('Base URL: ' . config('services.bog.base_url', 'NOT SET'));
        $this->line('Client ID: ' . (config('services.bog.client_id') ? '✓ SET' : '✗ NOT SET'));
        $this->line('Secret Key: ' . (config('services.bog.secret_key') ? '✓ SET' : '✗ NOT SET'));
        $this->newLine();

        if (!config('services.bog.client_id') || !config('services.bog.secret_key')) {
            $this->error('BOG credentials are not configured!');
            $this->info('Please run: bash setup-bog-credentials.sh');
            return Command::FAILURE;
        }

        // Test authentication
        try {
            $this->info('Testing authentication...');
            $authUrl = 'https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token';
            $this->line('Calling: ' . $authUrl);
            $this->newLine();
            
            // Create Basic Auth header
            $basicAuth = base64_encode(config('services.bog.client_id') . ':' . config('services.bog.secret_key'));
            
            // Test the API call directly with better error handling
            $response = \Illuminate\Support\Facades\Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . $basicAuth,
                ])
                ->post($authUrl, [
                    'grant_type' => 'client_credentials',
                ]);
            
            $this->line('Response Status: ' . $response->status());
            $this->line('Response Body: ' . $response->body());
            $this->newLine();
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['access_token'])) {
                    $this->info('✓ Authentication successful!');
                    $this->line('Token: ' . substr($data['access_token'], 0, 30) . '...');
                    $this->line('Expires in: ' . ($data['expires_in'] ?? 'unknown') . ' seconds');
                    return Command::SUCCESS;
                } else {
                    $this->error('✗ No access_token in response');
                    $this->line('Response data: ' . json_encode($data, JSON_PRETTY_PRINT));
                    return Command::FAILURE;
                }
            } else {
                $this->error('✗ HTTP request failed');
                $this->error('Status: ' . $response->status());
                $this->error('Body: ' . $response->body());
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Exception occurred!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            if ($this->option('verbose')) {
                $this->line('Trace: ' . $e->getTraceAsString());
            }
            return Command::FAILURE;
        }
    }
}

