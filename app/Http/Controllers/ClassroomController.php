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
use App\Shareform;
use App\Result;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Response;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function deleteAllStudents($classe)
    {
        DB::table('students')->where('user_id', Auth::id())->where('class_id', $classe)->delete();
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
      $class_id=$data->class_id;
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
                    'email'=> $row[3],
                    'class_id' => $class_id
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
            $classes = Classe::where('user_id',Auth::id())->with('tests')->orderBy('name')->paginate(20);
        }
        $users_id=[Auth::id()];
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
        if ($q)
        {
            $students = Student::where('user_id', Auth::id())
            ->where('class_id',$classe)
            ->where(function($query) use ($q) {
                $query->where('name', 'LIKE', '%'.$q.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$q.'%')
                    ->orWhere('email', 'LIKE', '%'.$q.'%')
                    ->orWhere('student_id', 'LIKE', '%'.$q.'%');
            })->orderBy('name')->paginate(20);
        }
        else
        {
            $students = Student::where('user_id',Auth::id())->where('class_id',$classe)->orderBy('name')->paginate(9);
        }
        return view('board.myStudents', compact('students', 'classe'));
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
      if (!empty($q)){
        $results =  DB::table('students')
                  ->select('results.omr_responses','results.img_responses','results.grade', 'students.*')
                  ->join('results', 'students.id', '=', 'results.student_id')
                  ->where('results.test_id', $test->id)
                  ->where(function($query) use ($q) {
                      $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$q.'%')
                        ->orWhere('email', 'LIKE', '%'.$q.'%')
                        ->orWhere('students.student_id', 'LIKE', '%'.$q.'%');
                  })->paginate(20);
      }
      else{
        $results =  DB::table('students')
                  ->select('results.omr_responses','results.img_responses','results.grade', 'students.*')
                  ->join('results', 'students.id', '=', 'results.student_id')
                  ->where('results.test_id', $test->id)
                  ->paginate(20);
      }
      $results2 = Result::where('test_id', $test->id)->count();
      $results3 = round(Result::where('test_id', $test->id)->avg('grade'),2);
      $results4 = Result::where('test_id', $test->id)->inRandomOrder()->take(1000)->get();
      $titles=explode(";",$test->titles);
      $answers=explode(";",$test->answers);
      $enrolls = Student::where('class_id', $classe->id)
                  ->whereNotExists(function($query) use($id){
                      $query->select(DB::raw(1))
                            ->from('results')
                            ->whereRaw("students.id = results.student_id AND results.test_id = $id");
                  })->count();
      $questions = Formcoord::where(function($query){
                $query->where('idField',0)
                      ->orWhereNull('idField');
            })->selectRaw('field_name, q_id, min(q_option) as q_min, max(q_option) as q_max, shape, min(id) as id')
            ->whereIn('shape', [1,2,3])
            ->where('form_id', $test->form_id)
            ->groupBy('shape', 'field_name', 'q_id')
            ->get();
      $omr_answers=[];
      $img_answers=[];
      $grades=[];
      foreach ($results4 as $result){
        if (isset($result->omr_responses)){
          array_push($omr_answers,explode(";",$result->omr_responses));
        }
        if (isset($result->img_responses) ){
          array_push($img_answers,explode(";",$result->img_responses));
        }
        array_push($grades,$result->grade);
      }
      if (count($omr_answers)>0) {
        $omr_answers=array_map(null, ...$omr_answers);
      }
      if (count($img_answers)>0) {
        $img_answers=array_map(null, ...$img_answers);
      }
      foreach ($img_answers as $img_answer) {
        array_push($omr_answers,$img_answer);
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
      return view('board.myTest', compact('test', 'classe', 'enrolls', 'results', 'results2', 'results3', 'results4','titles', 'answers', 'questions', 'items','omr_answers', 'forms', 'grades'));
    }

    public function defineAnswers($id)
    {
      $id_class = Test::find($id)->class_id;
      $classe = User::find(Auth::id())->classes()->find($id_class);
      $test= $classe->tests()->find($id);
      $titles=explode(";",$test->titles);
      $answers=explode(";",$test->answers);
      $weights=explode(";",$test->answers_weight);
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
        $test->titles=implode(";",$titles);
        $test->answers=implode(";",$answers);
        $test->answers_weight=implode(";",$weights);
        $test->save();
        return back();
    }

    public function scanInAnswers($id)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $formcoords = Form::find($test->form_id)->formcoords;
        $form_id=$test->form_id;
        $scanInAnswers=1;
        $pro = 0;
        return view('board.gradeTestForms', compact('formcoords', 'form_id', 'test', 'scanInAnswers', 'pro'));
    }

    public function gradeTestForms($id)
    {
        $id_class = Test::find($id)->class_id;
        $user = User::find(Auth::id());
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $formcoords = Form::find($test->form_id)->formcoords;
        $form_id=$test->form_id;
        $scanInAnswers=0;
        $last = $user->pro - $user->pro_sheets;
        $pro = str_split(str_pad($last, 7, "0", STR_PAD_LEFT));
        $pro = rand(intval(111), intval(999)) . $pro[0] . rand(intval(111), intval(999)) . $pro[1] . rand(intval(111), intval(999)) . $pro[2] . rand(intval(111), intval(999)) . $pro[3] . rand(intval(111), intval(999)) . $pro[4] . rand(intval(111), intval(999)) . $pro[5] . rand(intval(111), intval(999)) . $pro[6];
        return view('board.gradeTestForms', compact('formcoords', 'form_id', 'test', 'scanInAnswers', 'pro'));
    }

    public function storeGradeOmr($id, Request $data)
    {

        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        if ($data->input('scanInAnswers')==1) {
          $questions = Formcoord::where(function($query){
                    $query->where('idField',0)
                          ->orWhereNull('idField');
                })->selectRaw('field_name, q_id, min(q_option) as q_min, max(q_option) as q_max, shape')
                ->whereIn('shape', [1,2])
                ->where('form_id', $test->form_id)
                ->groupBy('shape', 'field_name', 'q_id')
                ->get();
          $titles=[];
          $answers=[];
          $weights=[];
          $fields=explode(";",$data->input('omr_responses'));
          foreach ($fields as $field) {
            if($field==""){
              array_push($answers, "*");
            }else {
              array_push($answers, $field);
            }
            array_push($weights, 1);
          }
          foreach ($questions as $question) {
            array_push($titles,$question->field_name . "-" . $question->q_id);
          }
          $test->titles=implode(";",$titles);
          $test->answers=implode(";",$answers);
          $test->answers_weight=implode(";",$weights);
          $test->save();
          return $test;
        }else {
          $student = Student::where('user_id', Auth::id())->where('student_id', $data->input('student_id'))->where('class_id',$id_class)->first();
          $enroll = Classroom::where('class_id', $classe->id)->where('student_id', $student->id)->first();
          if (Result::where('student_id', $student->id)->exists()) {
            $result = Result::where('student_id', $student->id)->first();
          }else{
            $result= New Result();
          }
          $result->student_id=$student->id;
          $result->omr_responses=$data->input('omr_responses');
          $result->omr_grade=$data->input('omr_grade');
          $result->grade=$data->input('omr_grade');
          $test->results()->save($result);
          return $result;
        }
    }

    public function storeGradeImg($id, Request $data)
    {
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $student = Student::where('user_id', Auth::id())->where('student_id', $data->input('student_id'))->where('class_id',$id_class)->first();
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
        }elseif(isset($enroll->id)){
          $result= New Result();
          $result->student_id=$student->id;
          $result->img_responses=$data->input('img_responses');
          $result->img_grade=$data->input('img_grade');
          $result->grade=$data->input('img_grade');
          $test->results()->save($result);
        }
        return $result;
    }

    public function closeTest($id, $action){
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $test->status=$action;
        $test->save();
        return back();
    }

    public function downloadResults($id){
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $results =  DB::table('students')
                  ->select('results.omr_responses','results.img_responses','results.grade', 'students.*')
                  ->join('results', 'students.id', '=', 'results.student_id')
                  ->where('results.test_id', $test->id)
                  ->get();
        $filename = "results.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('Student ID', 'Name', 'Last Name', 'OMR responses', 'IMG results', 'Final Grade'));
        foreach($results as $row) {
            fputcsv($handle, array($row->student_id, $row->name, $row->last_name, str_replace(";","-",$row->omr_responses), str_replace(";","-",$row->img_responses), $row->grade));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'results.csv', $headers);
    }

    public function deleteResults($id){
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $results =  Result::where('test_id', $test->id);
        $results->delete();
        return back();
    }

    public function updateTest($id, Request $data){
        $validator = $this->validate($data, [
          'name' => 'required',
          'form_id' => 'required',
        ]);
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $test->update($data->all());
        $test->save();
        return back();
    }

    public function deleteTest($id, Request $data){
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        $test= $classe->tests()->find($id);
        $test->delete();
        return redirect(route('myClasses'));
    }

    public function pendingEvaluation(Request $data, $id)
    {
        $q=$data->input('q');
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
        if (!empty($q))
        {
            $students = Student::where('class_id', $classe->id)
              ->whereNotExists(function($query) use($id){
                  $query->select(DB::raw(1))
                        ->from('results')
                        ->whereRaw("students.id = results.student_id AND results.test_id = $id");
              })
              ->where(function($query) use ($q) {
                  $query->where('students.name', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.last_name', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.email', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.student_id', 'LIKE', '%'.$q.'%');
              })->paginate(10);
        }
        else
        {
            $students = Student::where('class_id', $classe->id)
            ->whereNotExists(function($query) use($id){
                $query->select(DB::raw(1))
                      ->from('results')
                      ->whereRaw("students.id = results.student_id AND results.test_id = $id");
            })
            ->paginate(10);
        }
        //return $students;
        return view('board.pendingEvaluation', compact('students', 'id'));
    }

    public function downladPendings(Request $data, $id)
    {
        $q=$data->input('q');
        $id_class = Test::find($id)->class_id;
        $classe = User::find(Auth::id())->classes()->find($id_class);
            $students = Student::where('class_id', $classe->id)
            ->whereNotExists(function($query) use($id){
                $query->select(DB::raw(1))
                      ->from('results')
                      ->whereRaw("students.id = results.student_id AND results.test_id = $id");
            })->get();
        $filename = "pendings.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('Student ID', 'Name', 'Last Name', 'E-mail'));
        foreach($students as $row) {
            fputcsv($handle, array($row->student_id, $row->name, $row->last_name, $row->email));
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'pendings.csv', $headers);
    }

    public function classHistory(Request $data, $id){
        $q=$data->input('q');
        $classe = User::find(Auth::id())->classes()->find($id);
        $tests = Test::where('class_id', $classe->id)->get();
        $avgs=[]; $quartiles=[]; $testsNames=[]; $testIds=[]; $dataq=[];
        foreach ($tests as $test) {
          $quartile=[];
          array_push($dataq, Result::where('test_id', $test->id)->pluck('grade'));
          $total=Result::where('test_id', $test->id)->count('grade');
          $middle=ceil($total/2);
          array_push($avgs,round(Result::where('test_id', $test->id)->avg('grade'),2));
          array_push($quartile,round(Result::select('grade')->where('test_id', $test->id)->orderBy('grade', 'asc')->limit($middle)->get()->avg('grade'),2));
          array_push($quartile,round(Result::select('grade')->where('test_id', $test->id)->orderBy('grade', 'desc')->limit($middle)->get()->avg('grade'),2));
          array_push($quartile,round(Result::where('test_id', $test->id)->min('grade'),2));
          array_push($quartile,round(Result::where('test_id', $test->id)->max('grade'),2));
          array_push($quartiles, $quartile);
          array_push($testsNames, $test->name);
          array_push($testIds, $test->id);
        }
        if (!empty($q)){
            $students = Student::where('class_id', $classe->id)
              ->where(function($query) use ($q) {
                  $query->where('students.name', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.last_name', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.email', 'LIKE', '%'.$q.'%')
                    ->orWhere('students.student_id', 'LIKE', '%'.$q.'%');
                  })->paginate(10);
        }
        else{
            $students = Student::where('class_id', $classe->id)
            ->paginate(10);
        }
        foreach ($students as $student) {
           $grades = Result::select('test_id', 'grade')
              ->where('student_id', $student->id)
              ->whereIn('test_id', $testIds)
              ->get();
           $student->setAttribute('grades', $grades);
        }
        return view('board.classHistory', compact('avgs', 'quartiles', 'testsNames', 'students', 'id', 'results', 'tests', 'dataq'));
    }

    public function downloadClassHistory($id){
        $classe = User::find(Auth::id())->classes()->find($id);
        $tests = Test::where('class_id', $classe->id)->get();
        $avgs=[]; $quartiles=[]; $testsNames=[]; $testIds=[];
        $testIds=[];
        foreach ($tests as $test) {
          array_push($testIds, $test->id);
        }
        $students = Classroom::where('class_id', $classe->id)
          ->join('students', 'classrooms.student_id', "=", 'students.id')
          ->get();
        $filename = "pendings.csv";
        $handle = fopen($filename, 'w+');
        $titles=['ID','Photo	Nombre',	'Apellido',	'E-mail'];
        foreach ($tests as $test){
          array_push($titles, $test->name);
        }
        array_push($titles, 'Final Grade');
        fputcsv($handle, $titles);
        foreach ($students as $student) {
           $row = [$student->student_id, $student->name, $student->last_name, $student->email];
           $grades = Result::select('test_id', 'grade')
              ->where('student_id', $student->id)
              ->whereIn('test_id', $testIds)
          ->get();
          $final=0; $points=0; $temp =0; $grades2=[];
          foreach ($tests as $test){
            foreach ($grades as $grade){
              if ($grade->test_id == $test->id){
                  $temp =1;
                  array_push($row, $grade->grade);
                  $final=$final+($grade->grade*$test->test_weight);
                  $points=$points+$test->test_weight;
                  break;
              }else{
                $temp =0;
              }
            }
            if ($temp==0){
                if(isset($grade)){
                  array_push($row, 0);
                  $final=$final+($grade->grade*$test->test_weight);
                  $points=$points+$test->test_weight;
                }
            }
          }
          if(isset($grade)){
            $final=$final/$points;
          }
          array_push($row, $final);
          fputcsv($handle, $row);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'results.csv', $headers);
    }

    public function createQrPdf(Request $data){
       $user = User::find(Auth::id());
       $form = $user->forms()->find($data->form_id);
       $formcoords = $form->formcoords->where('shape',5)->where('idField',1);
       $students = Student::where('user_id',Auth::id())->where('class_id',$data->classe)->orderBy('name')->get();
       if ($students->isEmpty()) {
         $errors=[];
         array_push($errors,"You do not have any enrolled students");
         return back()->withErrors($errors);
       }
       if ($formcoords->isEmpty()) {
         $errors=[];
         array_push($errors,"The form must have a QR field marked as ID");
         return back()->withErrors($errors);
       }
       return view('board.createQrPdf', compact('students', 'formcoords'));
    }

}
