<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects
     * Projects are ordered alphabeticcaly by name
     */
    public function index(){
        $projects=Project::orderBy('name')->get();

        // pass the lsit of projects to the index view
        return view('projects.index', compact('projects'));

    }

    /**
     * store newly cretaed project in the database and 
     * validates the request to ensure project name is provided
     */
    public function store(Request $request){

        // validate input
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);

        // create project
        Project::create($validated);
        return redirect()->route('projects.index')->with('success','Project created');

    }

    public function edit(Project $project){
        return view('projects.edit', compact ('project'));
    }

    public function update(Request $request, Project $project){
        //validate input
        $validate=$request->validate([
            'name'=>'required|string|max:255'
        ]);

        // update project
        $project->update($validate);
        return redirect()->route('projects.index')->with('success','Project updated');

    }

    public function destroy(Project $project){
        //delete project
        $project->delete();
        return redirect()-> route('projects.index')->with('success', 'Project deleted ');
    }


}
