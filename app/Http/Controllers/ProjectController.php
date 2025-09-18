<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        $orojects=Project::orderBy('name')->get();
        return view('projects.index', compact('projects'));

    }

    public function strore(Request $request){
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        Project::create($validated);
        return redirect()->route('projects.index')->with('success','Project created');

    }

    public function edit(Project $project){
        return view('projects.edit', compact ('project'));
    }

    public function update(Request $request, Project $project){
        $validate=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        $project->update($validate);
        return redirect()->route('projects.index')->with('success','Project updated');

    }

    public function destry(Project $project){
        $project->delete();
        return redirect()-> route('projects.index')->with('success', 'Project deleted ');
    }


}
