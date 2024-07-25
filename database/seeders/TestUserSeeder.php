<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Criando usuário teste com o email 'test@test.com'.");
        $user = User::where('email', 'test@test.com')->first();
        if ($user) {
            $this->command->info('Usuário teste já existe, nenhum novo usuário criado.');
            return;
        }

        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        if($user)
            $this->command->info('Usuário teste criado com sucesso.');
        else
        $this->command->info('Erro ao criar usuário teste.');

    }
}
