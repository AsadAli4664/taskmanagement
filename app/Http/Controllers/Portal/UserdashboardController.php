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
class UserdashboardController extends Controller
{
    
    public function index($id)
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
        $tasks = Task::orderBy('created_at', 'desc')->get();
        $users = User::all();
        $idd = $id;
        $taskss = Task::where('assigned_to', $id)->orderBy('created_at', 'desc')->get();
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
        $taskchart = Task::whereIn("id", $taskIdsFromSQL)->orderBy('created_at', 'desc')->take(10)->get();
        // return view('portal.subtasks.index',compact('tasks'));
        return view('portal.userdashboard.index', compact('data', 'tasks', 'users', 'taskss', 'alltask', 'taskchart', 'tasks_chart', 'idd'));
    }

     /* when click on user dashboard stats   */
     public function userincompleted($id){
        $permissionResult = check_permission('view_task');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $userId = $id;
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
            
        return view('portal.userdashboard.userincompleted',compact('projects','users', 'userassign', 'tasks', 'usertask'));
    }

    public function useralltasks($id){
        $permissionResult = check_permission('view_task');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $userId = $id;
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
            
        return view('portal.userdashboard.useralltasks',compact('projects','users', 'userassign', 'tasks', 'usertask'));
    }

    public function completedtasks($id){
        $permissionResult = check_permission('view_task');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $userId = $id;
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
            
        return view('portal.userdashboard.completed',compact('projects','users', 'userassign', 'tasks', 'usertask'));
    }

}
