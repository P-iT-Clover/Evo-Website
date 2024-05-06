<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'qid',
        'job_application_id',
        'type',
        'label'
    ];

    public function jobApplication()
    {
        return $this->hasOne(JobApplication::class);
    }
}
