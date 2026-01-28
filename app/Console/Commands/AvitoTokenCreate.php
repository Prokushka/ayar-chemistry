<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AvitoTokenCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:token {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a refresh_token';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (!$this->argument('token')) {
            $this->error('Token is missing');
            return Command::FAILURE;
        }
        $client = config('services.avito.client');
        $secret = config('services.avito.secret');
        $token = Http::asForm()->withUrlParameters([
            'domen' => 'https://api.avito.ru',
        ])
            ->post('{+domen}/token',[
                'client_id' => $client,
                'client_secret' => $secret,
                'grant_type' => 'authorization_code',
                'code' => $this->argument('token')

            ]);

        if ($token->failed()) {
            \Log::error('Ошибка обновления токена Avito через authorization_code: ' . $token->body());
            $this->error('Ошибка получения токена');
            return Command::FAILURE;
        }

        if (!Cache::has('access_token')){
            $json = $token->json();
            Log::info($json);
            Cache::put('access_token' , $json['access_token'], now()->addDay());
            Cache::put('refresh_token' , $json['refresh_token']);
            return Command::SUCCESS;
        }
    }
}
