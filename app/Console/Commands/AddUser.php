<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-user {email} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //println($this->argument('email'));
        $user = new User();
        $user->email = $this->argument('email');
        $user->name = $this->argument('name');
        $user->save();
    }
}
