<?php

namespace App\Console\Commands;

use Mail;
use App\User;
use Illuminate\Console\Command;
use App\Mail\RegisterSuccessMailable;

class SendMailRegisterSuccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email of registered users';

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
        $users = User::all();
        foreach ($users as $user ) {
          Mail::to($user->email)->send(new RegisterSuccessMailable($user->name));
        }
    }
}
