@extends('layouts.form')


@section('fileInput')
    <div class="x_content" id="file_upload">
        {{ __('messages.imageFile') }}<input id="file_input" type='file' />
        {{ __('messages.PDFFile') }}<input id='pdf' type='file'/>
    </div>
@endsection

@section('fscript')
    <script>
        $(window).load(function(){
            $('#myModal').modal('show');
        });
        var temp_boxes = [];

        function init() {
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

            // make draw() fire every INTERVAL milliseconds
            setInterval(draw, INTERVAL);
            boxes=[];
            temp_boxes=[];
            addCircle(10000, 10000, radius, '#256b2d','temp1','temp','temp');
            addCircle(10000, 10000, radius, '#256b2d','temp2','temp','temp');
            addRect(10000, 10000, width, height, '#256b2d','temp3','temp','temp',1);
            addRect(10000, 10000, width, height, '#256b2d','temp4','temp','temp',1);

            <?php foreach ($formcoords as $box){
                  echo "addTempRect(" . $box['x'] . ", " . $box['y'] . ", " . $box['w'] . ", " . $box['h'] .  ", " . $box['r'] . ", '#" . $box['fill'] . "', '"  . $box['field_name'] .  "', " . $box['q_id'] . ", '"  . $box['q_option'] . "', "  . $box['shape'] . ", '" . $box['multiMark'] . "', '"  . $box['idField'] . "');";
            } ?>

            for (var tb = 0; tb < temp_boxes.length; tb++){
                if (temp_boxes[tb].shape=="2"){
                    addCircle((temp_boxes[tb].x*dx)+esq[0], (temp_boxes[tb].y*dy)+esq[1], temp_boxes[tb].r*dx, temp_boxes[tb].fill,temp_boxes[tb].field_name,temp_boxes[tb].q_id,temp_boxes[tb].q_option,temp_boxes[tb].shape,temp_boxes[tb].multiMark,temp_boxes[tb].idField);
                }else{
                    addRect((temp_boxes[tb].x*dx)+esq[0], (temp_boxes[tb].y*dy)+esq[1], temp_boxes[tb].w*dx,temp_boxes[tb].h*dy,temp_boxes[tb].fill,temp_boxes[tb].field_name,temp_boxes[tb].q_id,temp_boxes[tb].q_option,temp_boxes[tb].shape,temp_boxes[tb].multiMark,temp_boxes[tb].idField);
                }
            }
            // set our events. Up and down are for dragging,
            // double click is for making new boxes
            canvas.onmousedown = myDown;
            canvas.onmouseup = myUp;
            canvas.ondblclick = myDblClick;
        }
    </script>
@endsection
