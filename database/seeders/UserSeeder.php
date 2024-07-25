<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando 10 usuários aleatórios.');
        User::factory()->count(10)->create();
        $this->command->info('Usuários criados com sucesso.');
    }
}
