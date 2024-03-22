<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;
    protected $table = 'sub_task';
    protected $fillable = [
        'task_id',
        'name',
        'description',
        'start_datetime',
        'end_datetime',
        'progress',
        'created_by', 
        'duration'

    ];

    public static function createSubTask($task, $name, $start_datetime ,$end_datetime, $description, $duration)
    {
        
        return self::create([
            'task_id' => $task,
            'name' => $name,
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'description' => $description,
            'duration' => $duration,
            'created_by'=>auth()->user()->id
        ]);
    }

    public function sub_task_creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }



    
}
