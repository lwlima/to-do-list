<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-admin {email : The email of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an administrator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário '$email' não encontrado.");
            return;
        }

        if($user->is_admin){
            $this->error("Usuário '$email' já é um administrador.");
            return;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("usuário '$email' agora é um administrador.");
    }
}
