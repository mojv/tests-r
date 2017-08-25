<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Classe;
use App\Classroom;
use App\Test;
use App\Form;
use App\Formcoord;
use App\ShareForm;
use App\Result;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use DB;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myStudents(Request $data)
    {
        $q=$data->input('q');
        if ($q)
        {
            $students = Student::where('user_id', Auth::id())->where(function($query) use ($q) {
                $query->where('name', 'LIKE', '%'.$q.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$q.'%')
                    ->orWhere('email', 'LIKE', '%'.$q.'%');
            })->orderBy('name')->paginate(9);
        }
        else
        {
            $students = Student::where('user_id',Auth::id())->orderBy('name')->paginate(9);
        }
        return view('board.myStudents', compact('students'));
    }

    public function createStudent(Request $data)
    {
        $validator = $this->validate($data, [
            'student_id' => [
                'required',
                Rule::unique('students','student_id')->where(function ($query) {
                      $query->where('user_id', Auth::id());
                }),
            ],
            'name' => 'required',
            'last_name' => 'required',
            'photo' => 'image|max:500'
        ]);
        $user = User::find(Auth::id());
        $student = new Student($data->all());
        if ($data->hasFile('photo')){
            $path = $data->photo->store('public');
            $student->photo=  basename($path);
        }
        $user->students()->save($student);
        $user->save();
        return back();
    }

    public function updateStudent(Request $data, $id)
    {
        $student = User::find(Auth::id())->students()->find($id);
        $validator = $this->validate($data, [
            'student_id' => [
                'required',
                Rule::unique('students','student_id')->ignore($student->id)->where(function ($query) {
                      $query->where('user_id', Auth::id());
                }),
            ],
            'name' => 'required',
            'last_name' => 'required',
            'photo' => 'image|max:500'
        ]);
        $photo=$student->photo;
        $student->update($data->all());
        if ($data->hasFile('photo')){
            if (isset($photo)){
                Storage::delete("/public/".$photo);
            }
            $path = $data->photo->store('public');
            $student->photo=  basename($path);
        }
        $student->save();
        return back();
    }

    public function deleteStudent(Request $data)
    {
        $student = User::find(Auth::id())->students()->find($data->input('id'));
        $student->delete();
        return back();
    }

    public function deleteAllStudents()
    {
        DB::table('students')->where('user_id', Auth::id())->delete();
        return back();
    }

    public function addStudentList(Request $data)
    {
      ini_set('max_execution_time', 18000000);
      $user = User::find(Auth::id());
      $this->validate($data, [
          'studentList' => 'required'
      ]);
      $path = $data->studentList->path();
      $file = fopen($path,"r");
      $num=1;
      $errors=[];
      while(!feof($file)){
          try {
              $row="";
              $row=fgetcsv($file, 0, ",");
              DB::table('students')->insert(
                array(
                    'user_id' => Auth::id(),
                    'student_id' => $row[0],
                    'name' => $row[1],
                    'last_name'=> $row[2],
                    'email'=> $row[3]
                )
              );
          } catch(\Illuminate\Database\QueryException $ex){
              array_push($errors,"The line # $num of the CSV file could not be uploaded.");
          }
          $num++;
      }
      ini_set('max_execution_time', 30);
      return back()->withErrors($errors);
    }

    public function myClasses(Request $data)
    {
        $q=$data->input('q');
        if ($q)
        {
            $classes = Classe::where('user_id',Auth::id())
            ->where('name', 'LIKE', "%$q%")
            ->with('tests')
            ->with('classrooms')
            ->orderBy('name')
            ->paginate(20);
        }
        else
        {
            $classes = Classe::where('user_id',Auth::id())->with('tests')->with('classrooms')->orderBy('name')->paginate(20);
        }
        $users_id=[Auth::id(), 1];
        $forms_id=[];
        $shareForms=ShareForm::where('user_id',Auth::id())->get();
        foreach ($shareForms as $form) {
          array_push($forms_id, $form->form_id);
        }
        $forms = DB::table('forms')
            ->select('form_name', 'id')
            ->whereIn('user_id', $users_id)
            ->whereIn('id',$forms_id, 'or')
            ->pluck('form_name', 'id')->prepend('','');
        return view('board.myClasses', compact('classes', 'forms'));
    }

    public function createClass(Request $data)
    {
        $validator = $this->validate($data, [
            'name' => [
                'required',
                Rule::unique('classes','name')->where(function ($query) {
                      $query->where('user_id', Auth::id());
                }),
            ],
        ]);
        $user = User::find(Auth::id());
        $classe = new Classe($data->all());
        $user->classes()->save($classe);
        $user->save();
        return back();
    }

    public function updateClass(Request $data, $id)
    {
        $classe = User::find(Auth::id())->classes()->find($id);
        $validator = $this->validate($data, [
            'name' => [
                'required',
                Rule::unique('classes','name')->ignore($classe->id)->where(function ($query) {
                      $query->where('user_id', Auth::id());
                }),
            ],
        ]);
        $classe->update($data->all());
        $classe->save();
        return back();
    }

    public function deleteClass(Request $data)
    {
        $student = User::find(Auth::id())->classes()->find($data->input('id'));
        $student->delete();
        return back();
    }

    public function deleteAllClasses()
    {
        DB::table('classes')->where('user_id', Auth::id())->delete();
        return back();
    }

    public function createExam(Request $data)
    {
        $validator = $this->validate($data, [
          'class_id' => 'required',
          'name' => 'required',
        ]);
        $classe = Classe::find($data->input('class_id'));
        if ($classe->user_id == Auth::id()){
            $test = new Test($data->all());
            $classe->tests()->save($test);
        }
        return back();
    }

    public function enrollStudents(Request $data, $classe)
    {
        $q=$data->input('q');
        if (!empty($q))
        {
            $students = Student::where('user_id', Auth::id())
              ->where(function($query) use ($q) {
                  $query->where('name', 'LIKE', '%'.$q.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$q.'%')
                    ->orWhere('email', 'LIKE', '%'.$q.'%');
              })->with('classrooms')->orderBy('name')->paginate(10);
        }
        else
        {
            $students = Student::where('user_id',Auth::id())->with('classrooms')->orderBy('name')->paginate(9);
        }
        //return $students;
        return view('board.enrollStudents', compact('students', 'classe'));
    }

    public function enrollStudent(Request $data){
        if ($data->ajax()){
            $classe = User::find(Auth::id())->classes()->find($data->all()['class_id']);
            $enroll= new Classroom;
            $enroll->class_id=$classe->id;
            $enroll->student_id=$data->all()['student_id'];
            $enroll->save();
            return $enroll;
        }
    }

    public function unrollStudent(Request $data){
        if ($data->ajax()){
            $classe = User::find(Auth::id())->classes()->find($data->all()['class_id']);
            $temp= Classroom::where('student_id',$data->all()['student_id'])->where('class_id',$classe->id)->get();
            $enroll= Classroom::where('student_id',$data->all()['student_id'])->where('class_id',$classe->id);
            $enroll->delete();
            return $temp;
        }
    }

    public function enrollAllStudents($id){
      ini_set('max_execution_time', 18000000);
      $classe = User::find(Auth::id())->classes()->find($id);
      $classe_id = $classe->id;
      $students = Student::where('user_id', Auth::id())->get();
      foreach ($students as $student) {
        DB::table('classrooms')->insert(
          array(
              'class_id' => $classe_id,
              'student_id' => $student->id,
            )
          );
      }
      ini_set('max_execution_time', 30);
      return back();
    }

    public function unrollAllStudents($id){
      $classe = User::find(Auth::id())->classes()->find($id);
      $enroll= Classroom::where('class_id',$classe->id)->delete();
      return back();
    }

    public function myTest($id, Request $data)
    {
      $q=$data->input('q');
      $id_class = Test::find($id)->class_id;
      $classe = User::find(Auth::id())->classes()->find($id_class);
      $test= $classe->tests()->find($id);
      $results2 = Result::select('grade')->where('test_id', $test->id)->get();
      if (!empty($q)){
        $results =  DB::table('students')
                  ->select('results.omr_responses','results.img_responses','results.grade', 'students.*')
                  ->join('results', 'students.id', '=', 'results.student_id')
                  ->where('results.test_id', $test->id)
                  ->where(function($query) use ($q) {
                      $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$q.'%')
                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                  })->paginate(20);
      }
      else{
        $results =  DB::table('students')
                  ->select('results.omr_responses','results.img_responses','results.grade', 'students.*')
                  ->join('results', 'students.id', '=', 'results.student_id')
                  ->where('results.test_id', $test->id)
                  ->paginate(20);
      }
      //return $results;
      $enrolls = Classroom::select('id')->where('class_id', $classe->id)->get();
      $questions = Formcoord::where(function($query){
                $query->where('idField',0)
                      ->orWhereNull('idField');
            })->select('shape', 'field_name', 'q_id')
            ->whereIn('shape', [1,2,3])
            ->where('form_id', $test->form_id)
            ->groupBy('shape', 'field_name', 'q_id')->get();
      return view('board.myTest', compact('test', 'classe', 'enrolls','results2', 'results', 'questions'));
    }

    public function defineAnswers($id)
    {
      $id_class = Test::find($id)->class_id;
      $classe = User::find(Auth::id())->classes()->find($id_class);
      $test= $classe->tests()->find($id);
      $titles=explode("¬",$test->titles);
      $answers=explode("¬",$test->answers);
      $weights=explode("¬",$test->answers_weight);
      $questions = Formcoord::where(function($query){
                $query->where('idField',0)
                      ->orWhereNull('idField');
            })->selectRaw('field_name, q_id, min(q_option) as q_min, max(q_option) as q_max, shape')
            ->whereIn('shape', [1,2,3])
            ->where('form_id', $test->form_id)
            ->groupBy('shape', 'field_name', 'q_id')
            ->get();
      return view('board.defineAnswers', compact('test', 'classe', 'questions', 'titles', 'answers', 'weights'));
    }

    public function saveAnswers(Request $data, $id)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $titles=[];
        $answers=[];
        $weights=[];
        foreach ($data->input('titles') as $title){
          array_push($titles,$title);
        }
        foreach ($data->input('answers') as $answer){
          array_push($answers, $answer);
        }
        foreach ($data->input('weights') as $weight){
          array_push($weights,$weight);
        }
        $test->titles=implode("¬",$titles);
        $test->answers=implode("¬",$answers);
        $test->answers_weight=implode("¬",$weights);
        $test->save();
        return back();
    }

    public function gradeTestForms($id)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $formcoords = Form::find($test->form_id)->formcoords;
        $form_id=$test->form_id;
        return view('board.gradeTestForms', compact('formcoords', 'form_id', 'test'));
    }

    public function storeGradeOmr($id, Request $data)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $student = Student::where('user_id', Auth::id())->where('student_id', $data->input('student_id'))->first();
        $result= New Result();
        $result->student_id=$student->id;
        $result->omr_responses=$data->input('omr_responses');
        $result->omr_grade=$data->input('omr_grade');
        $result->grade=$data->input('omr_grade');
        $test->results()->save($result);
    }
    public function storeGradeImg($id, Request $data)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $student = Student::where('user_id', Auth::id())->where('student_id', $data->input('student_id'))->first();
        $result = Result::where('test_id', $id)->where('student_id', $student->id)->first();
        if(isset($result)){
          $result->img_responses=$data->input('img_responses');
          $result->img_grade=$data->input('img_grade');
          if (isset($result->grade) || $result->grade != null){
              $final = $result->grade + $data->input('img_grade');
              $result->grade=$final;
          }else{
              $result->grade=$data->input('img_grade');
          }
          $result->save();
        }else{
          $result= New Result();
          $result->student_id=$student->id;
          $result->img_responses=$data->input('img_responses');
          $result->img_grade=$data->input('img_grade');
          $result->grade=$data->input('img_grade');
          $test->results()->save($result);
        }
    }

    public function closeTest($id, $action){
      $id_class = Test::find($id)->class_id;
      $classe = User::find(Auth::id())->classes()->find($id_class);
      $test= $classe->tests()->find($id);
      $test->status=$action;
      $test->save();
      return back();
    }
}
