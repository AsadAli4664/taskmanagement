<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'start_date',
        'due_date',
        'manager',
        'status',
        'category',
        'created_by',
    ];
    public static function createProject($name,$location,$start_date,$due_date,$manager,$category,$status)
    {
        return self::create([
            'name' => $name,
            'location' => $location,
            'start_date'=>Carbon::parse($start_date),
            'due_date' => Carbon::parse($due_date),
            'manager' => $manager,
            'status' => $status,
            'category' => $category,
            'created_by' => auth()->user()->id,
        ]);
    }
    public function createdByUser()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function project_status()
    {
        return $this->belongsTo(Status::class,'status');
    }

    public function project_category()
    {
        return $this->belongsTo(Category::class,'category');
    }

    public function project_manager()
    {
        return $this->belongsTo(User::class,'manager');
    }



}
