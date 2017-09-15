@extends('layouts.dashboard')

@section('content')
<div class="page-title">
  <div class="title_left">
    <h3>{{ __('messages.myClassrooms') }}</h3>
  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
      <form action="{{route('myClasses')}}" method="get">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="{{ __('messages.search') }}...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <button type="button" class="btn btn-success btn-sm" class="btn btn-primary" data-toggle="modal" data-target=".createClass-modal">{{ __('messages.createClassroom') }}</button>
        <button type="button" class="btn btn-danger btn-sm" class="btn btn-primary" onclick="deleteConfirmation()" >{{ __('messages.deleteAllClassrooms') }}</button>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
          {{ $classes->appends(Request::only('q'))->links() }}
        </div>
        <!-- start classrooms list -->
        <table class="table table-striped projects">
          <thead>
            <tr>
              <th style="width: 1%">#</th>
              <th style="width: 20%">{{ __('messages.name') }}</th>
              <th>{{ __('messages.tests') }}</th>
              <th>{{ __('messages.classroomProgress') }}</th>
              <th>{{ __('messages.enrollStudents') }}</th>
              <th style="width: 20%">{{ __('messages.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($classes as $classe)
            <tr>
              <td>{{$classe->id}}</td>
              <td>
                <a>{{$classe->name}}</a>
                <br />
                @if (isset($classe->syllabus))
                  <a href="#"><small>{{ __('messages.seeSyllabus') }}</small></a>
                @else
                  <small>{{ __('messages.noSyllabus') }}</small>
                @endif
              </td>
              <td>
                <ul class="list-inline">
                  @foreach ($classe->tests as $test)
                  <li>
                    <a href="{{route('myTest', ['test'=>$test->id])}}"><input type="image" src="{{ asset('images\exam.png') }}" height="32" value="{{$test->id}}" data-toggle="tooltip" data-placement="top" title="{{$test->name}}"></a>
                  </li>
                  @endforeach
                  <li>
                    <input type="image" src="{{ asset('images\create.png') }}" height="32"  data-toggle="modal" data-target=".createExam-modal" onclick="set_class(this.value)" value="{{$classe->id}}">
                  </li>
                </ul>
              </td>
              <td class="project_progress">
                <?php
                  if (count($classe->tests)!=0){
                    $complete = round((($classe->tests->sum('status'))/(count($classe->tests)))*100,1);
                  }else{
                    $complete = 0;
                  }

                ?>
                <div class="progress progress_sm">
                  <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{$complete}}"></div>
                </div>
                <small>{{$complete}}% {{ __('messages.complete') }}</small>
              </td>
              <td>
                <a href="{{route('enrollStudents', ['classe'=> $classe->id])}}"><button type="button" class="btn btn-success btn-xs">{{ __('messages.enroll') }}</button></a> ({{ count($classe->classrooms) }} {{ __('messages.students') }})
              </td>
              <td>
                <div class="col-xs-4">
                <a href="{{route('classHistory', ['class' => $classe->id])}}" class="btn btn-primary btn-xs"><i class="fa fa-area-chart"></i> {{ __('messages.view') }} </a>
                </div>
                <div class="col-xs-4">
                <button class="btn btn-info btn-xs" value="{{$classe->id}}" onclick="filterForms(this.value)" data-toggle="modal" data-target=".updateClass-modal"><i class="fa fa-pencil"></i> {{ __('messages.edit') }} </button>
                </div>
                <div class="col-xs-3">
                  <form action="{{route('deleteClass')}}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" value="{{$classe->id}}" name="id">
                    <button class="btn btn-danger btn-xs" type="submit" onclick="return confirm('{{ __('messages.deleteClassWaning') }}')"><i class="fa fa-trash-o" ></i> {{ __('messages.delete') }} </button>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade createClass-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ __('messages.createClassroom') }}</h4>
              </div>
              <div class="modal-body">
                <form action="{{ route('createClass') }}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="form-group col-lg-12">
                        <p>{{ __('messages.classroomName') }} *</p>
                        <input type="text" class="form-control" name="name"  required>
                    </div>
                    <div class="form-group col-lg-12">
                      <p>{{ __('messages.classroomSyllabus') }}</p>
                      <div id="alerts"></div>
                      <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                        <div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                          <ul class="dropdown-menu">
                          </ul>
                        </div>

                        <div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            <li>
                              <a data-edit="fontSize 5">
                                <p style="font-size:17px">{{ __('messages.huge') }}</p>
                              </a>
                            </li>
                            <li>
                              <a data-edit="fontSize 3">
                                <p style="font-size:14px">{{ __('messages.normal') }}</p>
                              </a>
                            </li>
                            <li>
                              <a data-edit="fontSize 1">
                                <p style="font-size:11px">{{ __('messages.small') }}</p>
                              </a>
                            </li>
                          </ul>
                        </div>

                        <div class="btn-group">
                          <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                          <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                          <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                          <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                        </div>

                        <div class="btn-group">
                          <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                          <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                          <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                          <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                        </div>

                        <div class="btn-group">
                          <a class="btn" data-edit="justifyleft" title="Align hjj (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                          <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                          <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                          <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                        </div>

                        <div class="btn-group">
                          <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                          <div class="dropdown-menu input-append">
                            <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                            <button class="btn" type="button">Add</button>
                          </div>
                          <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                        </div>

                        <div class="btn-group">
                          <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                          <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                        </div>

                        <div class="btn-group">
                          <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                          <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                        </div>
                      </div>

                      <div id="editor-one" class="editor-wrapper"></div>

                      <textarea name="syllabus" id="descr" style="display:none;"></textarea>

                    </div>
                    <div align="right">
                      <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                      <button type="submit" class="btn btn-primary">{{ __('messages.createClassroom') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /modals -->
        <!-- Modal -->
        <div class="modal fade updateClass-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ __('messages.updateClassroom') }}</h4>
              </div>
              <div class="modal-body">
                @foreach ($classes as $classe)
                <div class="updateForms form{{$classe->id}}">
                  <form action="{{ route('updateClass', ['class' => $classe->id] ) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                      <div class="form-group col-lg-12">
                          <p>{{ __('messages.classroomName') }} *</p>
                          <input type="text" class="form-control" name="name" value="{{$classe->name}}" required>
                      </div>
                      <div class="form-group col-lg-12">
                        <p>{{ __('messages.classroomSyllabus') }}</p>
                        <div id="alerts"></div>
                        <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                          <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            </ul>
                          </div>

                          <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                              <li>
                                <a data-edit="fontSize 5">
                                  <p style="font-size:17px">{{ __('messages.huge') }}</p>
                                </a>
                              </li>
                              <li>
                                <a data-edit="fontSize 3">
                                  <p style="font-size:14px">{{ __('messages.normal') }}</p>
                                </a>
                              </li>
                              <li>
                                <a data-edit="fontSize 1">
                                  <p style="font-size:11px">{{ __('messages.small') }}</p>
                                </a>
                              </li>
                            </ul>
                          </div>

                          <div class="btn-group">
                            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                            <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                            <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                          </div>

                          <div class="btn-group">
                            <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                            <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                            <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                            <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                          </div>

                          <div class="btn-group">
                            <a class="btn" data-edit="justifyleft" title="Align hjj (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                            <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                            <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                            <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                          </div>

                          <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                            <div class="dropdown-menu input-append">
                              <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                              <button class="btn" type="button">Add</button>
                            </div>
                            <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                          </div>

                          <div class="btn-group">
                            <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                            <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                          </div>

                          <div class="btn-group">
                            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                          </div>
                        </div>

                        <div id="editor-one{{$classe->id}}" class="editor-wrapper editor-two"><?php echo $classe->syllabus ?></div>

                        <textarea name="syllabus" id="descr{{$classe->id}}" style="display:none;">{{$classe->syllabus}}</textarea>

                      </div>
                      <div align="right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.updateClassroom') }}</button>
                      </div>
                    </div>
                  </form>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <!-- /modals -->
        <!-- Modal -->
        <div class="modal fade createExam-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ __('messages.createTest') }}</h4>
              </div>
              <form action="{{ route('createExam') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.testName') }} *</p>
                        <input type="text" class="form-control" name="name" id="test_name" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.testWeight') }}</p>
                        <input type="number" min="1" max="1000" step="1" class="form-control" name="test_weight" id="test_weight" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.formName') }}</p>
                        {{ Form::select('form_id', $forms, '', ['class' => 'form-control', 'id' => 'test_form_id', 'required'=>'required'])}}
                    </div>
                    <input type="hidden" name="class_id" value="" id="class_id">
                  </div>
                </div>
                <div class="modal-footer">
                  <div align="right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.createTest') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /modals -->
        <script>
            function filterForms(value){
              $(".updateForms").hide();
              $(".form" + value).show();
            }
            function deleteConfirmation() {
                var txt;
                var person = prompt("{{ __('messages.confirmDeleteStudents') }} {{ __('messages.deleteAllClassrooms') }}", "");
                if (person != "{{ __('messages.deleteAllClassrooms') }}") {
                } else {
                    window.location.href = "{{route('deleteAllClasses')}}";
                }
            }
            function set_class(value){
              $('#test_name').val('');
              $('#test_weight').val('');
              $('#test_form_id').val('');
              $('#class_id').val(value);
            }
            $('#editor-one').bind("DOMSubtreeModified",function(){
              $('#descr').val($('#editor-one').html());
            });
            @foreach ($classes as $classe)
            $('#editor-one{{$classe->id}}').bind("DOMSubtreeModified",function(){
              console.log('hola');
              $('#descr{{$classe->id}}').val($('#editor-one{{$classe->id}}').html());
            });
            @endforeach
        </script>
      </div>
    </div>
  </div>
</div>
@endsection
