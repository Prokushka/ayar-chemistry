<?php

namespace App\Console\Commands;

use App\Http\Services\CallGigaChatAI;
use Illuminate\Console\Command;

class GigaChatCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigachat:call {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call a gigachat API with prompt';

    /**
     * Execute the console command.
     */
    public function handle()
    {

       return (new CallGigaChatAI())->call($this->argument('message'));
    }
}
