<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Mail\StudentCreate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Test;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentTest;
use App\Models\StudentTestAttempt;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $user = auth()->user();

        $selectedDate = Carbon::now()->format('Y-m-d');
        $totalStudent = Student::count();
        $totalExam = Exam::count();

        if ($request->date) {
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        }

        $registeredToday = Student::whereDate('created_at', $selectedDate)->count();
        $attempsToday = StudentTestAttempt::whereDate('created_at', $selectedDate)->count();

        return view('dashboard', compact('user','totalStudent','totalExam','selectedDate','registeredToday','attempsToday'));
    }

    public function showExamForm(Request $request,$id = '')
    {
        $exams = Exam::get();
        $exam = '';

        if ($id) {
            $exam = Exam::find($id);
        }

        $schools = Subject::distinct('school')->pluck('school');

        return view('exam.examform',compact('schools','exams','exam'));
    }

    public function saveExam(Request $request,$id = '')
    {
        echo "<pre>"; print_r($request->all()); die;
        $request->validate([
            'school' => ['required'],
            'name' => ['required'],
        ]);

        if ($id) {
            $exam = Exam::find($id);
            if ($exam) {
                $exam = $exam->update([
                    'school' => $request->school,
                    'name' => $request->name,
                ]);
                return back()->with('success','Updated Successfully.');
            }

        }

        $exam = Exam::create([
            'school' => $request->school,
            'name' => $request->name,
        ]);

        return back()->with('success','Added Successfully.');
    }

    public function deleteExam($id)
    {
        if (strtolower(auth()->user()->role->name) != 'admin') {
            return back()->with('error','Unauthorized Operation.');
        }

        if (!$id) {
            return back()->with('error','Cannot not Delete.');
        }

        $test = Exam::find($id);

        if ($test && $test->delete()) {
            return back()->with('success','deleted sucessfully');
        }

        return back()->with('success','Unable to Delete');
    }

    public function showTestForm(Request $request,$id = '')
    {   
        if (!session()->has('exam_id') || !session('exam_id')) {
            if (!$request->has('exam_id') || !$request->exam_id) {
                $exams = Exam::get();
                return view('test.selectexam', compact('exams'));
            } else {
                session(['exam_id' => $request->exam_id]);
            }
        }

        $test = '';
        if ($id) {
            $test = Test::find($id);
        }

        $subjects = Subject::distinct()->pluck('name');
        // $questionList = Test::where('exam_id',session('exam_id'))->orderBy('id','desc')->paginate(10);
        $questionList = Test::where('exam_id',$request->exam_id)->orderBy('id','desc')->paginate(10);

        return view('test.form', compact('subjects','questionList','test'));
    }

    public function showTestList(Request $request)
    {
        $questionList = Test::query();
        
        if ($request->has('question') && $request->question != '') {
            $questionList = $questionList->where("que",'like', '%'.$request->question.'%');
        }

        $questionList = $questionList->orderBy('id','desc')->paginate(20);

        return view('test.list',compact('questionList'));
    }

    public function saveTest(Request $request,$id = '')
    {
        $quef = '';
        $opf = [];

        $request->validate([
            'subject' => ['required'],
            'right_answer' => ['required'],
        ]);

        session(['test_subject' => $request->subject]);
        session(['small_instructions' => $request->small_instructions]);
        session(['unseen_passage' => $request->unseen_passage]);
        session(['directions' => $request->directions]);

        if ($request->hasFile('quef')) {
            $file = $request->file('quef');
            $fileName = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
            $quef = $file->storeAs('/questions', $fileName);
        }

        for ($i=1;$i<=4;++$i) {
            if ($request->hasFile('o'.$i.'f') && $request->{'o'.$i.'f'}) {
                $file = $request->file('o'.$i.'f');
                $fileName = 'o'.$i.'f' . '_' . str_replace(' ','_',$file->getClientOriginalName());
                //move_uploaded_file($_FILES["o".$i."f"]["tmp_name"], $fileName);
                //$opf[$i] = $fileName;
                $opf[$i] = $file->storeAs('/options', $fileName);
            }
        }

        if ($id) {
            $test = Test::find($id);

            if ($test) {
                $test = $test->update([
                    'subject_name' => $request->subject,
                    'que' => trim($request->que),
                    'quef' => $quef,  
                    'o1' => trim($request->o1),
                    'o1f' => $opf ? $opf[1] : '',
                    'o2' => trim($request->o2),
                    'o2f' => $opf ? $opf[2] : '',
                    'o3' => trim($request->o3),
                    'o3f' => $opf ? $opf[3] : '',
                    'o4' => trim($request->o4),
                    'o4f' => $opf ? $opf[4] : '',
                    'right_answer' => trim($request->right_answer),
                    'unseen_passage' => trim($request->unseen_passage),
                    'directions' => trim($request->directions),
                    'small_instructions' => trim($request->small_instructions)
                ]);

                return back()->with('success','Test Updated.');
            }

            return back()->with('error','Unable to Update Test.');
        }

        $newTest = Test::create([
            'subject_name' => $request->subject,
            'que' => trim($request->que),
            'quef' => $quef,
            'o1' => trim($request->o1),
            'o1f' => $opf ? $opf[1] : '',
            'o2' => trim($request->o2),
            'o2f' => $opf ? $opf[2] : '',
            'o3' => trim($request->o3),
            'o3f' => $opf ? $opf[3] : '',
            'o4' => trim($request->o4),
            'o4f' => $opf ? $opf[4] : '',
            'right_answer' => trim($request->right_answer),
            'unseen_passage' => trim($request->unseen_passage),
            'directions' => trim($request->directions),
            'small_instructions' => trim($request->small_instructions),
            'exam_id' => session('exam_id')
        ]);

        if ($newTest) {
            return back()->with('success','Test Added.');
        }

        return back()->with('error','Unable to add test.');
    }

    public function deleteTest($id)
    {
        if (strtolower(auth()->user()->role->name) != 'admin') {
            return back()->with('error','Unauthorized Operation.');
        }

        if (!$id) {
            return back()->with('error','Cannot not Delete.');
        }

        $test = Test::find($id);

        if ($test && $test->delete()) {
            return back()->with('success','deleted sucessfully');
        }

        return back()->with('success','Unable to Delete');
    }

    public function showStudentForm(Request $request,$id='')
    {
        $student = Student::find($id);

        $classes = [1,2,3,4,5,6,7,8,9,10,11,12];
        $students = Student::orderBy('id','desc')->get();

        if ($id && !$student) {
            return back()->with('error','Student not found.');
        }

        return view('students.form',compact('student','classes','students'));
    }

    public function storeStudent(Request $request, $id = '')
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required'],
            'father_name' => ['required'],
            'add_no' => ['required','integer'],
            'class' => ['required'],
        ];

        $request->validate($rules);

        $password = rand(10000,1000000000);

        if ($id) {
            $student = Student::findOrFail($id);

            $student = $student->update([
                'name' => $request->name,
                'father_name' => $request->father_name,
                'add_no' => $request->add_no,
                'class' => $request->class,
            ]);

            return redirect()->route('admin.student.form')->with('success', 'Student Updated Successfully.');
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return back()->with('error', 'Email Already associated with another user.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => 2
        ]);

        if ($user) {
            $student = Student::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'father_name' => $request->father_name,
                'add_no' => $request->add_no,
                'class' => $request->class
            ]);
        }

        if ($student) {
            $status = Mail::to($user->email)->send(new StudentCreate($student->name, $user->email, $password));

            \Log::info([$status]);
            return back()->with('success', 'Student Added.');
        }

        return back()->with('error', 'Unable to add student.');
    }

    public function deleteStudent($id)
    {
        if (strtolower(auth()->user()->role->name) != 'admin') {
            return back()->with('error','Unauthorized Operation.');
        }

        if (!$id) {
            return back()->with('error','Can not Delete.');
        }

        $student = Student::find($id);

        if ($student && $student->delete()) {
            return back()->with('success','deleted sucessfully');
        }

        return back()->with('success','Unable to Delete');
    }

    public function studentTest(Request $request, $examId = '')
    {
        $testList = StudentTest::
                    selectRaw('user_id,exam_id,student_test_attempt_id,count(question_id) question_attempted')
                    ->with('user','exam');

        if ($examId) {
            $testList = $testList->where('exam_id',$examId);                
        }

        $testList = $testList
                    ->groupBy('user_id','exam_id','student_test_attempt_id')
                    ->get();

        return view('adminreports.student_test', compact('testList'));
    }

    public function studentTestAttempt(Request $request)
    {
        $testList = StudentTestAttempt::with('user','exam');
        
        if ($request->has('id') && $request->id) {
            $testList = $testList->where('exam_id',$request->id);
        }

        if ($request->has('download') && $request->download) {
            try {
                return $this->downloadStudentTestAttemptExcel($testList->get());
            } catch (Exception $e) {
                echo "Error creating file: " . $e->getMessage();
            }
        }

        $testList = $testList->orderByDesc('id')->paginate(20);

        return view('adminreports.student_test_attempt',compact('testList'));
    }

    public function students(Request $request)
    {
        $list = Student::get();

        if ($request->has('download') && $request->download) {
            try {
                return $this->downloadStudentExcel($list);
            } catch (Exception $e) {
                echo "Error creating file: " . $e->getMessage();
            }
        }

        return view('adminreports.students',compact('list'));
    }

    public function studentTestQuestion(Request $request)
    {
        $testList = StudentTest::with('user','exam','question');
        $testCount = 0;

        if ($request->has('id') && $request->id) {
            $testList = $testList->where('student_test_attempt_id',$request->id);
        }

        $testCount = $testList->count();
        $testList = $testList->get();

        return view('adminreports.student_test_question', compact('testList','testCount'));
    }

    public function exam(Request $request)
    {
        $list = Exam::get();

        return view('adminreports.test_list',compact('list'));
    }

    public function clearcache() 
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
    }

    function downloadStudentExcel($data) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnIndex = 1;

        $sheet->setCellValue('A1','ID');
        $sheet->setCellValue('B1','Name');
        $sheet->setCellValue('C1','Email');
        $sheet->setCellValue('D1','FatherName');

        // Add data to the Excel file
        $rowIndex = 2; // Start from the second row (after headers)
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A'.$rowIndex, $row->id);
            $sheet->setCellValue('B'.$rowIndex, $row->user->name);
            $sheet->setCellValue('C'.$rowIndex, $row->user->email);
            $sheet->setCellValue('D'.$rowIndex, $row->father_name);
            $rowIndex++;
        }

        // Save the Excel file
        $filename = 'students.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $mime = mime_content_type($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($filename) . "\"");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filename);

        //return response()->download($filename,$filename,['Content-Type: text/xlxs']);
    }

    function downloadStudentTestAttemptExcel($data) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnIndex = 1;

        $sheet->setCellValue('A1','Name');
        $sheet->setCellValue('B1','Exam');
        $sheet->setCellValue('C1','Subject');
        $sheet->setCellValue('D1','Question');
        $sheet->setCellValue('E1','Right Answer');
        $sheet->setCellValue('F1','Student Answer');


        // Add data to the Excel file
        $rowIndex = 2; // Start from the second row (after headers)
        foreach ($data as $key => $row) {
            $userName = $row->user ? $row->user->name : '';
            $examName = $row->exam ? $row->exam->name : '';

            //echo "<pre>"; print_r($row->exam->name); die;
            $sheet->setCellValue('A'.$rowIndex, $userName);
            $sheet->setCellValue('B'.$rowIndex, $examName);
            $sheet->setCellValue('C'.$rowIndex, $row->attempt_no);
            $rowIndex++;
        }

        // Save the Excel file
        $filename = 'students.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $mime = mime_content_type($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($filename) . "\"");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filename);

        //return response()->download($filename,$filename,['Content-Type: text/xlxs']);
    }

    public function createTestUsingExcel(Request $request)
    {
        $file = $request->file;
        echo '<pre>'; print_r($request->all()); die;
        // if ($request->hasFile($file) && $file) {
        //     $file = $request->file('file');
        //     $fileName = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
        //     $quef = $file->storeAs('/questionpaper', $fileName);

        //     $file = base_path($fileName);
        //     $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file);
        //     $spreadsheet = $reader->load($file);

        //     echo '<pre>'; print_r($spreadsheet); die;
        // }

        if ($request->hasFile('file') && $file) {
            $file = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $drawings = $worksheet->getDrawingCollection();
            $images = [];

            foreach ($drawings as $drawing) {
                if ($drawing instanceof MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageData = ob_get_clean();
                } else {
                    $zipReader = fopen($drawing->getPath(), 'r');
                    $imageData = stream_get_contents($zipReader);
                    fclose($zipReader);
                }
        
                $coordinates = $drawing->getCoordinates(); // e.g., "C2"
                $images[$coordinates] = $imageData;
            }

            $data = [];

            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $rowIndex = $row->getRowIndex();
                $rules = [
                    'subject_name' => ['required'],
                ];
                $q='';$o1='';$o2='';$o3='';$o4='';

                if (isset($images["C$rowIndex"])) {
                    $q = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
                    Storage::disk('public')->put('/questions/'.$q, $images["C$rowIndex"]);
                    // ($images["C$rowIndex"])->storeAs('/questions', $q);
                } else {
                    $rules[] = ['q' => 'required'];
                }

                if (isset($images["E$rowIndex"])) {
                    $o1 = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
                    Storage::disk('public')->put('/options/'.$o1, $images["C$rowIndex"]);
                    // ($images["C$rowIndex"])->storeAs('/options',  $o1);
                } else {
                    $rules[] = ['o1' => 'required'];
                }

                if (isset($images["G$rowIndex"])) {
                    $o2 = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
                    Storage::disk('public')->put('/options/'.$o2, $images["C$rowIndex"]);
                    // ($images["E$rowIndex"])->storeAs('/options',  $o2);
                } else {
                    $rules[] = ['o2' => 'required'];
                }

                if (isset($images["I$rowIndex"])) {
                    $o3 = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
                    Storage::disk('public')->put('/options/'.$o3, $images["C$rowIndex"]);
                    // ($images["G$rowIndex"])->storeAs('/options',  $o3);
                } else {
                    $rules[] = ['o3' => 'required'];
                }

                if (isset($images["K$rowIndex"])) {
                    $o4 = time() . '_' . str_replace(' ','_',$file->getClientOriginalName());
                    Storage::disk('public')->put('/options/'.$o4, $images["C$rowIndex"]);
                    // ($images["H$rowIndex"])->storeAs('/options',  $o4);
                } else {
                    $rules[] = ['o4' => 'required'];
                }

                $data[$rowIndex] = [
                    $worksheet->getCell("A$rowIndex")->getValue() ?? '',
                    $worksheet->getCell("B$rowIndex")->getValue() ?? '',
                    $q,
                    $worksheet->getCell("D$rowIndex")->getValue() ?? '',
                    $o1,
                    $worksheet->getCell("F$rowIndex")->getValue() ?? '',
                    $o2,
                    $worksheet->getCell("H$rowIndex")->getValue() ?? '',
                    $o3,
                    $worksheet->getCell("J$rowIndex")->getValue() ?? '',
                    $o4,
                    $worksheet->getCell("L$rowIndex")->getValue() ?? '',
                    $worksheet->getCell("M$rowIndex")->getValue() ?? '',
                    $worksheet->getCell("N$rowIndex")->getValue() ?? '',
                    $worksheet->getCell("O$rowIndex")->getValue() ?? '',
                ];
            }

            dd($data);
        }
        dd($request);

    }
}
