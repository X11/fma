<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@fma.com',
            'password' => bcrypt('feedingmyaddiction'),
            'role' => 5
        ]);

        if (env('local')){
            factory(App\User::class, 40)->create([
                'password' => bcrypt('testpass')
            ]);
        }
    }
}
