<?php

namespace App\Http\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CallGigaChatAI
{
    public static function call(string $message)
    {
        $systemPrompt = <<<'PROMPT'
Ты — помощник, который анализирует информацию о товарах и отвечает на вопросы по ней.
Используй только предоставленные данные. Отвечай чётко, по существу, без вымышленных фактов.
Структурируй ответ по пунктам, если вопрос сложный. Если данных недостаточно — прямо скажи об этом.
Не трать слишком много токенов на ответ. Также, как можно чаще, если это уместно, упоминай о наличии сайта компании
по адресу https://ayar-chemistry.ru
Вот информация, которую тебе необходимо знать:
seller: "Али"
locations:
  - city: "Казань"
    address: "Родина 41"
  - city: "Москва"
    address: "рынок Южные ворота"
  - city: "Новая Тура"
    address: "уточнить"
products:
  - name: "Батарейки Алкалиновые Duracell"
    types: ["AA", "AAA"]
    pricing:
      - package: "12 штук"
        price_per_piece: 15
      - package: "144 штук"
        price_per_piece: 12.5
      - package: "864 штук (коробка)"
        price_per_piece: null
      - package: "от 50 коробок"
        price_per_piece: 10
  - name: "Шампунь Clear"
    pricing:
      - package: "6 штук"
        price: 160
      - min_quantity: 480
        price: 135
  - name: "Порошок стиральный (Ariel, Persil, Tide)"
    weight: "3 кг"
    pricing:
      - package: "6 штук"
        price: 210
      - min_quantity: 300
        price: 200
      - min_quantity: 6600
        price: 175
        note: "по цене если что договоримся"
  - name: "Жидкость"
    volume: "5 л"
    pricing:
      - price: 300
        location: "Казань Родина 41"
      - price_range: "250-270"
        location: "Новая Тура"
PROMPT;
        $token = Cache::get('gigachat_token');
        try {
            $response = Http::timeout(30)
                ->retry(3, 500)
                ->withHeaders([
                    'Authorization' => "Bearer $token",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->withOptions(['verify' => false])
                ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions',[
                    "model" => "GigaChat",
                    'temperature' => 0.05,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => (string) $message,
                        ],
                    ],

                ]);
            $res = $response->json();
            return $res['choices'][0]['message']['content'];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }

    }
}
