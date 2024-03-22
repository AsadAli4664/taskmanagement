<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;
    protected $table = 'task_assignment';
    protected $fillable = [
        'project_id',
        'collaborators',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id'); // Specify the foreign key ('task_id')
    }
}


