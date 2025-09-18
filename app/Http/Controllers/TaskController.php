<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request){
        $projects=Project::orderBy('name')->get();
        $projectId= $request->query('project_id');
        $tasksQuery=Task::query();
        if($projectId){
            $tasksQuery->where('project_id', $projectId);
        }
        $tasks = $tasksQuery->orderBy('Priority')->get();
        return view('tasks.index', compact('tasks', 'projects', 'projectId'));

    }

    public function store(Request $request){
    $data=$request->validate([
        'name'=>'required|string|max:255',
        'project_id'=>['nullable', Rule::exists('projects', 'id')]
    ]);
    $max = Task::when(isset($data['project_id']), fn($q)=>$q->where('project_id', $data['project_id']))->max('priority');
    $data['priority']=$max?$max+1:1;
    $task=Task::create($data);
    return redirect()->back()-> with('success', 'Task created');

    }

    public function edit(Task $task){

        $projects=Project::orderby('name')->get();
        return view('task.edit', compact('task', 'projects'));
         
    }

    public function update(Task $task, Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'project_id'=>['nullable', Rule::exists('projects', 'id')]
        ]);
        $task->update($data);
        return redirect()->route('task.index',['project_id'=>$data['project_id']?? null])->with('success','Task Updated');

    }

    public function destroy(Task $task){
        $task->delete();
        //After deletion, we re-sequence priorities within the same project

        $this->resyncPriorities($task->project_id);
        return redirect()->back()->with('success', 'Task deleted.');

    }

    public function reorder(Request $request){
        $data=$request->validate([
            'ordered_ids'=>'required|array',
            'project_id'=>'nullable|integer|exists:projects,id'
        ]);

        //ordered_ids expected top to bottom the first is priority=1
        foreach($data['ordered_ids'] as $index=>$id){
            Task::where('id', $id)
            ->update(['priority'=>$index+1, 'project_id'=>$data['project_id']??null]);
        }

        return response()->json(['status'=>'ok']);

    }

    private function resyncPriorities($projectId=null){

        $tasks= Task::when($projectId, fn($q)=>$q->where('project_id', $projectId)->orderBy('priority')->get());
        foreach($tasks as $index=>$task){
            $task->priority=$index+1;
            $task->saveQuietly();
        }

    }
}
