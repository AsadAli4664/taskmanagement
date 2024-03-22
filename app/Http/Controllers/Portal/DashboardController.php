<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Order;
use App\Models\Category;
use App\Models\Group;
use App\Models\Application;
use App\Models\Tag;
use App\Models\Product;
use App\Models\User;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            //'total_subtasks' => SubTask::count(),
            //'total_projects' => 0,
            //'total_tasks' => 0,
            'total_subtasks' => 0,
        ];

        $session_id = auth()->user()->id;
        $tasks_chart = Task::where('assigned_to', $session_id)->get();
        $tasks = Task::orderBy('created_at', 'desc')->get();
        $taskschartlimit = Task::orderBy('created_at', 'desc')->take(10)->get();
        $users = User::all();
        $currentUser = auth()->user();
        $id = auth()->user()->id;
        $tasksa = DB::select("
        (
            SELECT DISTINCT task_id
            FROM (
                SELECT 
                    task_id, 
                    FIND_IN_SET('$id', REPLACE(REPLACE(REPLACE(collaborators, '\"', ''), '[', ''), ']', '')) AS collab 
                FROM 
                    task_assignment
            ) AS res 
            WHERE 
                collab > 0
        )
        UNION
        (
            SELECT DISTINCT id
            FROM task
            WHERE assigned_to = '$id'
        )
    ");
        // Extract task IDs from the result of the raw SQL query
        $taskIdsFromSQL = array_column($tasksa, 'task_id');

// Use the extracted task IDs in the whereIn condition
$alltask = Task::whereIn("id", $taskIdsFromSQL)->orderBy('created_at', 'desc')->get();
$chartsforspecificuser = Task::whereIn("id", $taskIdsFromSQL)->orderBy('created_at', 'desc')->take(10)->get();
$tasksstats = DB::select("
        (
            SELECT DISTINCT task_id
            FROM (
                SELECT 
                    task_id, 
                    FIND_IN_SET('$id', REPLACE(REPLACE(REPLACE(collaborators, '\"', ''), '[', ''), ']', '')) AS collab 
                FROM 
                    task_assignment
            ) AS res 
            WHERE 
                collab > 0
        )
        UNION
        (
            SELECT DISTINCT id
            FROM task
            WHERE assigned_to = '$id' OR created_by = '$id'
        )
    ");
        // Extract task IDs from the result of the raw SQL query
        $taskIdsFromSQLstat = array_column($tasksstats, 'task_id');

// Use the extracted task IDs in the whereIn condition
$alltaskstats = Task::whereIn("id", $taskIdsFromSQLstat)->orderBy('created_at', 'desc')->get();
$notification = Task::whereIn("id", $taskIdsFromSQL)
                ->whereDate('created_at', today())
                ->get();

      

// Assuming you have a model named Task
$taskss = Task::where('assigned_to', $id)->orderBy('created_at', 'desc')->get();
    
       // return view('portal.subtasks.index',compact('tasks'));
        return view('portal.dashboard', compact('data', 'tasks', 'taskschartlimit', 'alltaskstats', 'users', 'alltask', 'chartsforspecificuser', 'notification'));


    }
    public function userdashboard($id)
    {

        $data = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::where('assigned_to', $id)->get()->count(),
            'completed_tasks' => Task::where('assigned_to', $id)
                ->where('progress', 100)
                ->get()->count(),
            'pending_tasks' => Task::where('assigned_to', $id)
                ->where('progress', '<', 100)
                ->get()->count(),
            //'total_subtasks' => SubTask::count(),
            //'total_projects' => 0,
            //'total_tasks' => 0,
            'total_subtasks' => 0,
        ];
        $permissionResult = check_permission('view_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }

        $tasks_chart = Task::where('assigned_to', $id)->get();
        $tasks = Task::all();
        $users = User::all();
        $idd = $id;
        $taskss = Task::where('assigned_to', $id)->get();
        $tasksa = DB::select("
        (
            SELECT DISTINCT task_id
            FROM (
                SELECT 
                    task_id, 
                    FIND_IN_SET('$id', REPLACE(REPLACE(REPLACE(collaborators, '\"', ''), '[', ''), ']', '')) AS collab 
                FROM 
                    task_assignment
            ) AS res 
            WHERE 
                collab > 0
        )
        UNION
        (
            SELECT DISTINCT id
            FROM task
            WHERE assigned_to = '$id'
        )
    ");
        // Extract task IDs from the result of the raw SQL query
        $taskIdsFromSQL = array_column($tasksa, 'task_id');

        // Use the extracted task IDs in the whereIn condition
        $alltask = Task::whereIn("id", $taskIdsFromSQL)->get();
        // return view('portal.subtasks.index',compact('tasks'));
        return view('portal.userdashboard', compact('data', 'tasks', 'users', 'taskss', 'alltask', 'tasks_chart', 'idd'));
    }

     /* when click on user dashboard stats   */
     public function userincompleted(){
        $permissionResult = check_permission('view_task');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $userId = auth()->user()->id;
        $projects=Project::all();
        $users=User::all();
        $userassign=User::where("unit_id", $userId)->get();
        $tasks = Task::orderBy('created_at', 'desc')->get();


        $tasksa = DB::select("
    (
        SELECT DISTINCT task_id
        FROM (
            SELECT 
                task_id, 
                FIND_IN_SET('$userId', REPLACE(REPLACE(REPLACE(collaborators, '\"', ''), '[', ''), ']', '')) AS collab 
            FROM 
                task_assignment
        ) AS res 
        WHERE 
            collab > 0
    )
    UNION
    (
        SELECT DISTINCT id
        FROM task
        WHERE assigned_to = '$userId'
    )
");

    
    // Extract task IDs from the result of the raw SQL query
        $taskIdsFromSQL = array_column($tasksa, 'task_id');

    // Use the extracted task IDs in the whereIn condition
        $usertask = Task::whereIn("id", $taskIdsFromSQL)->get();
            
        return view('portal.task.userincompleted',compact('projects','users', 'userassign', 'tasks', 'usertask'));
    }

    /* end user dashboard stats functions */

}
