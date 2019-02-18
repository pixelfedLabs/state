<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating a new user...');
        $name = $this->ask('Name');
        $email = $this->ask('Email');

        if(User::whereEmail($email)->exists()) {
            $this->error('Email already in use, please try again...');
            exit;
        }

        $password = $this->secret('Password');
        $confirm = $this->secret('Confirm Password');

        if($password !== $confirm) {
            $this->error('Password mismatch, please try again...');
            exit;
        }

        if($this->confirm('Are you sure you want to create this user?') && $name && $email && $password) {
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->email_verified_at = now();
            $user->save();

            $this->info('Created new user!');
        }
    }
}
