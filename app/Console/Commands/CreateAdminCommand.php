<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si el usuario admin ya existe
        $user = User::where('email', 'admin@michelin.com')->first();

        if ($user) {
            $this->info('✓ El usuario admin ya existe.');
            $this->line('   Email: ' . $user->email);
            return 0;
        }

        // Crear el usuario admin
        try {
            User::create([
                'nombre' => 'Admin',
                'apellido1' => 'Michelin',
                'apellido2' => '',
                'nombre_del_atributo' => 'admin',
                'email' => 'admin@michelin.com',
                'password_hash' => Hash::make('admin123'),
                'telefono' => '600000000',
                'nacimiento' => '1990-01-01',
                'estado' => 'activo',
                'id_rol' => 1,
            ]);

            $this->info('✓ Usuario administrador creado correctamente!');
            $this->line('   Email: admin@michelin.com');
            $this->line('   Contraseña: admin123');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Error al crear el usuario: ' . $e->getMessage());
            return 1;
        }
    }
}
