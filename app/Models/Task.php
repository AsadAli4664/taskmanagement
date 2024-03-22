<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'task';
    protected $fillable = [
        'title',
        'description',
        'duedate',
        'priority',
        // 'project',
        'assigned_to',
        // Add other attributes as needed
        'created_by',
    ];

    public function task_project()
    {
        return $this->belongsTo(Project::class,'project');
    }

    public function task_manager()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }
    public function task_assign()
    {
        return $this->belongsTo(TaskAssignment::class,'id','task_id');
    }
    public function task_creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function subtasks()
    {
      return $this->hasMany(SubTask::class);
    }   
    public function taskAssignments()
{
    return $this->hasMany(TaskAssignment::class);
}
public function collaborators()
{
    return $this->belongsToMany(User::class, 'id');
}

}
