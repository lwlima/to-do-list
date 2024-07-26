# Projeto Laravel
Este projeto é uma aplicação Laravel que inclui um dashboard de administração com funcionalidades em tempo real e tarefas. Este README fornece instruções para configurar e executar o projeto localmente.

## Tecnologias Usadas
- **PHP:** 8.1
- **Laravel:** 11
- **Node.js:** 20.9.0
- **npm:** 10.1.0
- **Composer**
- **Banco de Dados:** MySQL
## Configuração do Projeto
### 1. Clone o Repositório
Primeiro, clone o repositório para sua máquina local:

``git clone https://github.com/SEU_USUARIO/SEU_REPOSITORIO.git``
``cd SEU_REPOSITORIO``

### 2. Instale as Dependências
Instale as dependências do PHP usando Composer:
``composer install``

Instale as dependências do front-end usando npm:
``npm install``

### 3. Configure o Arquivo .env
Copie o arquivo .env.example para um novo arquivo .env e configure as variáveis de ambiente:
``cp .env.example .env``

Edite o arquivo .env com as seguintes configurações:

#### Banco de Dados:
DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=nome_do_banco

DB_USERNAME=seu_usuario

DB_PASSWORD=sua_senha

#### Pusher:
Crie uma conta em Pusher e configure as seguintes variáveis:
PUSHER_APP_ID=seu_app_id

PUSHER_APP_KEY=seu_app_key

PUSHER_APP_SECRET=seu_app_secret

PUSHER_APP_CLUSTER=seu_app_cluster

### 4. Execute as Migrations e Seeders
Para preparar o banco de dados, execute as migrations e seeders:

``php artisan migrate``

``php artisan db:seed``

O seeder cria:

- 10 usuários aleatórios
- Um usuário admin com e-mail admin@admin.com e senha password
- Um usuário de teste com e-mail test@test.com e senha password
- 20 tarefas para cada usuário
### 5. Inicie o Job Queue
Alguns eventos do backend são processados via jobs. Inicie o worker da fila com o comando:

``php artisan queue:work``

### 6. Execute o Servidor Backend
Para rodar o backend da aplicação, execute:

``php artisan serve``
### 7. Execute o Frontend
Para rodar o front-end da aplicação, execute:

``npm run dev``

## Funcionalidades do Dashboard
O usuário admin tem acesso ao dashboard que inclui:

- Número total de tarefas
- Número total de tarefas concluídas
- Número total de tarefas pendentes

Essas métricas são atualizadas em tempo real usando Laravel Echo com Broadcast e Pusher.


## Notas Adicionais
- **Broadcast:** As atualizações em tempo real requerem uma conta Pusher. Configure as variáveis de ambiente PUSHER_APP_ID, PUSHER_APP_KEY, PUSHER_APP_SECRET, e PUSHER_APP_CLUSTER no arquivo .env.

- **Atividades Recentes:** O dashboard também exibe uma tabela de atividades recentes que mostra o usuário que fez a alteração e a tarefa em questão. Note que este item não está ativo com Broadcast.
