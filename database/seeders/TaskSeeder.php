<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando 20 tarefas aleatórias para cada usuário.');

        User::all()->each(function ($user) {
            Task::factory()->count(20)->create(['user_id' => $user->id]);
        });
        
        $this->command->info('Tarefas criadas com sucesso.');
    }
}
