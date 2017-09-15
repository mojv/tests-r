@extends('layouts.dashboard')

@section('content')

  <div class="row tile_count" align="center">
    <a href="{{route('pendingEvaluation' , ['test'=>$test->id])}}"><div class="col-md-2 col-sm-4 col-xs-4 tile_stats_count">
      <span class="count_top"><i class="fa fa-users"></i> {{ __('messages.pendingEvaluation') }}</span>
      <div class="count">@if(empty($enrolls)) - @else {{ $enrolls }} @endif</div>
    </div></a>
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
              <li><a data-toggle="modal" data-target=".bs-example-modal-sm">{{ __('messages.testSettings') }}</a>
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
              <td>{{str_replace(";","-",$result->omr_responses)}}</td>
              <td>{{str_replace(";","-",$result->img_responses)}}</td>
              <td>{{$result->grade}}%</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="clearfix"></div>
      </div>
    </div>

    <div class="col-md-12">
      <br>
      <div class="x_panel">
        <div class="x_title">
          <h2>{{ __('messages.statisticalResume') }}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" >
          <div class="col-md-9 col-sm-12 col-xs-12">
            <div class="demo-container" style="height:280px">
              <div class="x_content">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active setChart"><a href="#tab_content1" id="pie-tab" role="tab" data-toggle="tab" aria-expanded="true">{{ __('messages.pieChart') }}</a>
                    </li>
                    <li role="presentation" class="setChart"><a href="#tab_content2" role="tab" id="bar-tab" data-toggle="tab" aria-expanded="false">{{ __('messages.barChart') }}</a>
                    </li>
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                      <div id="pie-chart" style="height:600px;"></div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                      <div class="row">
                      <div class="form-group col-lg-2" id="partitions_div">
                        <input type="number" class="form-control" placeholder="Partitions" id="partitions" value="9" onchange="setAverageChart()">
                      </div>
                      </div>
                      <div id="bar-chart" style="height:600px;"></div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-12 col-xs-12" >
            <div class="pre-scrollable" style="max-height: 650px; height:650px;">
              <div class="x_title">
                <h2>{{__('messages.questionsList')}}</h2>
                <div class="clearfix"></div>
              </div>
              <ul class="list-unstyled top_profiles scroll-view">
                <li class="media event">
                  <div class="media-body">
                    <a class="title" onclick="setTimeout(function(){ questionSts('average'); }, 300);">{{ __('messages.averageScore') }}</a>
                  </div>
                </li>
                @foreach ($questions as $question)
                <li class="media event">
                  <div class="media-body">
                    <a class="title" onclick="setTimeout(function(){ questionSts({{$question->id}}); }, 300);">{{__('messages.fieldName')}}: {{$question->field_name}} -@if($question->shape == 3) ({{  __('messages.image')}}) @else  {{  __('messages.questionNumber')}}: <strong>{{$question->q_id}} </strong> @endif </a>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Small modal -->
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-sm">
         <div class="modal-content">
           <form action="{{route('updateTest', ['test'=>$test->id])}}" method="POST">
             {{ csrf_field() }}
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
               </button>
               <h4 class="modal-title" id="myModalLabel2">{{ __('messages.testSettings') }}</h4>
             </div>
             <div class="modal-body">
               <div class="row">
                 <div class="form-group col-lg-12">
                     <p>{{ __('messages.testName') }} *</p>
                     <input type="text" class="form-control" name="name" id="test_name" value="{{$test->name}}" required>
                 </div>
                 <div class="form-group col-lg-12">
                     <p>{{ __('messages.testWeight') }}</p>
                     <input type="number" min="1" max="1000" step="1" class="form-control" name="test_weight" id="test_weight" value="{{$test->test_weight}}" required>
                 </div>
                 <div class="form-group col-lg-12">
                     <p>{{ __('messages.formName') }}</p>
                     {{ Form::select('form_id', $forms, $test->form_id, ['class' => 'form-control', 'id' => 'test_form_id'])}}
                 </div>
               </div>
             </div>
             <div class="modal-footer">
               <a href="{{route('deleteTest', ['test'=>$test->id])}}" onclick="return confirm('{{ __('messages.deleteTestWarning') }}')"><button type="button" class="btn btn-danger">{{ __('messages.deleteTest') }}</button></a>
               <button type="submit" class="btn btn-primary">{{ __('messages.updateTest') }}</button>
             </div>
           </form>
         </div>
       </div>
     </div>
     <!-- /modals -->
  </div>
  <script>
    var titles = '{{ $test->titles}}';
    var titles = titles.split(";");
    var weights = '{{ $test->answers_weight}}';
    var weights = weights.split(";");
    var answers = '{{ $test->answers}}';
    var answers = answers.split(";");
    var grades = <?php echo json_encode($grades) ?>;
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
$("#bar-tab").attr("onclick","setTimeout(function(){ setAverageChart(); }, 300);");
$( "#bar-tab").trigger("click");
<?php $j=0;?>
@foreach ($questions as $question)
  <?php
     if (isset($omr_answers[$j])){
       $options=array_unique($omr_answers[$j]);
       sort($options);
       $list = "'".implode("','", $options)."'";
       $frequencies=array_count_values($omr_answers[$j]);
     }else {
       $list = "";
       $options="";
       $frequencies="";
     }
     $title=$question->field_name.'-'.$question->q_id; $data=[];
  ?>
  function questionSts(value){
    if(value=='average'){
      $("#partitions_div").show();
      $("#bar-tab").attr("onclick","setTimeout(function(){ setAverageChart(); }, 300);");
      $( "#bar-tab").trigger("click");
    }else{
      $("#partitions_div").hide();
      $("#bar-tab").attr("onclick","setTimeout(function(){ setChart"+ value +"b(); }, 300);");
      $("#pie-tab").attr("onclick","setTimeout(function(){ setChart"+ value +"a(); }, 300);");
      $( "#pie-tab").trigger("click");
    }
  }

 function setAverageChart(){
   partitions=$("#partitions").val();
   max=Math.max.apply(null, grades)+1;
   min=Math.min.apply(null, grades)-1;
   step=(max-min)/partitions;
   totals=[];
   labels=[];
   for (i=0;i<partitions;i++){
      start=min+(step*i);
      end=min+(step*(i+1));
      temp=grades.filter(function(x) {
          return x >= start && x < end;
      });
      totals.push(temp.length);
      labels.push(Math.round(start*10)/10 + "-" + Math.round(end*10)/10);
   }

   var echartBar = echarts.init(document.getElementById('bar-chart'));

   echartBar.setOption({

     tooltip : {
          trigger: 'axis'
      },
      calculable : true,
      legend: {
          data:['Frequencies','shape']
      },
      xAxis : [
          {
              type : 'category',
              data : labels
          }
      ],
      yAxis : [
          {
              type : 'value',
              name : 'frequencies',
              axisLabel : {
                  formatter: '{value} ml'
              }
          },
          {
              type : 'value',
              name : 'grade',
              axisLabel : {
                  formatter: '{value} °C'
              }
          }
      ],
      series : [

          {
              name:'Totals',
              type:'bar',
              data:totals
          },
          {
              name:'Distribution shape',
              type:'line',
              yAxisIndex: 1,
              data:totals
          }
      ]
   });
 }

 function setChart{{$question->id}}a(){
    var pie{{$question->id}} = echarts.init(document.getElementById('pie-chart'));
    var option = {
    tooltip: { trigger: 'item', formatter: "{a} <br/>{b} : {c} ({d}%)"   },
    legend: {x: 'center', y: 'bottom', data: [<?php echo $list; ?>] },
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
        @if($options!="")
          @foreach ($options as $option)
            {value: {{$frequencies[$option]}}, name: '{{$option}}'},
          @endforeach
        @endif
      ]}]};
    pie{{$question->id}}.setOption(option);
  }
  function setChart{{$question->id}}b(){
    var bar{{$question->id}} = echarts.init(document.getElementById('bar-chart'));
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
      yAxis: [{type: 'category', data:  [<?php echo $list; ?>] }],
      series: [{ name: 'Bar', type: 'bar',
      <?php $values=[]; ?>
      @if($options!="")
        @foreach ($options as $option)
          <?php array_unshift($values, $frequencies[$option]);?>
        @endforeach
      @endif
      data: [<?php echo "'".implode("','", array_reverse ($values))."'" ?>]
      },]
    };
    bar{{$question->id}}.setOption(option);
  }

<?php $j++; ?>
@endforeach
</script>
@endsection
