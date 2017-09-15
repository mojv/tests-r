@extends('layouts.dashboard')

@section('content')
<div class="page-title">
  <div class="title_left">
    <h3>{{ __('messages.pendingEvaluation') }}</h3>
  </div>

  <div class="title_right">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
      <form action="{{route('pendingEvaluation', ['test' => $id])}}" method="get">
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
        <a href="{{route('downladPendings', ['test' => $id])}}"><button type="button" class="btn btn-success btn-sm" class="btn btn-primary" data-toggle="modal" data-target=".createClass-modal">{{ __('messages.donwloadCSV') }}</button></a>
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
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
