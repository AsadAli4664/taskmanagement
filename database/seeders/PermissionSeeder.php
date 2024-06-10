<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'add_criminal_record']);
        Permission::create(['name' => 'edit_task']);
        Permission::create(['name' => 'delete_task']);
        Permission::create(['name' => 'view_task']);
        Permission::create(['name' => 'create_subtask']);
        Permission::create(['name' => 'edit_subtask']);
        Permission::create(['name' => 'delete_subtask']);
        Permission::create(['name' => 'view_subtask']);
        Permission::create(['name' => 'view_alldashboardstats']);
        Permission::create(['name' => 'view_userdashboard']);
        Permission::create(['name' => 'view_alltasks']);
        Permission::create(['name' => 'view_allactivities']);
        Permission::create(['name' => 'manage_permission']);
        Permission::create(['name' => 'add_collaborator']);
        // Create roles and assign permissions
       // $super_adminRole = Role::create(['name' => 'super_admin']);
       // $super_adminRole->givePermissionTo(['create_users', 'edit_users', 'delete_users','view_users','create_project','edit_project','delete_project','view_project','create_task','edit_task','delete_task','view_task','create_subtask','edit_subtask','delete_subtask','view_subtask','create_activity','edit_activity','delete_activity','view_activity']);
       // $adminRole = Role::create(['name' => 'admin']);
       // $adminRole->givePermissionTo(['create_users', 'edit_users', 'delete_users','view_users','create_project','edit_project','delete_project','view_project','view_task','view_subtask','view_activity']);
       // $moderatorRole = Role::create(['name' => 'moderator']);
        //$moderatorRole->givePermissionTo(['create_users', 'edit_users', 'delete_users','view_users','create_project','edit_project','delete_project','view_project','create_task','edit_task','delete_task','view_task','create_subtask','edit_subtask','delete_subtask','view_subtask']);
    }
}
