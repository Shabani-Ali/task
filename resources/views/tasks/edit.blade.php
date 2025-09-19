@extends('layouts.app')
@section('content')
<div class="card">
    <h2> Edit Task </h2>
    <form method="POST" action="{{route('tasks.update', $task)}}">
        @csrf
        @method('PUT')
        <div style="display:flex; gap:8px; align-items:center;">
            <input name="name" value="{{old('name', $task->name)}}" required style="flex:1;">
            <select name="project_id">
                <option value="">No project</option>
                @foreach($projects as $project)
                <option value="{{$project->id}}" @if($task->project_id==$project->id) selected @endif>{{$project->name}}</option>
                @endforeach
            </select>
            <button class="btn primary" type="submit">Save</button>
            <a href="{{route('tasks.index')}}" class="btn">Cancel</a>


        </div>

    </form>
</div>
@endsection