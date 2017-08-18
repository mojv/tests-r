<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Classe;
use App\Test;
use App\Form;
use App\ShareForm;
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
            $students = Student::where('user_id',Auth::id())
            ->where(DB::raw('CONCAT_WS(" ",name, last_name)'), 'like', $q)
            ->paginate(9);
            //'CONCAT_WS(name," ",last_name," ",email," ",student_id)', 'like', "%{$q%"
        }
        else
        {
            $students = Student::where('user_id',Auth::id())->paginate(9);
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
            ->paginate(10);
        }
        else
        {
            $classes = Classe::where('user_id',Auth::id())->with('tests')->paginate(10);
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

}
