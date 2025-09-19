<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="view point" content="width=device-width, initial-scale=1">
        <title> Task Manager </title>
        <link href="https://cdn.jsdelivr.net/npm/modern-normalize/modern-normalize.min.css" rel="stylesheet">
        <!--Basic styles for the app-->
        <style>
            body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; padding: 24px; background:#f7f9fc; color:#111; }
            .container { max-width:900px; margin:0 auto; }
            
            /*card container for content blocks*/

            .card { background:#fff;
                 padding:16px;
                  border-radius:8px;
                   box-shadow: 0 6px 18px rgba(11,20,40,0.06);
                 }
            
                 /*task item styling*/
            .task { padding:12px;
                 border-radius:6px; 
                 background:#fafafa;
                  margin-bottom:8px; 
                  cursor:grab; display:flex;
                   justify-content:space-between;
                    align-items:center; }

            /*Left side of a task prioity and name*/
                    .task .left { display:flex;
                 gap:12px; 
                 align-items:center; 
                }

            /*priority number*/
                .task .priority { 
                width:36px; 
                text-align:center; 
                font-weight:bold; 
                opacity:0.7; 
            }

            .task .name { font-weight:600; }
            /*controls for edit and delete buttons*/
            .controls a, .controls form { 
                display:inline-block;
                 margin-left:6px; 
                }
            .project-select { margin-bottom:16px; }
            .small { font-size:0.9rem; color:#666; }
            .btn { padding:8px 12px;
                 border-radius:6px; 
                 text-decoration:none;
                  border:1px solid #ddd;
                   background:#fff;
                 }

            .btn.primary { background:#1560bd; color:#fff; border-color:#1560bd; }
            form.inline { display:inline; }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('head')
    </head>
    <body>
        <div class="container">
            <h1> Task Manager</h1>
            <nav style="margin-bottom:16px">
                <a href="{{route('tasks.index')}}" class="btn">Tasks</a>
                <a href="{{route('projects.index')}}" class="btn">Projects</a>
            </nav>
            @if(session('success')) <div class="card" style="margin-bottom: 12px;"><strong>{{session('success')}}</strong></div> @endif
            @yield('content')
        </div>
        @stack('scripts')

    </body>
</html>
