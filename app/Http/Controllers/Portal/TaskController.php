<?php

namespace App\Http\Controllers\Portal;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskAssignment;
use App\Models\SubTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCreated;


class TaskController extends Controller
{
    public function index()

    {
        $permissionResult = check_permission('view_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $userId = auth()->user()->id;
        $projects = Project::all();
        $users = User::all();
     
        $tasks = Task::orderBy('created_at', 'desc')->get();
        $tasksd = Task::orderBy('created_at', 'desc')->get();


       

        // Use the extracted task IDs in the whereIn condition
        $usertask = Task::orderBy('created_at', 'desc')->get();
        // $isValid = 0; // Initialize $isValid as false (indicating invalid)

        return view('portal.task.index', compact('projects', 'users', 'tasks', 'usertask', 'tasksd'));
    }

    public function searchTasks(Request $request)
    {
        $tasksQuery = Task::query();
    
        if ($request->has('crime_no') && !empty($request->crime_no)) {
            $tasksQuery->where('crime_no', $request->crime_no);
        }
    
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $tasksQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $tasks = $tasksQuery->get();
    
        return response()->json($tasks);
    }
    
    
    
    

    public function assignedtask()

    {
        $permissionResult = check_permission('view_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $userId = auth()->user()->id;
        $projects = Project::all();
        $users = User::all();
        $userassign = User::where("unit_id", $userId)->get();
        $tasks = Task::orderBy('created_at', 'desc')->get();
        $usertask = Task::orderBy('created_at', 'desc')->get();



        return view('portal.task.assignedtask', compact('projects', 'users', 'userassign', 'tasks', 'usertask'));
    }

    public function completed()
    {

        $permissionResult = check_permission('view_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $userId = auth()->user()->id;
        $projects = Project::all();
        $users = User::all();
        $userassign = User::where("unit_id", $userId)->get();
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

        return view('portal.task.completed', compact('projects', 'users', 'userassign', 'tasks', 'usertask'));
    }
    public function incompleted()
    {
        $permissionResult = check_permission('view_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $userId = auth()->user()->id;
        $projects = Project::all();
        $users = User::all();
        $userassign = User::where("unit_id", $userId)->get();
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

        return view('portal.task.incompleted', compact('projects', 'users', 'userassign', 'tasks', 'usertask'));
    }


    public function add()
    {
        $permissionResult = check_permission('add_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        return view('portal.task.add');
    }

    public function edit($id)
    {
        $permissionResult = check_permission('edit_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $task = Task::findorfail($id);
        $userId = auth()->user()->id;
        $userassign = User::where("unit_id", $userId)->get();
        $taskAssignment = TaskAssignment::where('task_id', $id)->first();
        return view('portal.task.edit', compact('task', 'userassign', 'taskAssignment'));
    }

    public function editcollab($id)
    {

        $task = Task::findorfail($id);
        $userId = auth()->user()->id;
        $userassign = User::where("unit_id", $userId)->get();
        $taskAssignment = TaskAssignment::where('task_id', $id)->first();
        return view('portal.task.editcollab', compact('task', 'userassign', 'taskAssignment'));
    }

    public function delete($id)
    {
     
        $task  = Task::where('id', $id)->delete();
        return redirect()->route('task.index')->with('success', 'Record deleted successfully');
    }

    public function list(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        $userId = auth()->user()->id;

        $totalRecords = Task::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Task::select('count(*) as allcount')
            ->where(function ($query) use ($searchValue, $userId) {
                $query->where('title', 'like', '%' . $searchValue . '%')
                    ->orWhere('description', 'like', '%' . $searchValue . '%')
                    ->orWhere('priority', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('task_project', function ($subquery) use ($searchValue) {
                        $subquery->where('name', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('task_creator', function ($subquery) use ($searchValue) {
                        $subquery->where('name', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('task_manager', function ($subquery) use ($searchValue) {
                        $subquery->where('name', 'like', '%' . $searchValue . '%');
                    });
            })
            ->unless(auth()->user()->hasPermissionTo('view_alltasks'), function ($query) {
                return $query->where('assigned_to', auth()->id());
            })
            ->count();

        $records = Task::where(function ($query) use ($searchValue, $userId) {
            $query->where('title', 'like', '%' . $searchValue . '%')
                ->orWhere('description', 'like', '%' . $searchValue . '%')
                ->orWhere('priority', 'like', '%' . $searchValue . '%')
                ->orWhereHas('task_project', function ($subquery) use ($searchValue) {
                    $subquery->where('name', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('task_creator', function ($subquery) use ($searchValue) {
                    $subquery->where('name', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('task_manager', function ($subquery) use ($searchValue) {
                    $subquery->where('name', 'like', '%' . $searchValue . '%');
                });
        })
            ->unless(auth()->user()->hasPermissionTo('view_alltasks'), function ($query) {
                return $query->where('assigned_to', auth()->id());
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $route = route('task.edit', $record->id);
            $delete_route = route('task.delete', $record->id);

            $data_arr[] = array(
                "id" => $record->id,
                "title" => $record->title,
                "due_date" => $record->duedate,
                "description" => $record->description,
                "priority" => $record->priority,
                // "project" => $record->task_project->name. " | ".$record->task_project->location ,
                "assigned_to" => $record->task_manager->name,
                "created_by" => $record->task_creator->name,
                "progress" => $record->progress,
                "action" => '<div class="btn-group">
                                <a href="' . $route . '" class="mr-1 text-info" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                              

                         
                        </div>'
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return \Response::json($response);
    }

    public function subtask_list(Request $request, $id)
    {
        $data = SubTask::all();
        $tasks = Task::where("id", $id)->get();
        $users = User::all();
        return view('portal.task.subtasks', compact('data', 'tasks', 'users'));
    }


    public function store(Request $request)
    {

        $permissionResult = check_permission('add_criminal_record');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $currentDayStart = now()->setTimezone('Asia/Karachi')->startOfDay();
        $request->validate([
            'crime_no' => 'required'
            
        ]);

        // $userEmail = 'A.Ali@psca.gop.pk'; // Replace this with the actual email
        $assigntoid=$request->assigned_to;
        $userEmail = User::whereIn('id', [$assigntoid])->pluck('email');
        $task = new Task([
            'crime_no' => $request->crime_no,
            'crime_section' => $request->crime_section,
            'criminal_address' => $request->criminal_address,
            'arrest_date' => $request->arrest_date,
            'remand' => $request->remand,
            'title' => $request->days,
            'arrest_by' => $request->arrest_by,
            'designation' => $request->designation,
            'condition' => $request->condition,
            'arrest_status' => $request->arrest_status,
            'created_by' => auth()->user()->id
            // Add other fields as needed
        ]);


        // Save the task to the database
        $task->save();

    

        return redirect()->route('task.index')->with(['success' => 'record entered successfully']);
    }
    public function update(Request $request, $id)
    {
   
        $request->validate([
            'crime_no' => 'required|max:1000', // Change 'name' to 'title'
           
        ]);
        Task::where("id", $id)->update([
            'crime_no' => $request->crime_no,
            'crime_section' => $request->crime_section,
            'criminal_address' => $request->criminal_address,
            'arrest_date' => $request->arrest_date,
            'remand' => $request->remand,
            'title' => $request->days,
            'arrest_by' => $request->arrest_by,
            'designation' => $request->designation,
            'condition' => $request->condition,
            'arrest_status' => $request->arrest_status,
        ]);
        return redirect()->route('task.index')->with('success', 'record updated successfully');
    }

    public function updatecollaborator(Request $request, $id)
    {
        // Get the existing collaborators from the database
        $taskAssignment = TaskAssignment::where('task_id', $id)->first();

        // If the task assignment doesn't exist, you may want to handle this case

        // Get the checked collaborators from the request
        $checkedCollaborators = $request->input('collaborators', []);

        // Merge the existing collaborators with the checked collaborators
        $updatedCollaborators = array_merge(json_decode($taskAssignment->collaborators, true) ?? [], $checkedCollaborators);

        // Update the task assignment with the merged collaborators
        $taskAssignment->update([
            'collaborators' => json_encode($updatedCollaborators),
        ]);

        return redirect()->route('task.index')->with('success', 'Collaborator added successfully');
    }
}
