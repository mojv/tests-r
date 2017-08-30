@extends('layouts.dashboard')

@section('content')

  <div class="row tile_count" align="center">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-users"></i> {{ __('messages.numberStudents') }}</span>
      <div class="count">@if(empty($enrolls)) - @else {{ $enrolls }} @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-thumbs-up"></i> {{ __('messages.studentsEvaluated') }}</span>
      <div class="count green">@if(empty($results2)) - @else {{ $results2 }} @endif</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-area-chart"></i> {{ __('messages.averageScore') }} </span>
      <div class="count">@if(empty($results3)) - @else {{ $results3 }}% @endif</div>
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
          <div class="btn-group">
            <button type="button" class="btn btn-success">{{ __('messages.action') }}</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="{{route('downloadResults', ['test'=>$test->id])}}" target="_blank">{{ __('messages.downloadResults') }}</a>
              </li>
              <li><a href="{{route('deleteResults', ['test'=>$test->id])}}">{{ __('messages.deleteResults') }}</a>
              </li>
              <li class="divider"></li>
              @if($test->status==0)
              <li><a href="{{route('closeTest', ['test'=>$test->id, 'action'=>1])}}">{{ __('messages.closeTest') }}</a>
              @else
              <li><a href="{{route('closeTest', ['test'=>$test->id, 'action'=>0])}}">{{ __('messages.openTest') }}</a>
              @endif
              </li>
            </ul>
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

    @foreach ($questions as $question)
    <div class="col-md-4 col-sm-6 col-xs-12">
      <br>
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-bars"></i>{{__('messages.fieldName')}}: {{$question->field_name}}<small>@if($question->shape == 3) ({{  __('messages.image')}}) @else  {{  __('messages.questionNumber')}}: {{$question->q_id}} @endif</small></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
              <li role="presentation" class="active setChart"><a href="#tab_content1{{$question->id}}" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Home</a>
              </li>
              <li role="presentation" class="setChart"><a href="#tab_content2{{$question->id}}" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false" onclick="setTimeout(function(){ setChart{{$question->id}}b(); }, 300)">Profile</a>
              </li>
            </ul>
            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content1{{$question->id}}" aria-labelledby="home-tab">

                <div id="pie{{$question->id}}" style="height:350px;"></div>

              </div>
              <div role="tabpanel" class="tab-pane fade" id="tab_content2{{$question->id}}" aria-labelledby="profile-tab">

                <div id="bar{{$question->id}}" style="height:350px;"></div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    @endforeach

  </div>
  <script>
    var titles = '{{ $test->titles}}';
    var titles = titles.split(";");
    var weights = '{{ $test->answers_weight}}';
    var weights = weights.split(";");
    var answers = '{{ $test->answers}}';
    var answers = answers.split(";");
    var omr_results = [];
    var img_results = [];

  </script>
@endsection

@section('script')

<!-- FastClick -->
<script src="{{ asset('vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- ECharts -->
<script src="{{ asset('vendors/echarts/dist/echarts.js') }}"></script>
<script src="{{ asset('vendors/echarts/map/js/world.js') }}"></script>

