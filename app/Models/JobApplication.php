<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'job',
        'status'
    ];

    public function jobApplicationAttempts()
    {
        return $this->hasMany(JobApplicationAttempt::class);
    }

    public function jobApplicationQuestions()
    {
        return $this->hasMany(JobApplicationQuestion::class);
    }
}
