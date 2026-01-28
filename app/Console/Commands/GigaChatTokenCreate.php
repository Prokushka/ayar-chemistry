<?php

namespace App\Console\Commands;

use App\Jobs\GigaChatRefreshToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GigaChatTokenCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigachat:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a token for gigachat API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $secretkey = config('services.gigachat.secret');
        $clientKey = config('services.gigachat.client');
        $authKey = trim(base64_encode("$clientKey:$secretkey"));
        $uuid = (string) Str::uuid();
        try {
            $response = Http::timeout(30)
                ->retry(3, 500)
                ->asForm()
                ->withHeaders([
                    'Authorization' => "Basic $authKey",
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'RqUID' => $uuid,
                ])
                ->withOptions(['verify' => false])
                ->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', [
                    'scope' => 'GIGACHAT_API_PERS',
                ]);
            $token = $response->json();

            Cache::put('gigachat_token', $token['access_token']);

            echo 'token has been planted
            ';
            GigaChatRefreshToken::dispatch()->delay(now()->addMinutes(25));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

    }
}