<script>
<?php $j=0; $alphabet = range('A', 'Z'); $q_omr=count($omr_answers);?>
@foreach ($questions as $question)
  <?php
    $title=$question->field_name.'-'.$question->q_id; $j=array_search($title, $titles); $data=[];
  ?>
  @if($question->q_min == 'A')
    <?php $from=0; $to=array_search($question->q_max, $alphabet); ?>
  @elseif($question->q_min == 0)
    <?php $from=0; $to=$question->q_max; ?>
  @elseif($question->q_min == 1)
    <?php $from=1; $to=$question->q_max+1; ?>
  @endif
  @if($question->shape != 3)
    @for ($i=$from;$i<=$to;$i++)
      @if($question->q_min == 'A')
        <?php array_push($data, $alphabet[$i]); ?>
      @else
        <?php array_push($data, $i); ?>
      @endif
    @endfor
  @else
    <?php $data=["[0-4]","(4-6]","(6-8]","(8-10]"]; ?>
  @endif

 //function setChart{{$question->id}}a(){
    var pie{{$question->id}} = echarts.init(document.getElementById('pie{{$question->id}}'));
    var option = {
    tooltip: { trigger: 'item', formatter: "{a} <br/>{b} : {c} ({d}%)"   },
    legend: {x: 'center', y: 'bottom', data: [<?php echo "'".implode("','", $data)."'" ?>] },
    toolbox: { show: true,
      feature: {
        magicType: {show: true, type: ['pie', 'funnel'],
          option: {
            funnel: {x: '25%', width: '50%', funnelAlign: 'left', max: 1548 }
          }
        },
        restore: {show: false, title: "Restore"},
        saveAsImage: {show: false, title: "Save Image"}
      }
    },
    calculable: true,
    series: [{ name: 'Pie', type: 'pie',radius: '55%', center: ['50%', '48%'],
      data: [
        @if($question->shape != 3 && count($omr_answers)>0)
          <?php $temp=array_count_values($omr_answers[$j]); ?>
          @for ($i=$from;$i<=$to;$i++)
            @if($question->q_min == 'A')
              <?php if(array_key_exists($alphabet[$i], $temp)){$value = $temp[$alphabet[$i]];}else{$value = 0;} ?>
              {value: {{$value}}, name: '{{$alphabet[$i]}}'},
            @else
              <?php if(array_key_exists($i, $temp)){$value = $temp[$i];}else{$value = 0;} ?>
              {value: {{$value}}, name: '{{$i}}'},
            @endif
          @endfor
        @elseif(count($img_answers)>0)
          <?php $temp=array_count_values($img_answers[$j-$q_omr]);
            $value1=0; $value2=0; $value3=0; $value4=0;
            foreach($temp as $option => $val){
              if($option <= 4){
                $value1=$value1+$val;
              }elseif($option <= 6){
                $value2=$value2+$val;
              }elseif($option <= 8){
                $value3=$value3+$val;
              }elseif($option <= 10){
                $value4=$value4+$val;
              }
            }
          ?>
          {value: {{$value1}}, name: "[0-4]"},
          {value: {{$value2}}, name: "(4-6]"},
          {value: {{$value3}}, name: "(6-8]"},
          {value: {{$value4}}, name: "(8-10]"}
        @endif
      ]}]};
    pie{{$question->id}}.setOption(option);
  //}
  function setChart{{$question->id}}b(){
    var bar{{$question->id}} = echarts.init(document.getElementById('bar{{$question->id}}'));
    var option = {
      title: {text: 'Bar Graph',subtext: 'Graph subtitle'},
      tooltip: {trigger: 'axis'},
      legend: {x: 100,data: ['2015']},
      toolbox: {show: false,
        feature: {
        saveAsImage: {show: false, title: "Save Image"}
        }
      },
      calculable: true,
      xAxis: [{type: 'value', boundaryGap: [0, 0.01]}],
      yAxis: [{type: 'category', data:  [<?php echo "'".implode("','", array_reverse($data))."'" ?>] }],
      series: [{ name: '2015', type: 'bar',
      <?php $values=[]; ?>
      @if($question->shape != 3 && count($omr_answers)>0)
        <?php $temp=array_count_values($omr_answers[$j]); ?>
        @for ($i=$from;$i<=$to;$i++)
          @if($question->q_min == 'A')
            <?php if(array_key_exists($alphabet[$i], $temp)){array_push($values,$temp[$alphabet[$i]]);}else{array_push($values,0);} ?>
          @else
            <?php if(array_key_exists($i, $temp)){$value = $temp[$i];}else{$value = 0;} ?>
            <?php if(array_key_exists($i, $temp)){array_push($values,$temp[$i]);}else{array_push($values,0);} ?>
          @endif
        @endfor
        data: [<?php echo "'".implode("','", array_reverse ($values))."'" ?>]
      @elseif(count($img_answers)>0)
      <?php $temp=array_count_values($img_answers[$j-$q_omr]);
        $value1=0; $value2=0; $value3=0; $value4=0;
        foreach($temp as $option => $val){
          if($option <= 4){
            $value1=$value1+$val;
          }elseif($option <= 6){
            $value2=$value2+$val;
          }elseif($option <= 8){
            $value3=$value3+$val;
          }elseif($option <= 10){
            $value4=$value4+$val;
          }
        }
      ?>
      data: [{{$value1}},{{$value2}},{{$value3}},{{$value4}}]
      @endif
      },]
    };
    bar{{$question->id}}.setOption(option);
  }

<?php $j++; ?>
@endforeach
</script>
@endsection
