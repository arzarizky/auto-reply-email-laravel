<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;
use App\Models\MailServer;
use App\Models\EmailContent;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // seed role admin
        $roleAdmin = Roles::create([
            'name'	=> 'Admin',
        ]);

        // get id role admin
        $idAdmin = $roleAdmin->id;

        // seed role client
        $rolesClient = Roles::create([
            'name'	=> 'Karyawan',
        ]);

        // get id role Client
        $idClient =  $rolesClient->id;

        //seed admin
        $createUserAdmin = User::Create([
            'name' => 'Teknisi',
            'role_id' => $idAdmin,
            'email' => 'teknisi@dwalaw.co.id',
            'password' => bcrypt('Teknisi@12345'),
        ]);

        //seed user client
        $createUserKaryawan= User::Create([
            'name' => 'Arza Rizky Nova Ramadhani',
            'role_id' => $idClient,
            'email' => 'arzarizky@dwalaw.co.id',
            'password' => bcrypt('Arzarizky@12345'),
        ]);

        MailServer::create([
            "user_id" => $createUserAdmin->id,
            "host" => "mail.dwalaw.co.id",
            "port" => 	993,
            "encryption" => "ssl",
            "username" => $createUserAdmin->email,
            "password" => "secret",
        ]);

        EmailContent::create([
            "user_id" => $createUserAdmin->id,
            "subject" => "Sesuaikan Subject Auto Reply",
            "body" => 	"Sesuaikan Body Auto Reply",
            "start_auto_replied" => "2024/10/19",
            "end_auto_replied" => "2024/10/20"
        ]);


        MailServer::create([
            "user_id" => $createUserKaryawan->id,
            "host" => "mail.dwalaw.co.id",
            "port" => 	993,
            "encryption" => "ssl",
            "username" => $createUserKaryawan->email,
            "password" => "Arzarizky@12345",
        ]);

        EmailContent::create([
            "user_id" => $createUserKaryawan->id,
            "subject" => "Sesuaikan Subject Auto Reply",
            "body" => 	"Sesuaikan Body Auto Reply",
            "start_auto_replied" => "2024/10/19",
            "end_auto_replied" => "2024/10/20"
        ]);
    }
}
