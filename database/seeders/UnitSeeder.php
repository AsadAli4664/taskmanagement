<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Define seed data
        $units = [
            'id' => 'Unit A',
            'unit_name' => 'Application',
            'unit_head' => 'CTO',
            'created_at' => now(),
            'updated_at' => now(),
            // Add more units as needed
        ];

       // \App\Models\Unit::factory(5)->create();
    }
}
