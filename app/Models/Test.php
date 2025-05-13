<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'subject_name',
        'que',
        'o1',
        'o2',
        'o3',
        'o4',
        'quef',
        'o1f',
        'o2f',
        'o3f',
        'o4f',
        'right_answer',
        'unseen_passage',
        'directions',
        'small_instructions',
        'exam_id'
    ];

    public function question()
    {
        $que = 'Question:- '. ucfirst(str_replace('\t','<br>',$this->que)).'<br>';

        if ($this->quef) {
            $que .= '<img style="border-radius: 0px;" src="'.asset('/'.$this->quef).'" />';
        }

        return $que;
    }

    public function options()
    {
        $answer = [];
        $answer[0] = 'A: <b>'. ucfirst($this->o1).'</b>';
        $answer[1] = 'B: <b>'. ucfirst($this->o2).'</b>';
        $answer[2] = 'C: <b>'. ucfirst($this->o3).'</b>';
        $answer[3] = 'D: <b>'. ucfirst($this->o4).'</b>';

        if ($this->o1f) {
            $answer[0] = 'A: <img style="border-radius: 0px;" src="'.asset('/'.$this->o1f).'" />';
        }
        if ($this->o2f) {
            $answer[1] = 'B: <img style="border-radius: 0px;" src="'.asset('/'.$this->o2f).'" />';
        }
        if ($this->o3f) {
            $answer[2] = 'C: <img style="border-radius: 0px;" src="'.asset('/'.$this->o3f).'" />';
        }
        if ($this->o4f) {
            $answer[3] = 'D: <img style="border-radius: 0px;" src="'.asset('/'.$this->o4f).'" />';
        }

        return implode('<br/>',[
            $answer[0],
            $answer[1],
            $answer[2],
            $answer[3]
        ]);
    }

    public function rightAnswer()
    {
        return 'Right Answer:- '. ucfirst($this->right_answer);
    }

    public function setUnseenPassage($value)
    {
        return $value ?? '';
    }

    public function getAnswerStatus()
    {
        $answer = StudentTest::where('exam_id',session('exam_id'))
            ->where('student_test_attempt_id',session('attempt_id'))
            ->where('user_id', auth()->user()->id)
            ->where('question_id',$this->id)
            ->first();

        return $answer ? $answer->testStatus() : 'nottaken';
    }
}
