<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // Role comes before User seeder here.
        $this->call('RoleTableSeeder');
        // User seeder will use the roles above created.
        $this->call('UserTableSeeder');
        // Country seeder
        $this->call('CountriesTableSeeder');
        // Company seeder to add other column
        $this->call('CompanyTableSeeder');
        // Category seeder
        $this->call('CategoriesTableSeeder');
    }
    
}
