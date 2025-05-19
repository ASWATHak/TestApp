<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'due_date',
        'status',
    ];
    
}
