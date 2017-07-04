@extends('layouts.form')

@section('nameModal')

    @if(empty($create))
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true"  id="myModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form role="form" method="POST" action="{{ route('checkName') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel2">{{ __('messages.formName') }}</h4>
                </div>
                <div class="modal-body">
                  <p>{{ __('messages.WriteFormName') }}</p>
                  <div class="form-group{{ $errors->has('form_name') ? ' has-error' : '' }}">
                      <input id="form_name" type="text" class="form-control" name="form_name" value="{{ old('form_name') }}" autofocus placeholder="Form Name" required>
                        @if ($errors->has('form_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('form_name') }}</strong>
                            </span>
                        @endif
                   </div>
                </div>
                <div class="modal-footer">
                  <a href="{{route('dashboard')}}"><button type="button" class="btn btn-default">{{ __('messages.cancel') }}</button></a>
                  <button type="submit" class="btn btn-primary">{{ __('messages.createForm') }}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    @endif

@endsection

@section('fileInput')
    <div class="x_content" id="file_upload">
        Image File<input id="file_input" type='file' />
        PDF File<input id='pdf' type='file'/>
    </div>
@endsection

@section('fscript')
    <script>
        $(window).load(function(){
            $('#myModal').modal('show');
        });

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

          addCircle(10000, 10000, radius, '#256b2d','temp1','temp','temp');
          addCircle(10000, 10000, radius, '#256b2d','temp2','temp','temp');
          addRect(10000, 10000, width, height, '#256b2d','temp3','temp','temp',1);
          addRect(10000, 10000, width, height, '#256b2d','temp4','temp','temp',1);

          // set our events. Up and down are for dragging,
          // double click is for making new boxes
          canvas.onmousedown = myDown;
          canvas.onmouseup = myUp;
          canvas.ondblclick = myDblClick;

        }
    </script>
@endsection
