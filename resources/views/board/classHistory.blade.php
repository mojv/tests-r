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

@endsection


@section('script')

<!-- FastClick -->
<script src="{{ asset('vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- ECharts -->
<script src="{{ asset('vendors/echarts/dist/echarts.js') }}"></script>
<script src="{{ asset('vendors/echarts/map/js/world.js') }}"></script>

<script>
var echartBar = echarts.init(document.getElementById('bar-chart'));

echartBar.setOption({

    tooltip : {
        trigger: 'axis',
        formatter: function (params) {
            var res = params[0].name;
            for (var i = params.length - 1; i >= 0; i--) {
                if (params[i].value instanceof Array) {
                    res += '<br/>' + params[i].seriesName;
                    res += '<br/>  开盘 : ' + params[i].value[0] + '  最高 : ' + params[i].value[3];
                    res += '<br/>  收盘 : ' + params[i].value[1] + '  最低 : ' + params[i].value[2];
                }
                else {
                    res += '<br/>' + params[i].seriesName;
                    res += ' : ' + params[i].value;
                }
            }
            return res;
        }
    },
    legend: {
        data:['上证指数','成交金额(万)']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataZoom : {show: true},
            dataView : {show: true, readOnly: false},
            magicType: {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    dataZoom : {
        show : true,
        realtime: true,
        start : 50,
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
        },
        {
            type : 'value',
            scale:true,
            splitNumber: 5,
            boundaryGap: [0.05, 0.05],
            axisLabel: {
                formatter: function (v) {
                    return Math.round(v/10000) + ' 万'
                }
            }
        }
    ],
    series : [
        {
            name:'成交金额(万)',
            type:'line',
            yAxisIndex: 1,
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
                                return Math.round(param.value/10000) + ' 万'
                            }
                        }
                    }
                },
                data : [
                    {type : 'max', name: '最大值', symbolSize:5},
                    {type : 'min', name: '最小值', symbolSize:5}
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
                                return Math.round(param.value/10000) + ' 万'
                            }
                        }
                    }
                },
                data : [
                    {type : 'average', name: '平均值'}
                ]
            }
        },
        {
            name:'上证指数',
            type:'k',
            data: <?php echo json_encode($quartiles) ?>
        }
    ]
});
</script>

@endsection
