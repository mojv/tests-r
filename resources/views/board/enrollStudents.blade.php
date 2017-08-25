@extends('layouts.dashboard')

@section('content')
<div class="page-title">
  <div class="title_left">
    <h3>{{ __('messages.myStudents') }}</h3>
  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
      <form action="{{route('enrollStudents', ['classe' => $classe])}}" method="get">
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
        <a href="{{route('enrollAllStudents', ['classe' => $classe])}}"><button type="button" class="btn btn-success btn-sm" class="btn btn-primary" data-toggle="modal" data-target=".createClass-modal">{{ __('messages.enrollAllStudents') }}</button></a>
        <a href="{{route('unrollAllStudents', ['classe' => $classe])}}"><button type="button" class="btn btn-danger btn-sm" class="btn btn-primary" onclick="deleteConfirmation()" >{{ __('messages.unrollAllStudents') }}</button></a>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <!-- start classrooms list -->
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
          {{ $students->appends(Request::only('q'))->links() }}
        </div>
        <table class="table table-striped projects">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('messages.photo') }}</th>
              <th>{{ __('messages.name') }}</th>
              <th>{{ __('messages.lastName') }}</th>
              <th>{{ __('messages.eMailAddress') }}</th>
              <th>{{ __('messages.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($students as $student)
            <tr>
              <td>{{$student->student_id}}</td>
              <td>
                <ul class="list-inline">
                  @if (!empty($student->photo))
                  <li>
                    <img  src="{{ asset('storage') }}\{{ $student->photo }}" height="32" value="{{$test->id}}" >
                  </li>
                  @else
                  <li>
                    <img  src="{{ asset('images\user.png') }}" height="32">
                  </li>
                  @endif
                </ul>
              </td>
              <td>{{$student->name}}</td>
              <td>{{$student->last_name}}</td>
              <td>{{$student->email}}</td>
              <?php $enrolled=0 ?>
              @foreach ($student->classrooms as $classroom)
                @if ($classroom->class_id == $classe)
                  <?php $enrolled=1 ?>
                  <?php $enroll_id= $classroom->id ?>
                @endif
              @endforeach
              @if($enrolled==0)
                <td>
                  <button id="enrollButton{{$student->id}}" type="button" class="btn btn-success btn-xs" value="{{$student->id}}" onclick="enrollStudent(this.value)">{{ __('messages.enroll') }}</button>
                </td>
              @else
                <td>
                  <button id="enrollButton{{$student->id}}" type="button" class="btn btn-danger btn-xs" value="{{$student->id}}" onclick="unrollStudent(this.value)">{{ __('messages.unroll') }}</button>
                </td>
              @endif

            </tr>
            @endforeach
          </tbody>
        </table>
        <script>
        function enrollStudent(value){
          var token = $("input[name='_token']").val();
          $.ajax({
              async: true,
              url: '{{route('enrollStudent')}}',
              headers: {"X-CSRF-TOKEN": token},
              type: 'POST',
              contentType: 'application/json',
              dataType: 'json',
              data: JSON.stringify({student_id: value, class_id: {{$classe}} }),
              success: function (data) {
                $('#enrollButton' + value).attr('class', 'btn btn-danger btn-xs');
                $('#enrollButton' + value).attr('onclick', 'unrollStudent(this.value)');
                $('#enrollButton' + value).html('{{ __('messages.unroll') }}');
              },
              error:function(){

              }
          })
        }
        function unrollStudent(value){
          var token = $("input[name='_token']").val();
          $.ajax({
              async: true,
              url: '{{route('unrollStudent')}}',
              headers: {"X-CSRF-TOKEN": token},
              type: 'POST',
              contentType: 'application/json',
              dataType: 'json',
              data: JSON.stringify({student_id: value, class_id: {{$classe}} }),
              success: function (data) {
                $('#enrollButton' + value).attr('class', 'btn btn-success btn-xs');
                $('#enrollButton' + value).attr('onclick', 'enrollStudent(this.value)');
                $('#enrollButton' + value).html('{{ __('messages.enroll') }}');
              },
              error:function(){

              }
          })
        }
        </script>
      </div>
    </div>
  </div>
</div>
@endsection
