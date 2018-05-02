@extends('layouts.dashboard')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="dashboard_graph">

      <form action="{{route('saveAnswers', ['test' => $test->id])}}" method="POST">
        {{ csrf_field() }}
      <div class="row x_title">
        <div class="col-md-6">
          <button type="submit" class="btn btn-success btn-sm">{{__('messages.save')}}</button>
          <a href="{{route('scanInAnswers', ['test' => $test->id])}}"><button type="button" class="btn btn-success btn-sm">{{__('messages.scanIn')}}</button></a>
        </div>
      </div>
      <?php
        $alphabet = range('A', 'Z');
      ?>

      @if(!empty($answers[0]))
        @foreach ($questions as $question)
        <?php $title=$question->field_name.'-'.$question->q_id; $j=array_search($title, $titles); ?>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel tile fixed_height_300">
            <div class="x_title">
              <h2>{{__('messages.fieldName')}}: {{$question->field_name}} @if($question->shape == 3) ({{  __('messages.image')}}) @else  - {{  __('messages.questionNumber')}}: {{$question->q_id}} @endif</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <input type="hidden" name="titles[]" value="{{$question->field_name}}-{{$question->q_id}}" class="form-control">
              @if(!empty($answers[$j]) || $answers[$j] == 0 )
                  @if($question->shape != 3)
                  <p>{{__('messages.correctAnswer')}}</p>
                  <input  name="answers[]" value="{{$answers[$j]}}" class="form-control">
                  @else
                    <p>{{__('messages.imageGrade')}}</p>
                    <select name="answers[]" class="form-control" required>
                      <option value="*" <?php if($answers[$j]=='*'){echo 'selected';}?>>{{__('messages.doNotEvaluate')}}</option>
                      <option value="img" <?php if($answers[$j]=='img'){echo 'selected';}?>>{{__('messages.gradeImg')}}</option>
                    </select>
                  @endif
              @else
                  @if($question->shape != 3)
                  <p>{{__('messages.correctAnswer')}}</p>
                  <input  name="answers[]" value="*" class="form-control">
                  @else
                    <p>{{__('messages.imageGrade')}}</p>
                    <select name="answers[]" class="form-control" required>
                      <option value="*">{{__('messages.doNotEvaluate')}}</option>
                      <option value="img">{{__('messages.gradeImg')}}</option>
                    </select>
                  @endif
              @endif
              <br>
              <p>{{__('messages.questionWeight')}}</p>
              @if(!empty($answers[$j]) || $answers[$j] == 0 )
                <input type="number" step="1" name="weights[]" value="{{$weights[$j]}}" class="form-control" min="1" required>
              @else
                <input type="number" step="1" name="weights[]" value="1" class="form-control" min="1" required>
              @endif
            </div>
          </div>
        </div>
        @endforeach

      @else

        @foreach ($questions as $question)
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel tile fixed_height_300">
            <div class="x_title">
              <h2>{{__('messages.fieldName')}}: {{$question->field_name}} @if($question->shape == 3) ({{  __('messages.image')}}) @else  - {{  __('messages.questionNumber')}}: {{$question->q_id}} @endif</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <input type="hidden" name="titles[]" value="{{$question->field_name}}-{{$question->q_id}}">
              @if($question->shape != 3)
              <p>{{__('messages.correctAnswer')}}</p>
              <input  name="answers[]" value="*" class="form-control">
              @else
                <p>{{__('messages.imageGrade')}}</p>
                <select name="answers[]" class="form-control" required>
                  <option value="*">{{__('messages.doNotEvaluate')}}</option>
                  <option value="img">{{__('messages.gradeImg')}}</option>
                </select>
              @endif
              <br>
              <p>{{__('messages.questionWeight')}}</p>
              <input type="number" step="1" name="weights[]" value="1" class="form-control" min="1" required>
            </div>
          </div>
        </div>
        @endforeach
      @endif

      </form>
      <div class="clearfix"></div>
    </div>
  </div>

</div>
@endsection
