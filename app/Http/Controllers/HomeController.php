<?php

namespace App\Http\Controllers;

use App\Task;
use Carbon\Carbon;
use App\Traits\UserActivity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    
    use UserActivity;
    const STATUS_NAMES=[
        1=>'Completed',
        0=>'Incomplete'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function getAll()
    {
        try {
            return Task::all();

            } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'position' => "top",
            "type" => "error"
        ]);
       }
    }

    public function dueTodayTasks()
    {
        try {
            return Task::whereDate('date','=',date('Y-m-d'))->where('status',0)->get();
             } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'position' => "top",
            "type" => "error"
        ]);
       }
    }

    public function getTasks()
    {
        try{
                return Task::where('status',0)->get();
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
            }
    }
    public function getActivites()
    {
        try {
            return \App\UserActivity::all();
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
           }
    }

    
    public function changeMultipleStatus(Request $request)
    {
        try {
                foreach($request->id as $item)
                {
                    $task = Task::find($item['id']);
                    $task->update(['status' => $request->status]);
                }
                $this->saveUserActivity("changed task records status to " .self::STATUS_NAMES[$request->status]);
                return response()->json([
                    'message' => "Tasks have been marked as  " .self::STATUS_NAMES[$request->status],
                    'position' => "top",
                    "type" => "success",
                    "status" => 200
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
                $task = Task::find($request->id);
                $task->update(['status' => $request->status]);
                $this->saveUserActivity("changed task record status to " . self::STATUS_NAMES[$request->status]);
                return response()->json([
                    'message' => "Task has been marked as  " .self::STATUS_NAMES[$request->status],
                    'position' => "top",
                    "type" => "success",
                    "status" => 200
                ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }


    public  function getCompleted()
    {
        try {
            return  Task::where("status", 1)->get();
    
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'position' => "top",
            "type" => "error"
        ]);
    }
    }

    public function updateTask(Request $request)
    {
        try {
            $Task = Task::find($request->id);
            $Task->update($request->all());
            $this->saveUserActivity("Updated Task record ");
            return response()->json([
                'message' => "A task  was updated",
                'position' => "top",
                "type" => "success",
                "status" => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }

    public function deleteTasks(Request $request)
    {
        try {
            foreach ($request->all() as $item) {
                Task::destroy($item['id']);
            }

            $this->saveUserActivity("Deleted tasks  records ");
            return response()->json([
                'message' => "Tasks were removed from the list",
                'position' => "top",
                "type" => "success",
                "status" => 204
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }

    public function deleteTask(Request $request)
    {
        try {
            Task::destroy($request->id);
            $this->saveUserActivity("Deleted a task record ");
            return response()->json([
                'message' => "A task was removed from the list",
                'position' => "top",
                "type" => "success",
                "status" => 204
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }

    public function saveTask(Request $request)
    {
        try {
            Task::create($request->all());
            $this->saveUserActivity("Created  a new task");
            return response()->json([
                'message' => "New task was created",
                'position' => "top",
                "type" => "success",
                "status" => 201
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'position' => "top",
                "type" => "error"
            ]);
        }
    }
}
