<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

// Models
use App\Models\User;
use App\Models\Unit;

use App\Mail\SignUp;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()

    {   
        $permissionResult = check_permission('view_users');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
        return $permissionResult;
        }
        return view('portal.users.index');
    }

    public function add()
    {

        $permissionResult = check_permission('create_users');
        if ($permissionResult instanceof \Illuminate\Http\RedirectResponse) {
            return view('portal.users.index');
        }
        $users = User::all();
        return view('portal.users.add', compact('users'));
          
    }

    public function edit($id)
    {
        $user = User::findorfail($id);
        return view('portal.users.edit', compact('user'));
    }

    public function delete($id)
    {

        $product  = User::where('id', $id)->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully');
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
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->orWhere('email', 'like', '%' . $searchValue . '%')->count();
        $records = User::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                      ->orWhere('email', 'like', '%' . $searchValue . '%');

            })
            // /->where('role', 'Employee') // Add the condition for role
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $route = route('user.edit', $record->id);
            $delete_route = route('user.delete', $record->id);

            $user = auth()->user();

            if ($user->hasAnyPermission(['manage_permission', 'edit_users', 'delete_users'])) {
                // User has at least one of the required permissions
                $action = '<div class="btn-group">';
                
                if ($user->hasPermissionTo('manage_permission')) {
                    $action .= '<a href="#" onclick="openRoleModal(' . $record->id . ')" class="mr-1 text-success" title="Grant Role">
                                    <i class="fa fa-plus"></i>
                                </a>';
                }
            
                if ($user->hasPermissionTo('edit_users')) {
                    $action .= '<a href="' . $route . '" class="mr-1 text-info" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>';
                }
            
                if ($user->hasPermissionTo('delete_users')) {
                    $action .= '<a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1 text-danger" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </a>';
                }
            
                $action .= '</div>';
            } else {
                // User doesn't have any of the required permissions

    $action = '<div></div>';
               
            }
            
           
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "designation"=>$record->designation,
                "phone"=>$record->phone, 
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

   public function store(Request $request)
    {

        if(Auth::user()->hasPermissionTo('create_users')){
        $request->validate([
            'email' => 'required|unique:users',
            'name' => 'required|max:150',
            'designation' => 'required|max:150',
            'phone' => 'required|max:11',
            'employee_id' => 'required|max:20',
            'password' => 'required|min:6|max:20'
            

        ]);

        $user = User::createUser($request->name, $request->employee_id, $request->email,$request->password, $request->designation, $request->phone, $request->unit_id);
        $user->givePermissionTo('view_criminal_record');
        $user->givePermissionTo('edit_criminal_record');
        $user->givePermissionTo('add_criminal_record');
        /*try {
            Mail::to($request->email)->send(new SignUp($request->name));
        } catch (\Exception $e) {
            return  $e->getMessage();
        }*/
        return redirect()->route('user.index')->with('success', 'User created successfully');
    }
    else{
        return redirect()->back()->with('error', 'You dont have specific Permission');
    }
    }
    


    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'name' => 'required|max:100',
            'designation' => 'required|max:150',
            'phone' => 'required|max:11',
        ]);

        User::where("id", $id)->update([
            "name" => $request->name,
            "email" => $request->email,
            "designation" => $request->designation,
            "phone" => $request->phone,
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }
    public function verify_email($email)
    {
        /*$user = User::where('email', $email)->get();
        /* if ($user->email_verified_at != null) {
            return 'Already Verified';
        } else {*/

        User::where("email", $email)->update([
            "email_verified_at" => now()
        ]);
        /*}*/

        return redirect('/signin');
    }

    public function assign_permission(Request $request)
    {
        $userId = $request->user_id;
        $permissions = $request->permissions;
    
        // Retrieve the user
        $user = User::find($userId);
    
        // Attach the selected permissions to the user
        $user->syncPermissions($permissions);
    
    
        return redirect()->back()->with('success', 'Permissions assigned successfully');
    }
    

    public function get_permission($userId){
        $user = User::find($userId);
        $allPermissions = Permission::all();
        $userPermissions = $user->permissions->pluck('name')->toArray();
    
        return response()->json([
            'allPermissions' => $allPermissions,
            'userPermissions' => $userPermissions,
        ]);
    }
    // UserController.php

    public function subtasks($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            abort(404); // User not found
        }
    
        $subtasks = $user->subtasks;
    
        return view('portal.users.subtasks', compact('user', 'subtasks'));
    }

        
       
}
