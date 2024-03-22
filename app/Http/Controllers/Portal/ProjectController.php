<?php

namespace App\Http\Controllers\Portal;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Carbon;


class ProjectController extends Controller
{
    public function index()

    {   
        $status=Status::all();
        $categories=Category::all();
        $users=User::all();

        return view('portal.projects.index',compact('status','categories','users'));
    }

    public function add()
    {
        check_permission('create_project');
        return view('portal.users.add');
          
    }

    public function edit($id)
    {
        check_permission('edit_project');
        $project = Project::findorfail($id);
        return view('portal.projects.edit', compact('project'));
    }

    public function delete($id)
    {

        $project  = Project::where('id', $id)->delete();
        return redirect()->route('project.index')->with('success', 'Project deleted successfully');
    }

    public function list(Request $request)
    {
        ## Read value
        check_permission('view_project');
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
        $totalRecords = project::select('count(*) as allcount')->count();
        $totalRecordswithFilter = project::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
        $records = project::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')

            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $route = route('project.edit', $record->id);
            $delete_route = route('project.delete', $record->id);
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "location" => $record->location,
                "start_date" => $record->start_date,
                "due_date" => $record->due_date,
                "manager" => $record->project_manager->name,
                "category" => $record->project_category->name,
                "status" => $record->project_status->name,
                "created_by" => $record->createdByUser->name,
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

       check_permission('create_project');
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'start_date' => 'required',
            'due_date' => 'required',
            'manager' => 'required',
            'category' => 'required',
            'status' => 'required'

        ]);
        //dd($request->due_date);

       $project = Project::createProject($request->name, $request->location, $request->start_date,$request->due_date, $request->manager,$request->category,$request->status); 
        return redirect()->route('project.index')->with('success', 'Project created successfully');
    }
    
    public function update(Request $request, $id)
    {
        check_permission('update_project');
        $request->validate([
            'name' => 'required|max:20',
        ]);
        Project::where("id", $id)->update([
            "name" => $request->name,
        ]);
        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }
          
}
