<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    // this allows the name field to be set via create() or update (), the attribute is mass assignagle
    protected $fillable=['name'];

    // define the relatioship to tasks, a project can have many tasks, tasks are ordered by priority fied
    public function tasks (){
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
