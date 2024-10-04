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
            'name' => "leonardo Espinosa Rivera",
            'cedula' => "1097092599",  // Agrega cedula 
            'email' => "riveraleo113@gmail.com",
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
            'name' => "Comunicacion.MXM",
            'cedula' => "1097092588", 
            'email' => "comunicacion@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 3,
            'is_active' => true,

        ]);
        
        $user = User::create([
            'name' => "AdministradorMXM",
            'cedula' => "27951191", 
            'email' => "talentohumanomxm@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 1,
            'is_active' => true,

        ]);



        /*** EJEMPLO SEEDER PARA LAS PAGINACIONES DEL ANGULAR */

        $user = User::create([
            'name' => "pedro",
            'cedula' => "1097092500",  // Agrega cedula 
            'email' => "riveraledad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);
        
        $user = User::create([
            'name' => "camilo",
            'cedula' => "1097092501",  // Agrega cedula 
            'email' => "riveraledadfskfd@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "perrrr",
            'cedula' => "1097092502",  // Agrega cedula 
            'email' => "riveraleqqdad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "QQQ",
            'cedula' => "1097092503",  // Agrega cedula 
            'email' => "riveraledSDad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "rope",
            'cedula' => "1097092504",  // Agrega cedula 
            'email' => "riverqealedad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "rett",
            'cedula' => "1097092505",  // Agrega cedula 
            'email' => "rivedsraledad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "pepe",
            'cedula' => "1097092506",  // Agrega cedula 
            'email' => "riveraledalalad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "pedddro",
            'cedula' => "1097092533",  // Agrega cedula 
            'email' => "riveraledads@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);
        $user = User::create([
            'name' => "pedssro",
            'cedula' => "1097092522",  // Agrega cedula 
            'email' => "rivad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);
        $user = User::create([
            'name' => "pedroe",
            'cedula' => "1097092530",  // Agrega cedula 
            'email' => "riverald@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "lelleelf",
            'cedula' => "1092092500",  // Agrega cedula 
            'email' => "rivealedad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);

        $user = User::create([
            'name' => "qqqq",
            'cedula' => "1097092592",  // Agrega cedula 
            'email' => "river22aledad@gmail.com",
            'password' => Hash::make('123456'),
            'rol_id' => 2,
            'is_active' => true,

        ]);


        
     }
}
