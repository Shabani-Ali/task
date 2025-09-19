@extends('layouts.app')
@section('content')
<div class="card">
    <h2>Edit Project</h2>
    <form action="{{route('projects.update', $project)}}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{old('name', $project->name)}}" required>
        <button type="submit" class="btn primary"> Save</button>
        <a href="{{route('projects.index')}}" class="btn">Cancel</a>
    </form>
</div>
@endsection