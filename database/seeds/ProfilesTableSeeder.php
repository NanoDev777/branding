<?php

use Illuminate\Database\Seeder;
use App\Profile;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = Profile::create(array(
            'description' => 'Administrador'
        ));
        $profile->actions()->sync([1]);
    }
}
