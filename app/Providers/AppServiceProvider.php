<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share $notification with nav view
        view()->composer('portal.layout.partials.nav', function ($view) {
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

            $notificationcount = Task::whereIn("id", $taskIdsFromSQL)
            ->whereDate('created_at', today())
            ->count();
            $notification = Task::whereIn("id", $taskIdsFromSQL)
                ->whereDate('created_at', today())
                ->get();
           $view->with(['notificationcount' => $notificationcount, 'notification' => $notification]);

        });
    }
}
