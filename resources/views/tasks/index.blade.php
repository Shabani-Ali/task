@extends('layouts.app')
@section('content')
<div class="card">
    <form method="GET" class="project-select" style="display:flex; gap:8px; align-items:center;">
        <label class="small">Project:</label>
        <select name="project_id" onchange="this.form.submit()">
            <option value="">All Projects</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" {{ (string)$project->id === (string)$projectId ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
        <a href="#" id="new-project-toggle" class="btn" style="margin-left:auto;"> + New Project</a>
    </form>

    <form id="new-project-form" method="post" action="{{ route('projects.store') }}" style="display:none; margin-top:12px;">
        @csrf
        <input type="text" name="name" placeholder="Project Name" required>
        <button type="submit" class="btn primary">Create</button>
    </form>
</div>

<form method="POST" action="{{ route('tasks.store') }}" style="display:flex; gap:8px; align-items:center; margin-bottom:12px;">
    @csrf
    <input name="name" placeholder="New Task Name" required style="flex:1;">
    <select name="project_id">
        <option value="">No Project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ (string)$project->id === (string)$projectId ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    <button class="btn primary" type="submit">Add Task</button>
</form>

<div id="task-list">
    @if($tasks->isEmpty())
        <p class="small">No Tasks Yet</p>
    @else
        <div id="sortable-list">
            @foreach ($tasks as $task)
                <div class="task" data-id="{{ $task->id }}">
                    <div class="left">
                        <div class="priority">{{ $task->priority }}</div>
                        <div>
                            <div class="name">{{ $task->name }}</div>
                            <div class="small">
                                {{ $task->project?->name ?? 'No project' }} • created {{ $task->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="controls">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn">Edit</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Delete task?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="small" style="margin-top:12px;">
    Drag tasks to reorder. Task on top becomes priority number 1.
</div>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const list = document.getElementById('sortable-list');

    if (list) {
        new Sortable(list, {
            animation: 150,
            handle: '.task',
            onEnd: function() {
                const ids = Array.from(list.querySelectorAll('.task')).map(el => el.dataset.id);
                const projectSelect = document.querySelector('select[name="project_id"]');
                const project_id = projectSelect ? projectSelect.value : null;

                fetch('{{ route("tasks.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ordered_ids: ids, project_id: project_id || null })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'ok') {
                        Array.from(list.querySelectorAll('.task .priority'))
                             .forEach((el, idx) => el.textContent = idx + 1);
                    } else {
                        alert('Could not reorder tasks');
                    }
                })
                .catch(() => alert('Network Error'));
            }
        });
    }

    const toggleBtn = document.getElementById('new-project-toggle');
    const newProjectForm = document.getElementById('new-project-form');

    if (toggleBtn && newProjectForm) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            newProjectForm.style.display = newProjectForm.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>
@endpush
