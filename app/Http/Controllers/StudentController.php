<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\StudentTest;
use App\Models\StudentTestAttempt;
use App\Models\Test;
use DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return view('studentsection.dashboard');
    }

    public function selectExam(Request $request)
    {
        if (session()->has('exam_id')
            && session()->has('exam_start')
        ) {
            $this->fetchQuestion(new Request);
        }

        if ($request->has('exam')) {
            session(['exam_id' => $request->exam]);
            return view('studentsection.startpage');
        }

        $examList = Exam::orderByDesc('id')->get();
        return view('studentsection.selectexam', compact('examList'));
    }

    public function startExam(Request $request)
    {
        $attempt = '';
        $userId = auth()->user()->id;

        if (session()->has('exam_id') && session('exam_id')) {
            $examId = session('exam_id');

            $lastAttempt = StudentTestAttempt::where('exam_id', $examId)
                ->where('user_id', $userId)
                ->where('exam_completed', 0)
                ->orderBy('id')
                ->first();

            if ($lastAttempt) {
                $time = $this->getTimeDifference($lastAttempt->created_at);
                $minutes = ($time['hour'] * 60) + $time['minute'];

                $attempt = $lastAttempt;
                if ($minutes >= '150') {
                    $lastAttempt->update(['exam_completed' => 1]);
                    $attempt = StudentTestAttempt::create([
                        'exam_id' => $examId,
                        'user_id' => $userId,
                        'attempt_no' => $lastAttempt ? $lastAttempt->attempt_no+1 : 1,
                        'exam_completed' => 0,
                    ]);
                }
            } else {
                $attempt = StudentTestAttempt::create([
                    'exam_id' => $examId,
                    'user_id' => $userId,
                    'attempt_no' => $lastAttempt ? $lastAttempt->attempt_no+1 : 1,
                    'exam_completed' => 0,
                ]);

                $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $attempt->created_at);
                $newDatetime = $datetime->addHours(2)->addMinutes(30);

                $attempt->update(['test_end_time' => $newDatetime->format('Y-m-d H:i:s')]);
            }

            if ($attempt && $attempt->id ) {
                session(['attempt_id' => $attempt->id]);
            }

            $lastAttemptedQuestion = StudentTest::where('user_id',auth()->user()->id)
            ->where('student_test_attempt_id',session('attempt_id'))
            ->where('exam_id',session('exam_id'))
            ->orderByDesc('id')
            ->first();

            $questions = Test::where('exam_id',session('exam_id'))
                ->orderBy('id')->get();

            $que = Test::where('exam_id',session('exam_id'));

            if ($lastAttemptedQuestion) {
                $que = $que->where('id','>',$lastAttemptedQuestion->id);
            }

            $que = $que->orderBy('id')->first();
            
            return view('studentsection.examsheet',compact('questions','que'));
        }

        return redirect()->back();
    }

    public function fetchQuestions(Request $request)
    {
        if ($request->has('status') && $request->has('answer')) {
            $attemptedQuestion = StudentTest::where('user_id',auth()->user()->id)
                                    ->where('student_test_attempt_id',session('attempt_id'))
                                    ->where('exam_id',session('exam_id'))
                                    ->where('question_id', $request->question_id)
                                    ->first();

            if (!$attemptedQuestion) {
                StudentTest::create([
                    'user_id' => auth()->user()->id,
                    'exam_id' => session('exam_id'),
                    'student_test_attempt_id' => session('attempt_id'),
                    'question_id' => $request->question_id,
                    'student_answer' => $request->answer,
                    'question_status' => $request->status,
                ]);
            } else {
                $attemptedQuestion->update([
                    'student_answer' => $request->answer,
                    'question_status' => $request->status,
                ]);
            }

            //fetch next question when clicked on under review button or next button
            $newQuestion = Test::where('exam_id',session('exam_id'))->where('id','>',$request->question_id)->first();
        } else {
            //fetch current question when clicked on questions under review
            $newQuestion = Test::where('exam_id',session('exam_id'))->where('id',$request->question_id)->first();
        }

        return [
            'test_finished' => !$newQuestion,
            'que' => $newQuestion ? $newQuestion->toArray() : $newQuestion,
        ];
    }

    public function submitTest(Request $request)
    {
        $attemptedQuestion = StudentTest::where('user_id',auth()->user()->id)
                                                ->where('student_test_attempt_id',session('attempt_id'))
                                                ->where('exam_id',session('exam_id'))
                                                ->where('question_id', $request->question_id)
                                                ->first();

        if (!$attemptedQuestion) {
            StudentTest::create([
                'user_id' => auth()->user()->id,
                'exam_id' => session('exam_id'),
                'student_test_attempt_id' => session('attempt_id'),
                'question_id' => $request->question_id,
                'student_answer' => $request->answer,
                'question_status' => $request->status,
            ]);
        } else {
            $attemptedQuestion->update([
                'student_answer' => $request->answer,
                'question_status' => $request->status,
            ]);
        }

        $finishTest = StudentTestAttempt::where('user_id',auth()->user()->id)
                        ->where('exam_id',session('exam_id'))
                        ->where('exam_completed',0)
                        ->orderByDesc('id')
                        ->first();

        if ($finishTest) {
            $finishTest->update(['exam_completed' => 1]);
        }

        return ['location' => route('student.dashboard')];
    }

    public function testTimer(Request $request)
    {
        $studentTestAttempt = StudentTestAttempt::where('user_id', auth()->user()->id)
                                ->where('exam_id',session('exam_id'))
                                ->where('exam_completed',0)
                                ->orderByDesc('id')
                                ->first();

        if ($studentTestAttempt) {
            $datetime1 = Carbon::parse($studentTestAttempt->test_end_time);
            $datetime2 = Carbon::now();

            // Get the difference in hours and minutes separately
            $interval = $datetime1->diff($datetime2);
            $diffInHours = $datetime1->diffInHours($datetime2);
            $diffInMinutes = $datetime1->diffInMinutes($datetime2) % 60; // Get remaining minutes after hours

            return response()->json([
                'time' => $interval->h.':'.$interval->i.':'.$interval->s,
                'hour' => $interval->h,
                'minute' => $interval->i,
            ]);
        }

        return response()->json([
            'time' => $interval->h.':'.$interval->i.':'.$interval->s,
            'hour' => $interval->h,
            'minute' => $interval->i,
        ]);
    }

    protected function getTimeDifference($createdAt)
    {
        $datetime1 = Carbon::parse($createdAt);
        $datetime2 = Carbon::now();

        $interval = $datetime1->diff($datetime2);

        return [
            'time' => $interval->h.':'.$interval->i.':'.$interval->s,
            'hour' => $interval->h,
            'minute' => $interval->i,
        ];
    }

    public function studentTestAttempt(Request $request)
    {
        $testList = StudentTestAttempt::with('user','exam')->where('user_id',auth()->user()->id)->paginate(20);
        return view('studentreports.student_test_attempt',compact('testList'));
    }

    public function studentTestQuestion(Request $request, $examId)
    {
        $attemptId = $request->id;

        $list = Test::where('exam_id',$examId);

        $testCount = 0;
        $testCount = $list->count();
        $testList = $list->get();

        foreach ($testList as $item) {
            $answer = StudentTest::where('user_id',auth()->user()->id)
                ->where('student_test_attempt_id',$attemptId)
                ->where('exam_id',$examId)
                ->where('question_id',$item->id)
                ->first();

            $attempt = StudentTestAttempt::where('id',$attemptId)->first();
            $examName = $attempt && $attempt->exam ? $attempt->exam->name : '';
            $userName = $attempt && $attempt->user ? $attempt->user->name : '';

            $item->exam_name = $examName;
            $item->user_name = $userName;
            $item->student_answer = ($answer && $answer->student_answer) ? $answer->student_answer : '';
        }

        if ($request->has('download') && $request->download) {
            try {
                $this->downloadStudentTestAttemptExcel($testList);
            } catch (Exception $e) {
                \Log::info("Error creating file: " . $e->getMessage());
            }
            return;
        }

        return view('studentreports.student_test_question', compact('testList','testCount'));
    }

    function downloadStudentTestAttemptExcel($data) 
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1','Name');
        $sheet->setCellValue('B1','Exam');
        $sheet->setCellValue('C1','Subject');
        $sheet->setCellValue('D1','Question');
        $sheet->setCellValue('E1','Question');
        $sheet->setCellValue('F1','Right Answer');
        $sheet->setCellValue('G1','Student Answer');

        // Add data to the Excel file
        $rowIndex = 2; // Start from the second row (after headers)

        foreach ($data as $key => $row) {
            $sheet->setCellValue('A'.$rowIndex, $row->user_name);
            $sheet->setCellValue('B'.$rowIndex, $row->exam_name);
            $sheet->setCellValue('C'.$rowIndex, $row->subject_name);

            $sheet->setCellValue('D'.$rowIndex, $row->que);
            //$sheet->setCellValue('E'.$rowIndex, $row->quef ? file_exists(asset($row->quef)) : '');
            $sheet->setCellValue('F'.$rowIndex, $row->right_answer);
            $sheet->setCellValue('G'.$rowIndex, $row->student_answer);
            try {
                if ($row->quef && file_exists($row->quef)) {
                    $drawing = new Drawing();
                    $drawing->setName('User Image');
                    $drawing->setDescription('User Profile Picture');
                    $drawing->setPath(public_path($row->quef)); // Set image path
                    $drawing->setHeight(50); // Set image height
                    $drawing->setCoordinates('E'.$rowIndex); // Place in the correct row
                    $sheet->getRowDimension($rowIndex)->setRowHeight(100);
                    $drawing->setWorksheet($sheet);
                }
            } catch (Exception $e) {
                \Log::info("Error adding image: " . $e->getMessage());
            }

            $rowIndex++;
        }

        // Save the Excel file
        $filename = 'tests.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $mime = mime_content_type($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $filename . "\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filename);
    }
}