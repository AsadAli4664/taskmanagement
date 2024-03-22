<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_arr = [
            [
                'created_at'=>now(),
                'name'=>'Software Development',
                
            ],
            [
                'created_at'=>now(),
                'name'=>'Procuremnet',
            ],
        ];

        Category::insert($obj_arr);
    }
}
