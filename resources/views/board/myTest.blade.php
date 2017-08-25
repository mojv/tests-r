@extends('layouts.dashboard')

@section('content')

  <div class="row tile_count" align="center">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-users"></i> {{ __('messages.numberStudents') }}</span>
      <div class="count">@if(empty(count($classe->classrooms))) - @else {{ count($enrolls) }} @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-thumbs-up"></i> {{ __('messages.studentsEvaluated') }}</span>
      <div class="count green">@if(empty(count($results))) - @else {{ count($results2) }} @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-area-chart"></i> {{ __('messages.averageScore') }} </span>
      <div class="count">@if(empty($results->avg('grade'))) - @else {{ $results2->avg('grade') }}% @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-sort-numeric-asc"></i> {{ __('messages.numberQuestions') }}</span>
      <div class="count green">@if(empty(count($questions))) - @else {{ count($questions) }} @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-sort-numeric-asc"></i> {{ __('messages.defineAnswers') }}</span>
      <div class="count green"><a href="{{route('defineAnswers', ['test' => $test->id])}}"><button type="button" class="btn btn-round btn-info">{{ __('messages.define') }}</button></div></a>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-gears"></i> {{ __('messages.readForms') }}</span>
      <div class="count green"><a href="{{route('gradeTestForms', ['test' => $test->id])}}"><button type="button" class="btn btn-round btn-success">{{ __('messages.read') }}</button></div></a>
    </div>
  </div>
  <!-- /top tiles -->

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">

        <div class="row x_title">
          <div class="col-md-6">
            <h3>{{ __('messages.responsesList') }}<small></small></h3>
          </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
          {{ $results->appends(Request::only('q'))->links() }}
          <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <form action="{{route('myTest', ['test' => $test->id])}}" method="get">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ __('messages.search') }}...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>
        <table class="table table-striped projects">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('messages.photo') }}</th>
              <th>{{ __('messages.name') }}</th>
              <th>{{ __('messages.studentAnswers') }}</th>
              <th>{{ __('messages.imgGrading') }}</th>
              <th>{{ __('messages.finalGrade') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($results as $result)
            <tr>
              <td>{{$result->student_id}}</td>
              <td>
                <ul class="list-inline">
                  @if (!empty($result->photo))
                  <li>
                    <img  src="{{ asset('storage') }}\{{ $result->photo }}" height="32" >
                  </li>
                  @else
                  <li>
                    <img  src="{{ asset('images\user.png') }}" height="32">
                  </li>
                  @endif
                </ul>
              </td>
              <td>{{$result->name}} {{$result->last_name}}</td>
              <td>{{$result->omr_responses}}</td>
              <td>{{$result->img_responses}}</td>
              <td>{{$result->grade}}%</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="clearfix"></div>
      </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <br>
      @if($test->status==0)
        <a href="{{route('closeTest', ['test'=>$test->id, 'action'=>1])}}"><button type="button" class="btn btn-warning btn-lg">{{ __('messages.closeTest') }}</button></a>
      @else
        <a href="{{route('closeTest', ['test'=>$test->id, 'action'=>0])}}"><button type="button" class="btn btn-success btn-lg">{{ __('messages.openTest') }}</button></a>
      @endif
    </div>
  </div>

@endsection

@section('script')


@endsection
