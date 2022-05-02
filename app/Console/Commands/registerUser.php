<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class registerUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropspace:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('What should the username be?');
        $email = $this->ask('What should the email be?');
        $password = $this->secret('What should the password be? (The input is hidden)');
        $this->info('Creating the user...');
        //Check if the user already exists
        $user = User::where('email', $email)->first();
        if ($user) {
            $this->error('User already exists!');
            return 1;
        }
        //Create the user
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
        $this->info('User created successfully.');
        return 0;
    }
}