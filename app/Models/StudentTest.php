<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function testStatus()
    {
        $status = 'nottaken';

        if ($this->question_status == 'submit'){ 
            if ($this->student_answer) {
                $status = 'attempted';
            } else {
                $status = 'unattempted';
            }
        }

        if ($this->question_status == 'under_review') {
            $status = 'review';
        }

        return $status;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Test::class);
    }

    public function attempt()
    {
        return $this->belongsTo(StudentTestAttempt::class);
    }
}
