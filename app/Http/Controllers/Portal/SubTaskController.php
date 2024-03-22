<?php

namespace App\Http\Controllers\Portal;
use App\Models\SubTask;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;



class SubTaskController extends Controller
{
    public function index()

    {   
        $permissionResult = check_permission('view_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $currentUser = auth()->user();
        $tasks=Task::all();
        $users= User::all();
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
$alltask = Task::whereIn("id", $taskIdsFromSQL)->get();
      

// Assuming you have a model named Task
$taskss = Task::where('assigned_to', $id)->get();
        return view('portal.subtasks.index',compact('tasks', 'taskss', 'tasksa', 'users', 'currentUser', 'alltask'));
       
       
       
    }

    public function add()
    {
        $permissionResult = check_permission('create_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        return view('portal.subtasks.add');
          
    }
    // Replace with your actual model and attribute names
    public function getTaskProgress(Request $request)
    {
        $taskId = $request->input('taskId');
        $task = Task::find($taskId);

        return response()->json(['progress' => $task->progress]);
    }


    public function edit($id)
    {
        $permissionResult = check_permission('edit_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $subtask = Subtask::findOrFail($id);
        return view('portal.subtasks.edit', compact('subtask'));
    
    }

    public function delete($id)
    {
        $permissionResult = check_permission('delete_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $subtasks  = SubTask::where('id', $id)->delete();
        return redirect()->route('subtask.index')->with('success', 'subtasks deleted successfully');
    }

    public function activity(Request $request, $id)
{
   $data=SubTask::where("id", $id)->get();
  
   $users=User::all();
   return view('portal.subtasks.activityview',compact('data', 'users'));

}

    public function list(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = SubTask::select('count(*) as allcount')->count();
        $totalRecordswithFilter = SubTask::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
        $records = SubTask::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            // ->orderBy($columnName, $columnSortOrder)
            ->orderByDesc('created_at')
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $route = route('subtask.edit', $record->id);
            $delete_route = route('subtask.delete', $record->id);
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "action" => '<div class="btn-group">
                <a href="' . $route . '" class="mr-1 text-info" title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1 text-danger" title="Delete">
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

    public function store(Request $request)
    {
        $permissionResult = check_permission('create_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return $permissionResult;
        }
        $id = auth()->user()->id;
        $currentDayStart = now()->setTimezone('Asia/Karachi')->startOfDay()->format('Y-m-d\TH:i');
        $currentDayEnd = now()->setTimezone('Asia/Karachi')->endOfDay()->format('Y-m-d\TH:i');

        $request->validate([
            'task' => 'required',
            'name' => 'required|max:100',
            'start_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:' . $currentDayStart . '|before:' . $currentDayEnd,
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'description' => 'required',
            'progress' => 'required',
        ], [
            'start_datetime.after_or_equal' => 'The Date must be within the current day.',
            'start_datetime.before' => 'The Date must be within the current day.',
            'end_datetime.after' => 'The End Date must be after the Start Date.',
        ]);
    
        // Check if there are any conflicts in datetime range "->where('task_id', $request->task) APPLY BELOW IF NEEDED"
        $conflictingSubtasks = \DB::table('sub_task')
    ->where('created_by', $id)
    ->whereRaw('? BETWEEN start_datetime AND end_datetime', [$request->start_datetime])
    ->exists();

if ($conflictingSubtasks) {
    return redirect()->route('subtask.index')->with('error', 'Activity exists between the specified time duration.');
}
         // Calculate duration in minutes
         $startDateTime = new \DateTime($request->start_datetime);
         $endDateTime = new \DateTime($request->end_datetime);
         $duration = $startDateTime->diff($endDateTime)->days * 24 * 60; // Convert days to minutes
         $duration += $startDateTime->diff($endDateTime)->h * 60; // Add hours to minutes
         $duration += $startDateTime->diff($endDateTime)->i; // Add remaining minutes
        // If no conflicts, proceed with creating subtask and updating progress
        $subtask = SubTask::createSubTask($request->task, $request->name, $request->start_datetime, $request->end_datetime, $request->description, $duration);
    
        Task::where("id", $request->task)->update([
            "progress" => $request->progress,
        ]);
    
        return redirect()->route('subtask.index')->with('success', 'Subtasks created successfully');
    }
    
    
    
    

    public function listsub(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = SubTask::select('count(*) as allcount')->count();
        $totalRecordswithFilter = SubTask::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->orWhere('description', 'like', '%' . $searchValue . '%')->count();
        // $records = SubTask::orderBy($columnName, $columnSortOrder)
        //     ->where(function ($query) use ($searchValue) {
        //         $query->where('name', 'like', '%' . $searchValue . '%')
        //               ->orWhere('description', 'like', '%' . $searchValue . '%');

        //     })
        //     // /->where('role', 'Employee') // Add the condition for role
        //     ->select('*')
        //     ->skip($start)
        //     ->take($rowperpage)
        //     ->orderBy($columnName, $columnSortOrder)
        //     ->get();
        $records = DB::table('task')->join('sub_task', 'task.id', '=', 'sub_task.task_id')->select('task.title', 'sub_task.*')->get();

       
        $data_arr = array();

        foreach ($records as $record) {
            $route = route('user.edit', $record->id);
            $delete_route = route('user.delete', $record->id);

            if (auth()->user()->hasPermissionTo('manage_permission')) {
                $action=
                            '<div class="btn-group">
                            <a href="#"  onclick="openRoleModal(' . $record->id . ')" class="mr-1 text-success" title="Grant Role">
                            <i class="fa fa-plus"></i>
                            </a>
                            <a href="' . $route . '" class="mr-1 text-info" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1 text-danger" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>' ;
            } else {
                $action= '<div class="btn-group">
                            
                            <a href="' . $route . '" class="mr-1 text-info" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1 text-danger" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>' ;
            }
           
            $data_arr[] = array(
                "id" => $record->id,
                "task" => $record->title,
                "activityname" => $record->name,
                "description"=>$record->description,
                "duedate"=>$record->created_at, 
                "created_at"=>$record->created_at,
                "created_by"=>$record->created_by, 
                "action" => 
                    $action
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
    public function update(Request $request, $id)
    {

       $permissionResult = check_permission('edit_subtask');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        $currentDayStart = now()->setTimezone('Asia/Karachi')->startOfDay()->format('Y-m-d\TH:i');
        $currentDayEnd = now()->setTimezone('Asia/Karachi')->endOfDay()->format('Y-m-d\TH:i');
        $request->validate([
         
            'name' => 'required|max:100',
            'start_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:' . $currentDayStart . '|before:' . $currentDayEnd,
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'description' => 'required'
        ], [
            'start_datetime.after_or_equal' => 'The Date must be within the current day.',
            'start_datetime.before' => 'The Date must be within the current day.',
            'end_datetime.after' => 'The End Date must be after the Start Date.',
        ]);
    
        // Check if there are any conflicts in datetime range "->where('task_id', $request->task) APPLY BELOW IF NEEDED"
        $conflictingSubtasks = \DB::table('sub_task')
    ->where('created_by', $id)
    ->whereRaw('? BETWEEN start_datetime AND end_datetime', [$request->start_datetime])
    ->exists();

if ($conflictingSubtasks) {
    return redirect()->route('subtask.index')->with('error', 'Activity exists between the specified time duration.');
}
         // Calculate duration in minutes
         $startDateTime = new \DateTime($request->start_datetime);
         $endDateTime = new \DateTime($request->end_datetime);
         $duration = $startDateTime->diff($endDateTime)->days * 24 * 60; // Convert days to minutes
         $duration += $startDateTime->diff($endDateTime)->h * 60; // Add hours to minutes
         $duration += $startDateTime->diff($endDateTime)->i; // Add remaining minutes
        SubTask::where("id", $id)->update([
            "name" => $request->name,
            "description" => $request->description,
            "start_datetime" => $request->start_datetime,
            "end_datetime" => $request->end_datetime,
            "duration" => $duration,
        ]);
        return redirect()->route('subtask.index')->with('success', 'subtasks updated successfully');
    }

    public function user_activity(Request $reqeust,$id){
        if(auth()->user()->id==$id || auth()->user()->hasPermissionTo('view-useractivity') ){
                    $subtasks= SubTask::all()->where('created_by',$id);
                    return $subtasks;
        }
        else{
         return redirect()->route('dashboard')->with('error','You dont have sepecified Permission');
        }

    }
}
