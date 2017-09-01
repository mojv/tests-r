<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Form;
use App\Formcoord;
use App\Appformcoord;
use Auth;
use File;
use App\Shareform;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createForm()
    {
        return view('board.createForm');
    }

    public function deleteFormcoords(Form $form)
    {
            $form->formcoords()->delete();
    }

    public function updateFormcoords(Request $data, Form $form)
    {
        if ($data->ajax()){
            foreach ($data->all() as $row){
                $formcoord=new Formcoord($row);
                $form->formcoords()->save($formcoord);
            }
        }
    }

    public function checkName(Request $data)
    {
        $validator = $this->validate($data, [
            'form_name' => 'required|min:4|unique:forms,form_name,NULL,id,user_id,' . Auth::id(),
        ]);
            $user = User::find(Auth::id());
            $form = new Form;
            $form->form_name=$data->input('form_name');
            $user->forms()->save($form);
            $create = 1;
            $form_id=$form->id;
            return view('board.createForm', compact('create', 'form_id'));
    }

    public function updateForm()
    {
        $user = User::find(Auth::id());
        $forms = $user->forms;
        $shareforms = Shareform::with('users','forms')
            ->where('user_owner', Auth::id())
            ->get();
        return view('board.updateForm', compact('forms', 'shareforms'));
    }

    public function editName(Request $data)
    {
        $validator = $this->validate($data, [
            'form_name' => 'required|min:4|unique:forms,form_name,NULL,id,user_id,' . Auth::id(),
        ]);
            $user = User::find(Auth::id());
            $form = $user->forms()->find($data->input('form_id'));
            $form->form_name=$data->input('form_name');
            $form->save();
            return back();
    }

    public function deleteForm($id)
    {
        $user = User::find(Auth::id());
        $form = $user->forms()->find($id);
        if(isset($form->formfile)){
          Storage::delete("/public/".$form->formfile);
        }        
        $form->delete();
        return back();
    }

    public function editForm($id)
    {
        $user = User::find(Auth::id());
        $form = $user->forms()->find($id);
        $formcoords = $form->formcoords;
        $form_id=$id;
        return view('board.editForm', compact('formcoords', 'form_id'));
    }

    public function uploadFormfile(Request $data){
        $this->validate($data, [
            'formfile' => 'mimes:jpeg,bmp,png,pdf|max:10000|required',
        ]);
        $user = User::find(Auth::id());
        $form = $user->forms()->find($data->input('form_id'));
        if ($form->formfile!= "" || $form->formfile!= null){
            Storage::delete("/public/".$form->formfile);
        }
        $path = $data->formfile->store('public');
        $form->formfile =basename($path);
        $form->save();
        return back();
    }

    public function shareForm(Request $data){
        if ($data->ajax()){
            $email =  $data->all()['email'];
            $user = User::where('email', $email)->where('id', "!=", Auth::id())->first();
            return $user;
        }
    }

    public function shareFormCreate(Request $data){
        if ($data->ajax()){
            $user = User::find(Auth::id());
            $form = $user->forms()->find($data->all()['form_id']);
            $shareForm = New Shareform();
            $shareForm->form_id=$form->id;
            $shareForm->user_owner=$user->id;
            $shareForm->user_id=$data->all()['user_id'];
            $shareForm->save();
            $user_sh= array($shareForm, User::find($shareForm->user_id));
            return $user_sh;
        }
    }

    public function shareFormDelete(Request $data){
       if ($data->ajax()){
           $shareForm = Shareform::find($data->all()['id']);
           if ($shareForm->user_owner==Auth::id()){
               $id=$shareForm->id;
               $shareForm->delete();
               return $id;
           }else {
               return 'error';
           }
       }

    }

    public function formList()
    {
        $user = User::find(Auth::id());
        $forms = $user->forms;
        $shareForms = Shareform::with('user_owners')->where('user_id',Auth::id())->with('forms')->get();
        return view('board.formList', compact('forms', 'shareForms'));
    }

    public function templateList()
    {
        $user = User::find(1);
        $forms = $user->forms;
        return view('board.appFormList', compact('forms'));
    }

    public function readForm($id)
    {
        $user = User::find(Auth::id());
        $form = $user->forms()->find($id);
        $formcoords = $form->formcoords;
        $form_id=$id;
        return view('board.readForm', compact('formcoords', 'form_id'));
    }

    public function readSharedForm($id)
    {
        $shareForm = Shareform::where('user_id',Auth::id())->where('form_id',$id)->first();
        $user = User::find($shareForm->user_owner);
        $form = $user->forms()->find($id);
        $formcoords = $form->formcoords;
        $form_id=$id;
        return view('board.readForm', compact('formcoords', 'form_id'));
    }

    public function stopShareForm($id)
    {
        $shareForm = Shareform::where('user_id',Auth::id())->where('form_id',$id)->first();
        $shareForm->delete();
        return back();
    }

    public function appReadForm($id)
    {
        $user = User::find(1);
        $form = $user->forms()->find($id);
        $formcoords = $form->formcoords;
        $form_id=$id;
        return view('board.readForm', compact('formcoords', 'form_id'));
    }

    public function ocrLanguage()
    {
        return view('board.ocrLanguage');
    }

    public function templates()
    {
        return view('board.templates');
    }

    public function software()
    {
        return view('board.software');
    }

    public function donate()
    {
        return view('board.donate');
    }

    public function forum()
    {
        return view('board.forum');
    }

}
