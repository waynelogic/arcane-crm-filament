<?php

namespace App\Console\Commands;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Console\Command;

class CrmInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Установка CRM');
        $this->call('migrate');
        $this->createAdmin();
        $this->info('Установка CRM завершена');
    }

    private function createAdmin()
    {
        $obAdmin = Manager::query()->where('email', 'info@albus-it.ru')->firstOrNew();
        $obAdmin->fill([
            'name' => 'Александр Пригода',
            'email' => 'info@albus-it.ru',
            'email_verified_at' => now(),
            'password' => bcrypt('Albus2020')
        ]);
        $obAdmin->save();
        $this->info('Создание администратора завершено');
    }
}
