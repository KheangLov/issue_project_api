<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=AddressSeeder
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(Storage::disk('generator')->get('addresses_01.sql'));
        DB::unprepared(Storage::disk('generator')->get('addresses_02.sql'));
    }
}
