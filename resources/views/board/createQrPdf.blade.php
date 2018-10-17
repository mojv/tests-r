@extends('layouts.dashboard')


@section('content')

    <style>

        .circle {
           background-color: rgba(0,0,0,0);
           border: 5px solid rgba(0,183,229,0.9);
           opacity: .9;
           border-right: 5px solid rgba(0,0,0,0);
           border-left: 5px solid rgba(0,0,0,0);
           border-radius: 50px;
           box-shadow: 0 0 35px #2187e7;
           width: 50px;
           height: 50px;
           margin: 0 auto;
           -moz-animation: spinPulse 1s infinite ease-in-out;
           -webkit-animation: spinPulse 1s infinite linear;
       }

       .circle1 {
           background-color: rgba(0,0,0,0);
           border: 5px solid rgba(0,183,229,0.9);
           opacity: .9;
           border-left: 5px solid rgba(0,0,0,0);
           border-right: 5px solid rgba(0,0,0,0);
           border-radius: 50px;
           box-shadow: 0 0 15px #2187e7;
           width: 30px;
           height: 30px;
           margin: 0 auto;
           position: relative;
           top: -40px;
           -moz-animation: spinoffPulse 1s infinite linear;
           -webkit-animation: spinoffPulse 1s infinite linear;
       }

       @-moz-keyframes spinPulse {
           0% {
               -moz-transform: rotate(160deg);
               opacity: 0;
               box-shadow: 0 0 1px #2187e7;
           }

           50% {
               -moz-transform: rotate(145deg);
               opacity: 1;
           }

           100% {
               -moz-transform: rotate(-320deg);
               opacity: 0;
           };
       }

       @-moz-keyframes spinoffPulse {
           0% {
               -moz-transform: rotate(0deg);
           }

           100% {
               -moz-transform: rotate(360deg);
           };
       }

       @-webkit-keyframes spinPulse {
           0% {
               -webkit-transform: rotate(160deg);
               opacity: 0;
               box-shadow: 0 0 1px #2187e7;
           }

           50% {
               -webkit-transform: rotate(145deg);
               opacity: 1;
           }

           100% {
               -webkit-transform: rotate(-320deg);
               opacity: 0;
           };
       }

       @-webkit-keyframes spinoffPulse {
           0% {
               -webkit-transform: rotate(0deg);
           }

           100% {
               -webkit-transform: rotate(360deg);
           };
       }

    </style>

    <div class="row">
        @yield('nameModal')
        <div class="x_content" id="file_upload">
            {{ __('messages.imageFile') }}<input id="file_input" type='file' accept=".png, .jpeg, .jpg" />
            {{ __('messages.PDFFile') }}<input id='pdf' type='file' accept=".pdf"/>
        </div>
        <canvas id="canvas" hidden></canvas>
        <div id="qrcode" hidden></div>
    </div>

    <div class="modal" id="loading">
            <br><br><br><br><br><br><br><br><br>
            <div class="circle"></div>
            <div class="circle1"></div>
    </div>

    <script type="text/javascript" src="{{ asset('js/dtables.js') }}?t={{rand(10000, 99999)}}"></script>
    <script type="text/javascript" src="{{ asset('js/qrcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pdfjs/build/pdf.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jsPDF.js') }}"></script>

    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
             ajaxStop: function() { $body.removeClass("loading"); }
        });
        $(function () {
            $('.modal').modal({
                show: false,
                keyboard: false,
                backdrop: 'static'
            });
        });
        //-----------aligning sheet variables-----------
        @if (!empty($form_id))
            $('#save').click(function(){
                saveform("{{ route('deleteFormcoords', $form_id) }}", "{{ route('updateFormcoords', $form_id) }}", {{$form_id}});
            });
        @endif


        var input = document.getElementById('file_input');
        input.addEventListener('change', handleFiles);
        $('#file_input').change(function(){
          $('#loading').modal('show');
        })
        PDFJS.disableWorker = true;
        var pdf = document.getElementById('pdf');
        pdf.addEventListener('change', pdftocanvas);
        $('#pdf').change(function(){
          $('#loading').modal('show');
        })
        var img = new Image;
        var degrees;
        var ctx;
        var canvas = document.getElementById('canvas');
        var sheet_corners;
        var dx = 0;
        var dy = 0;
        var esq = [];
        //-------Defining marks' possition variables-----
        var boxes = [];
        var WIDTH;
        var HEIGHT;
        var INTERVAL = 150;
        var isDrag = false;
        var mx, my;
        var canvasValid = false;
        var mySel = [];
        var mySelColor = '#CC0000';
        var mySelWidth = 2;
        var ghostcanvas;
        var gctx;
        var offsetx, offsety;
        var offsetsx = [];
        var offsetsy = [] ;
        var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;
        var shape = '';
        var width;
        var height;
        var radius;
        var area_boxes_count = 0;

        $(window).load(function(){
            $('#myModal').modal('show');
        });
        var temp_boxes = [];

        function init() {
            sheet_corners= 0;
            HEIGHT = canvas.height;
            WIDTH = canvas.width;
            ghostcanvas = document.createElement('canvas');
            ghostcanvas.height = HEIGHT;
            ghostcanvas.width = WIDTH;
            gctx = ghostcanvas.getContext('2d');

            //fixes a problem where double clicking causes text to get selected on the canvas
            canvas.onselectstart = function () { return false; }

            // fixes mouse co-ordinate problems when there's a border or padding
            // see getMouse for more detail
            if (document.defaultView && document.defaultView.getComputedStyle) {
              stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10)      || 0;
              stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)       || 0;
              styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)  || 0;
              styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)   || 0;
            }

            boxes=[];
            temp_boxes=[];
            students_id=[];
            students_name=[];

            <?php foreach ($formcoords as $box){
            echo "addTempRect(" . $box['x'] . ", " . $box['y'] . ", " . $box['w'] . ", " . $box['h'] .  ", " . $box['r'] . ", '#" . $box['fill'] . "', '"  . $box['field_name'] .  "', " . $box['q_id'] . ", '"  . $box['q_option'] . "', "  . $box['shape'] . ", '" . $box['multiMark'] . "', '"  . $box['idField'] . "', '" . $box['concatenate'] . "', '" . $box['corner'] . "');";
            } ?>

            @foreach($students as $student)
              students_id.push("{{$student->student_id}}");
              students_name.push("{{$student->name}}" + " " + "{{$student->last_name}}");
            @endforeach

            addRect((temp_boxes[0].x*dx)+esq[temp_boxes[0].corner][0], (temp_boxes[0].y*dy)+esq[temp_boxes[0].corner][1], temp_boxes[0].w*dx,temp_boxes[0].h*dy,temp_boxes[0].fill,temp_boxes[0].field_name,temp_boxes[0].q_id,temp_boxes[0].q_option,temp_boxes[0].shape,temp_boxes[0].multiMark,temp_boxes[0].idField,temp_boxes[0].concatenate);

            drawQr();

        }
    </script>

@endsection
