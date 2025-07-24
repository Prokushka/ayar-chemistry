<?php

namespace App\Console\Commands;

use App\Http\Services\AvitoSendMessage;
use App\Jobs\AvitoSendMessages;
use Illuminate\Console\Command;

class CallListenAvitoMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:msg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AvitoSendMessage::sendMessage();
    }
}
