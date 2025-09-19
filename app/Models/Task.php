<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable=['name', 'priority', 'project_id']; // attributes that a mass assignable
    protected $casts=[
        'priority'=>'integer', // attribute casting to ensure that priority is always an integer
    ];

    public function project(){

        return $this->belongsTo(Project::class); // define the relationship to project, a task belongs to a single project

    }
}
