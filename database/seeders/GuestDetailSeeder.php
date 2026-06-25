<?php

namespace Database\Seeders;

use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::role('Guest')->get();

        foreach ($patients as $index => $user) {
            GuestDetail::create([
                'guest_id'       => $user->id,
                'code_item'       => '05468',
                'birth_city'       => 'Torino',
                'birth_date'       => '17-05-1986',
                'tax_id_code'   => 'sdf56gddsf454',
                'gender'       => 'maschio',
                'phone'       => '3243244324',
                'medical_history'       => 'asdf dsafdsaf dsafads dfasdsasdadsff asdfasd saddsd',
                'blood_type'       => 'B',
                'allergies'       => 'sadfsadf, asdfdsafm mdsafasf,dsfsadf',
            ]);
        }
    }
}
