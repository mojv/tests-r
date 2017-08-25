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
        </div>
      </div>
      <?php
        $alphabet = range('A', 'Z');
      ?>

      @if(!empty($answers[0]) || $answers[0] != NULL)
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
              @if($question->q_min == 'A')
                <?php $from=0; $to=array_search($question->q_max, $alphabet); ?>
              @elseif($question->q_min == 0)
                <?php $from=0; $to=$question->q_max; ?>
              @elseif($question->q_min == 1)
                <?php $from=1; $to=$question->q_max+1; ?>
              @endif
              <br>
              @if($question->shape != 3)
              <p>{{__('messages.correctAnswer')}}</p>
              <select name="answers[]" class="form-control" required>
                <option value="*" <?php if($answers[$j]=='*'){echo 'selected';} ?>>{{__('messages.doNotEvaluate')}}</option>
                @for ($i=$from;$i<=$to;$i++)
                @if($question->q_min == 'A')
                  <option value="{{$alphabet[$i]}}" <?php if($answers[$j]==$alphabet[$i]){echo 'selected';} ?>>{{$alphabet[$i]}}</option>
                @else
                  <option value="{{$i}}" <?php if($answers[$j]==$i){echo 'selected';} ?>>{{$i}}</option>
                @endif
                @endfor
              </select>
              @else
                <p>{{__('messages.imageGrade')}}</p>
                <select name="answers[]" class="form-control" required>
                  <option value="*" <?php if($answers[$j]=='*'){echo 'selected';}?>>{{__('messages.doNotEvaluate')}}</option>
                  <option value="img" <?php if($answers[$j]=='img'){echo 'selected';}?>>{{__('messages.gradeImg')}}</option>
                </select>
              @endif
              <br>
              <p>{{__('messages.questionWeight')}}</p>
              <input type="number" step="1" name="weights[]" value="{{$weights[$j]}}" class="form-control" min="1" required>
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
              @if($question->q_min == 'A')
                <?php $from=0; $to=array_search($question->q_max, $alphabet); ?>
              @elseif($question->q_min == 0)
                <?php $from=0; $to=$question->q_max; ?>
              @elseif($question->q_min == 1)
                <?php $from=1; $to=$question->q_max+1; ?>
              @endif
              <br>
              @if($question->shape != 3)
              <p>{{__('messages.correctAnswer')}}</p>
              <select name="answers[]" class="form-control" required>
                <option value="*">{{__('messages.doNotEvaluate')}}</option>
                @for ($i=$from;$i<=$to;$i++)
                @if($question->q_min == 'A')
                  <option value="{{$alphabet[$i]}}">{{$alphabet[$i]}}</option>
                @else
                  <option value="{{$i}}">{{$i}}</option>
                @endif
                @endfor
              </select>
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
