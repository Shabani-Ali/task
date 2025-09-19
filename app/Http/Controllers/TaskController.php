<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{

    public function index(Request $request){
        // Get all projects to populate dropdowns
        $projects=Project::orderBy('name')->get();

        //Get selected project_id from query string
        $projectId= $request->query('project_id');

        //Base query for tasks
        $tasksQuery=Task::query();

        // If a project is selected filter tasks for that project only
        if($projectId){
            $tasksQuery->where('project_id', $projectId);
        }

        //order tasks by priority ascending
        $tasks = $tasksQuery->orderBy('Priority')->get();

        // return the tasks index view with tasks, projects and selected projet
        return view('tasks.index', compact('tasks', 'projects', 'projectId'));

    }

    public function store(Request $request){

        // validate incoming request
    $data=$request->validate([
        'name'=>'required|string|max:255',
        'project_id'=>['nullable', Rule::exists('projects', 'id')]
    ]);
    // determine current max priority for this project
    $max = Task::when(isset($data['project_id']), fn($q)=>$q->where('project_id', $data['project_id']))->max('priority');
    
    // set priority to max+1 or 1 if no task exists
    $data['priority']=$max?$max+1:1;

    // create a new task
    $task=Task::create($data);
    return redirect()->back()-> with('success', 'Task created');

    }

    //edit tasks

    public function edit(Task $task){

        $projects=Project::orderby('name')->get();
        return view('tasks.edit', compact('task', 'projects'));
         
    }

    // update a specified task in the storage

    public function update(Task $task, Request $request){

        // validate request
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'project_id'=>['nullable', Rule::exists('projects', 'id')]
        ]);

        //update task with validated data
        $task->update($data);
        return redirect()->route('tasks.index',['project_id'=>$data['project_id']?? null])->with('success','Task Updated');

    }



    // delete a specified task from storage and reorganize priorities after deletion
    public function destroy(Task $task){
        $task->delete();
        //After deletion, we re-sequence priorities within the same project

        $this->resyncPriorities($task->project_id);
        return redirect()->back()->with('success', 'Task deleted.');

    }

    // reorder tasks and only update priority keeping project_id unchanged
    public function reorder(Request $request){
        //validate incoming request
        $data=$request->validate([
            'ordered_ids'=>'required|array',
            'project_id'=>'nullable|integer|exists:projects,id'
        ]);

        //loop through Ids and update priority in order
        foreach($data['ordered_ids'] as $index=>$id){
            $taskQuery=Task::where('id', $id);
                $taskQuery=Task::where('id', $id);

                //optionally restrict reordering to a specific project
                if(!empty($data['project_id'])){
                    $taskQuery->where('project_id', $data['project_id']);
                }
            
                // update priority 
            $taskQuery->update(['priority'=>$index+1]);
        }

        return response()->json(['status'=>'ok']);

    }

    private function resyncPriorities($projectId=null){

        $tasks= Task::when($projectId, fn($q)=>
        $q->where('project_id', $projectId))
        ->orderBy('priority')->get();

        //loop throuhg tasks and update priority sequentially
        foreach($tasks as $index=>$task){
            $task->priority=$index+1;
            $task->saveQuietly(); // save without firing events
        }

    }
}
