@extends('layouts.dashboard')

@section('content')
<div class="page-title">
  <div class="title_left">
    <h3>{{ __('messages.myStudents') }}</h3><button type="button" class="btn btn-success btn-sm" class="btn btn-primary" data-toggle="modal" data-target=".createStudent-modal">{{ __('messages.createStudent') }}</button>
    <button type="button" class="btn btn-danger btn-sm" class="btn btn-primary" onclick="deleteConfirmation()" >{{ __('messages.deleteAllStudents') }}</button>
  </div>
  <div class="title_right">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
      <form action="{{route('myStudents')}}" method="get">
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
      <div class="x_content">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            {{ $students->appends(Request::only('q'))->links() }}
          </div>
          <div class="clearfix"></div>
          @foreach ($students as $student)
          <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
            <div class="well profile_view">
              <div class="col-sm-12">
                <div class="left col-xs-7">
                  <h2>{{$student->name}} {{$student->last_name}}:</h2>
                  <ul class="list-unstyled">
                    <li><i class="fa fa-envelope"></i> {{ __('messages.eMailAddress') }}: {{$student->email}}</li>
                    <li><i class="fa fa-bars"></i> ID: {{$student->student_id}}</li>
                  </ul>
                </div>
                <div class="right col-xs-5 text-center">
                  @if (!empty($student->photo))
                  <img src="{{ asset('storage') }}\{{ $student->photo }}" alt="" class="img-circle" height="120" width="110">
                  @else
                  <img src="{{ asset('images\user.png') }}" alt="" class="img-circle" height="120">
                  @endif
                </div>
              </div>
              <div class="col-xs-12 bottom text-center">
                <div class="col-xs-12 col-sm-6 emphasis">
                  <div class="col-xs-2">
                    <form action="{{route('deleteStudent')}}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <input type="hidden" value="{{$student->id}}" name="id">
                      <button type="upload" class="btn btn-danger btn-xs" onclick="return confirm('{{ __('messages.deleteEstudentWaning') }}')"></i> <i class="fa fa-trash"></i>
                      </button>
                    </form>
                  </div>
                  <div class="col-xs-2">
                    <button type="button" value="{{$student->id}}" class="btn btn-primary btn-xs" onclick="filterForms(this.value)" data-toggle="modal" data-target=".updateStudent-modal">
                      <i class="fa fa-user"> </i> {{ __('messages.updateProfile') }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach

        </div>
        <!-- Modal -->
        <div class="modal fade createStudent-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ __('messages.createStudent') }}</h4>
              </div>
              <div class="modal-body">
                <form action="{{ route('createStudent') }}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="form-group col-lg-6">
                        <p>ID *</p>
                        <input type="text" class="form-control" name="student_id"  required>
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.name') }} *</p>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.lastName') }} *</p>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.eMailAddress') }}</p>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group col-lg-6">
                        <p>{{ __('messages.uploadPhoto') }}</p>
                        <input type="file" class="form-control" name="photo">
                    </div>
                  </div>
                  <div align="right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.createStudent') }}</button>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <form action="{{route('addStudentList')}}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row" align="left">
                    <div class="form-group col-lg-12">
                        <p>{{ __('messages.uploadCsvMsg') }}</p>
                        <input type="file" class="form-control" name="studentList" accept=".csv" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">{{ __('messages.uploadCsv') }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /modals -->
        <!-- Modal -->
        <div class="modal fade updateStudent-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ __('messages.createStudent') }}</h4>
              </div>
              <div class="modal-body">
                @foreach ($students as $student)
                <div class="updateForms form{{$student->id}}">
                  <form action="{{ route('updateStudent', ['student'=>$student->id] )}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="row">
                      <div class="form-group col-lg-6">
                          <p>ID *</p>
                          <input type="text" class="form-control" name="student_id" value="{{ $student->student_id }}" required>
                      </div>
                      <div class="form-group col-lg-6">
                          <p>{{ __('messages.name') }} *</p>
                          <input type="text" class="form-control" name="name" value="{{ $student->name }}" required>
                      </div>
                      <div class="form-group col-lg-6">
                          <p>{{ __('messages.lastName') }} *</p>
                          <input type="text" class="form-control" name="last_name" value="{{ $student->last_name }}" required>
                      </div>
                      <div class="form-group col-lg-6">
                          <p>{{ __('messages.eMailAddress') }}</p>
                          <input type="email" class="form-control" name="email" value="{{ $student->email }}">
                      </div>
                      <div class="form-group col-lg-6">
                          <p>{{ __('messages.uploadPhoto') }}</p>
                          <input type="file" class="form-control" name="photo">
                      </div>
                    </div>
                    <div align="right">
                      <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                      <button type="submit" class="btn btn-primary">{{ __('messages.updateStudent') }}</button>
                    </div>
                  </form>
                </div>
                @endforeach
              </div>
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
                var person = prompt("{{ __('messages.confirmDeleteStudents') }} {{ __('messages.deleteAllStudents') }}", "");
                if (person != "{{ __('messages.deleteAllStudents') }}") {
                } else {
                    window.location.href = "{{route('deleteAllStudents')}}";
                }
            }
        </script>
      </div>
    </div>
  </div>
</div>

@endsection
