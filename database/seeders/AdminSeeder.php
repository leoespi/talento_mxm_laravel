<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Seeder;


class AdminSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()
     {
        $user = User::create([
            'name' => "leonardo",
            'cedula' => "1097092599",  // Agrega cedula 
            'email' => "eleo@gmail.com",
            'password' => bcrypt('123456'),
            'rol_id' => 1,

        ]);

        $user = User::create([
            'name' => "david",
            'cedula' => "27951193", // Agrega el nombre de usuario aquí
            'email' => "david@gmail.com",
            'password' => bcrypt('davidmxm123'),
            
            'rol_id' => 1,

        ]);
        
     }
}
