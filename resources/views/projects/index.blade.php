@extends('layout.app')
@section('content')
<div class="card">
    <h2>Projects</h2>
    @if(session('success'))
    <p><strong>{{session('success')}}</strong></p>
    @endif

    <from action="{{ route('projects.strore')}}" method="POST" style="margin-bottom:16px;">
        @csrf
        <input type="text" name="name" placeholder="New Project name" required>
        <button type="submit" class="btn-primary"> Add Project</button>
    </form>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="test-align: left, padding:8px;">Name</th>
                <th style="test-align: left, padding:8px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr style="border-top:1px solid #ddd;">
                <td style="padding: 8px;">{{$project->name}}</td>
                <td style="padding: 8px; text-align:right;">
                    <a href="{{route('project.edit', $project)}}" class="btn">
                        <form action="{{route('projects.destroy', $project)}}" method="POST" style="display:inline" onsubmit="return confirm('Delete this project?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tbn">Delete</button>
                        </form>

                </td>

            </tr>
            @empty
            <tr><td colspan="2"> No projects yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
        