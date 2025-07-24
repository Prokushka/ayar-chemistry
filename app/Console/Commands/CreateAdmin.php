<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {phone=89046633643}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make user Admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $user = User::query()->where('phone', $this->argument('phone'));

       $user->update([
           'user_role' => 1
       ]);
    }
}
