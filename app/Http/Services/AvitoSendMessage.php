<?php

namespace App\Http\Services;

use App\Jobs\AvitoSendMessages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AvitoSendMessage
{

    private string  $userId;

    private string $token;
    public function __construct()
    {
        $this->userId = config('services.avito.my_id');
        $this->token = (string) Cache::get('access_token');
    }

    public function sendMessage()
    {

        $token = Cache::get('access_token');


        $response = Http::timeout(30)->retry(3, 500)->withHeader('Authorization',"Bearer $this->token")
            ->withUrlParameters([
                'domen' => 'https://api.avito.ru',
                'user_id' => $this->userId
            ])
            ->get('{+domen}/messenger/v2/accounts/{user_id}/chats',[
                'unread_only' => 'true',
                'chat_types' => 'u2i,u2u'
            ]);

        if ($response->failed()) {
            $this->refreshToken();
            return;
        }



        $col = json_decode($response->getBody()->getContents(), true);
        $chats = $col['chats'][0]['id'] ?? null;
        if (!empty($chats)){

            for ($i = 0; $i < count($col['chats']); $i++){
                $chatId = $col['chats'][$i]['id'];
                $lastMessage = $this->countMessages($chatId);
                $firstMessage = false;
                if ($lastMessage === null){
                    $sendMessage = 'Здравствуйте. 🤝 Вас оптом интересует? Какие-то вопросы есть у вас? Напишите пожалуйста нам сюда на WhatApp. Наши номера для связи

        https://wa.me/79934156684
        https://wa.me/79953664423

        Это бот, менеджер подключится чуть позже

        Ecли вам необходим прайс - напишите "Прайс"

        ';
                    $firstMessage = true;
                }
                else{
                    if (mb_strtolower($lastMessage) === 'прайс'){
                        $sendMessage = 'https://drive.google.com/file/d/1_aPD5pxwKAUyJxyDejoMK5X3whbSlu54/view?usp=drive_link';
                    }
                    else{
                        $sendMessage = CallGigaChatAI::call($lastMessage);
                    }

                }
                //$lastMessage = $col['chats'][$i]['last_message']['content']['text'];


                Http::timeout(30)->retry(3, 500)->withHeaders([
                    'Authorization' => "Bearer $token",
                ])
                    ->withUrlParameters([
                        'domen' => 'https://api.avito.ru',
                        'user_id' => $this->userId,
                        'chat_id' => $chatId
                    ])
                    ->post('{+domen}/messenger/v1/accounts/{user_id}/chats/{chat_id}/read');
                Http::withHeaders([
                    'Authorization' => "Bearer $token",
                ])
                    ->withUrlParameters([
                        'domen' => 'https://api.avito.ru',
                        'user_id' => $this->userId,
                        'chat_id' => $chatId
                    ])
                    ->post('{+domen}/messenger/v1/accounts/{user_id}/chats/{chat_id}/messages',[
                        'message' => [
                            'text' => "$sendMessage",
                        ],
                        'type' => 'text',
                    ]);
                if ($firstMessage === true){
                    Http::timeout(30)->retry(3, 500)->withHeaders([
                        'Authorization' => "Bearer $token",
                    ])
                        ->withUrlParameters([
                            'domen' => 'https://api.avito.ru',
                            'user_id' => $this->userId,
                            'chat_id' => $chatId
                        ])
                        ->post('{+domen}/messenger/v1/accounts/{user_id}/chats/{chat_id}/messages',[
                            'message' => [
                                'text' => "",
                            ],
                            'type' => 'text',
                        ]);
                }

            }
        }
        AvitoSendMessages::dispatch()->delay(now()->addSeconds(3));
    }

    protected function countMessages( string $chatId): ?string
    {
        $arr = [];
        $response = Http::timeout(30)->retry(3, 500)->withHeader('Authorization',"Bearer $this->token")
            ->withUrlParameters([
                'domen' => 'https://api.avito.ru',
                'user_id' => $this->userId,
                'chat_id' => $chatId
            ])
            ->get('{+domen}/messenger/v3/accounts/{user_id}/chats/{chat_id}/messages/',[
                'limit' => 2
            ]);
        $res = $response->json();
        foreach ($res['messages'] as $key => $message){
            if ($message['type'] !== 'system'){
                $arr[$key] = $message;
            }
        }
        if (count($arr) > 1){
            return $res['messages'][0]['content']['text'];
        }
        return null;
    }

    private function refreshToken(): void
    {
        $client = config('services.avito.client');
        $secret = config('services.avito.secret');
        $refresh = Cache::get('refresh_token');
        if (!$refresh) {
            $token = Http::timeout(30)->retry(3, 500)->asForm()->withUrlParameters([
                'domen' => 'https://api.avito.ru',
            ])
                ->post('{+domen}/token',[
                    'client_id' => $client,
                    'client_secret' => $secret,
                    'grant_type' => 'authorization_code',
                    'code' => 'DQ9rNuugSyO1no8Y1xsGQg'

                ]);

            if ($token->failed()) {
                \Log::error('Ошибка обновления токена Avito через authorization_code: ' . $token->body());
                return;
            }


        }

        else{
            $token = Http::timeout(30)->retry(3, 500)->asForm()->withUrlParameters([
                'domen' => 'https://api.avito.ru',
            ])
                ->post('{+domen}/token',[
                    'client_id' => $client,
                    'client_secret' => $secret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh
                ]);
            if ($token->failed()) {
                \Log::error('Ошибка обновления токена Avito через refresh_token: ' . $token->body());
                return;
            }
        }

        $json = $token->json();
        dump(Cache::get('access_token'));
        Cache::put('access_token' , $json['access_token'], now()->addDay());
        Cache::put('refresh_token' , $json['refresh_token']);
        AvitoSendMessages::dispatch()->delay(now()->addSeconds(3));
    }
}
