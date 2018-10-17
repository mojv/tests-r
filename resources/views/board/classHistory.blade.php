@extends('layouts.dashboard')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">

        <div class="row x_title">
          <div class="col-md-6">
            <h3>{{ __('messages.responsesList') }}<small></small></h3>
          </div>
        </div>

        <div id="bar-chart" style="height:600px;"></div>

        <div class="clearfix"></div>
      </div>
    </div>
  </div>

  <div class="page-title">
    <div class="title_left">
      <h3>{{ __('messages.studentsGrades') }}</h3>
    </div>

    <div class="title_right">
      <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
        <form action="{{route('classHistory', ['class' => $id])}}" method="get">
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
          <a href="{{route('downloadClassHistory', ['class' => $id])}}"><button type="button" class="btn btn-success btn-sm" class="btn btn-primary" data-toggle="modal" data-target=".createClass-modal">{{ __('messages.donwloadCSV') }}</button></a>
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
                <th>{{ __('messages.eMailAddress') }}
                @foreach ($tests as $test)
                 <th>{{ $test->name}} {{ __('messages.weight') }}:{{ $test->test_weight}}</th>
                @endforeach
                <th>{{ __('messages.finalGrade') }}</th>
                <th>Hist</th>
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
                <?php $final=0; $points=0; $temp =0; $grades2=[];?>
                @foreach ($tests as $test)
                  @foreach ($student->grades as $grade)
                    @if ($grade->test_id == $test->id)
                      <td align="center">{{$grade->grade}}%</td>
                      <?php
                        $temp =1;
                        array_push($grades2, $grade->grade);
                        $final=$final+($grade->grade*$test->test_weight);
                        $points=$points+$test->test_weight;
                        break;
                      ?>
                    @else
                      <?php $temp =0; ?>
                    @endif
                  @endforeach
                  @if ($temp==0)
                    <td align="center">0%</td>
                    <?php
                      if(isset($grade)){
                        array_push($grades2, 0);
                        $final=$final+(0*$test->test_weight);
                        $points=$points+$test->test_weight;
                      }
                    ?>
                  @endif
                @endforeach
                <?php
                  if(isset($grade)){
                    $final=round($final/$points,2);
                  }
                ?>
                <td>{{$final}}%</td>
                <td> <a onclick='setTimeout(function(){ setLine(<?php echo json_encode($grades2) ?>); }, 300)' class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-area-chart"></i></a></td>
                <td></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Large modal -->

     <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-lg">
         <div class="modal-content">

           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
             </button>
             <h4 class="modal-title" id="myModalLabel">Modal title</h4>
           </div>
           <div class="modal-body">
              <div id="line-chart" style="height:600px;"></div>
           </div>
         </div>
       </div>
     </div>

     <!-- /modals -->
@endsection


@section('script')

<!-- FastClick -->
<script src="{{ asset('vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- ECharts -->
<script src="{{ asset('vendors/echarts/dist/echarts.min.js') }}"></script>
<script src="{{ asset('vendors/echarts/map/js/world.js') }}"></script>

<script>

var echartBar = echarts.init(document.getElementById('bar-chart'));

var dataq = echarts.dataTool.prepareBoxplotData([
  @foreach($dataq as $dat)
    <?php echo json_encode($dat) ?>,
  @endforeach
]);

echartBar.setOption({
    title: [
          {
              text: 'Max: Q3 + 1.5 * IRQ \nMin: Q1 - 1.5 * IRQ',
              left: '10%',

          }
    ],
    tooltip : {
        trigger: 'axis',
    },
    legend: {
        data:['Boxplots','Averages']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataZoom : {show: true, title: "Zoom"},
            dataView : {show: true, readOnly: false, title: "Data View"},
            magicType: {show: false, type: ['line', 'bar']},
            restore : {show: true, title: "Restore"},
            saveAsImage : {show: true, title: "Save"}
        }
    },
    dataZoom : {
        show : true,
        realtime: true,
        start : 0,
        end : 100
    },
    xAxis : [
        {
            type : 'category',
            boundaryGap : true,
            axisTick: {onGap:false},
            splitLine: {show:false},
            data : <?php echo json_encode($testsNames) ?>
        }
    ],
    yAxis : [
        {
            type : 'value',
            scale:true,
            splitNumber: 5,
            boundaryGap: [0.01, 0.01]
        }
    ],
    series : [
        {
            name:'Average',
            type:'line',
            symbol: 'none',
            data:<?php echo json_encode($avgs) ?>,
            markPoint : {
                symbol: 'emptyPin',
                itemStyle : {
                    normal : {
                        color:'#1e90ff',
                        label : {
                            show:true,
                            position:'top',
                            formatter: function (param) {
                                return Math.round(param.value)
                            }
                        }
                    }
                },
                data : [
                    {type : 'max', name: 'max', symbolSize:5},
                    {type : 'min', name: 'min', symbolSize:5}
                ]
            },
            markLine : {
                symbol : 'none',
                itemStyle : {
                    normal : {
                        color:'#1e90ff',
                        label : {
                            show:true,
                            formatter: function (param) {
                                return Math.round(param.value)
                            }
                        }
                    }
                },
                data : [
                    {type : 'average', name: 'avg'}
                ]
            }
        },
        {
            name: 'boxplot',
            type: 'boxplot',
            data: dataq.boxData,
            tooltip: {
                formatter: function (param) {
                    return [
                        'Experiment ' + param.name + ': ',
                        'Min: ' + param.data[4],
                        'Q3: ' + param.data[3],
                        'median: ' + param.data[2],
                        'Q1: ' + param.data[1],
                        'Max: ' + param.data[0]
                    ].join('<br/>')
                }
            }
        },
        {
            name: 'outlier',
            type: 'scatter',
            data: dataq.outliers
        }
    ]
});

function setLine(value){
  var line = echarts.init(document.getElementById('line-chart'));
  line.setOption({

     tooltip : {
         trigger: 'axis'
     },
     legend: {
         data:['Student','Average']
     },
     toolbox: {
         show : true,
         feature : {
             mark : {show: true},
             dataView : {show: true, readOnly: false, title: "Data View"},
             magicType : {show: true, type: ['line', 'bar'], title: {line: 'Line',bar: 'Bar'}},
             restore : {show: true, title: "Restore"},
             saveAsImage : {show: true, title: "Save"}
         }
     },
     calculable : true,
     xAxis : [
         {
             type : 'category',
             boundaryGap : false,
             data : <?php echo json_encode($testsNames) ?>
         }
     ],
     yAxis : [
         {
             type : 'value',
             axisLabel : {
                 formatter: '{value}%'
             }
         }
     ],
     series : [
         {
             name:'Student',
             type:'line',
             data:value,
         },
         {
             name:'Average',
             type:'line',
             data:<?php echo json_encode($avgs) ?>,
         }
     ]
  });
}

</script>

@endsection
