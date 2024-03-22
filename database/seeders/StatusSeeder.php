<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Models
use App\Models\Status;
class StatusSeeder extends Seeder
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
                'name'=>'Pending',
                
            ],
            [
                'created_at'=>now(),
                'name'=>'Owned',
            ],
            
            [
                'name'=>'In-Progress',
                'created_at'=>now(),
            ],
            
            [
                'name'=>'Blocked',
                'created_at'=>now(),
            ],
            
            [
                'name'=>'In-review',
                'created_at'=>now(),

            ],
            
            [
                'name'=>'Completed',
                'created_at'=>now(),
            ],
            [
                'name'=>'On-hold',
                'created_at'=>now(),
            ],
            
            [
                'name'=>'Un-assigned',
                'created_at'=>now(),
            ]
        ];

        Status::insert($obj_arr);
    }
}
