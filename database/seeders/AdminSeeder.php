<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()
     {

        $user = User::create([
            'name' => "Betsy Katherine Taboada Caro",
            'cedula' => "1097092580", 
            'email' => "betsy.taboada@mxm.com.co",
            'password' => Hash::make('123456'),
            'rol_id' => 4,
            'is_active' => true,

        ]);
        

        $user = User::create([
            'name' => "leonardo",
            'cedula' => "1097092599",  // Agrega cedula 
            'email' => "eleo@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 1,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "david",
            'cedula' => "27951193", 
            'email' => "david@gmail.com",
            'password' => Hash::make('davidmxm123'),
            'rol_id' => 1,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "PruebaComunicacion",
            'cedula' => "1097092588", 
            'email' => "comunicacion@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 3,
            'is_active' => true,

        ]);
        
     }
}
