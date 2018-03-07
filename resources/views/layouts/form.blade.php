@extends('layouts.dashboard')

@section('style')

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

@endsection

@section('content')

    <div class="row">
        @yield('nameModal')
        @yield('fileInput')
        <div class="col-lg-12" style="position: fixed" alaign="center">
        <div class="x_content" hidden id="commands">
          <div class="btn-group">
              <a id="omr-sq" class="btn btn-app">
              <i class="fa fa-check-square-o"></i> {{ __('messages.OMRSquare') }}
            </a>
            <a id="omr-sc" class="btn btn-app">
              <i class="fa fa-check-circle-o"></i> {{ __('messages.OMRCircle') }}
            </a>
            <a id="ocr" class="btn btn-app">
              <i class="fa fa-font"></i> {{ __('messages.OCR') }}
            </a>
            <a id="bcr" class="btn btn-app">
                <span class="glyphicon glyphicon-qrcode"></span> {{ __('messages.QRCode') }}
            </a>
            <a id="img"  class="btn btn-app">
              <i class="fa fa-file-image-o"></i> {{ __('messages.img') }}
            </a>
            <a id="delete"  class="btn btn-app">
              <i class="fa fa-trash"></i> {{ __('messages.deleteField') }}
            </a>
            <a id="save"  class="btn btn-app">
              <i class="fa fa-save"></i> {{ __('messages.saveForm') }}
            </a>
            <a id="threshold"  class="btn btn-app">
              <i class="fa fa-sliders"></i> {{ __('messages.threshold') }}
            </a>
          </div>
        </div>
        <div class="x_content row" hidden id="cancel">
          <div class="col-lg-1">
            <div class="btn-group">
              <a id="cancel"  class="btn btn-app cancel">
                <i class="fa fa-close"></i> {{ __('messages.exit') }}
              </a>
              <a id="readcorners"  class="btn btn-app" hidden>
                <i class="fa fa-retweet"></i> {{ __('messages.readcorners') }}
              </a>
            </div>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2 form-group has-feedback" align="center" id="markwidth" hidden>
            <input type="number" min="0" class="form-control has-feedback-right" id="width">
            <span class="fa fa-arrows-h form-control-feedback right" aria-hidden="true"></span>
            <label for="ex3">{{ __('messages.markWidth') }}</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2 form-group has-feedback" align="center" id="markheight" hidden>
            <input type="number" min="0" class="form-control has-feedback-right" id="height">
            <span class="fa fa-arrows-v form-control-feedback right" aria-hidden="true"></span>
            <label for="ex3">{{ __('messages.markHeight') }}</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2 form-group has-feedback" align="center" id="markradius" hidden>
            <input type="number" min="0" class="form-control has-feedback-right" id="radius">
            <span class="fa fa-arrow-circle-o-up form-control-feedback right" aria-hidden="true"></span>
            <label for="ex3">{{ __('messages.markRadius') }}</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2 form-group has-feedback" align="center" id="thresholdbar" hidden>
            <input type="range" min="1" max="255" step="1" value="128" id="thresholdinput">
            <label for="ex3" id="inputThresholdValue"></label>
          </div>
        </div>
        </div>
        <div class="modal fade bs-example-modal-bg" tabindex="-1" role="dialog" aria-hidden="true"  id="myModal2">
          <div class="modal-dialog modal-bg">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel2">{{ __('messages.settings') }}</h4>
                </div>
                <form id="modalok" action="javascript:void(0);">
                    <div class="modal-body">
                      <div class="row">
                        <input type="hidden" id="shape" >
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.fieldName') }}</p>
                            <input type="text" id="field_name" class="form-control" name="field_name" required>
                        </div>
                        <div class="form-group col-lg-6" id="id_field_div">
                            <p>{{ __('messages.idField') }}</p>
                            <select id="idField" class="form-control" name="idField">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.fieldOrientation') }}</p>
                            <select id="field_orientation" class="form-control" name="field_orientation">
                                <option value="1">{{ __('messages.horizontal') }}</option>
                                <option value="2">{{ __('messages.vertical') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.output') }}</p>
                            <select id="output" class="form-control" name="output">
                                <option value="1">A - Z</option>
                                <option value="2">0, 1, 2 ...</option>
                                <option value="3">1, 2, 3 ...</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.numberOfRows') }}</p>
                            <input type="number" id="rows" class="form-control" name="rows">
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.numberOfColumns') }}</p>
                            <input type="number" id="columns" class="form-control" name="columns">
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.allowMultiMarks') }}</p>
                            <select id="multiMark" class="form-control" name="multiMark">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.concatenate') }}</p>
                            <select id="concatenate" class="form-control" name="concatenate">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                      </div>
                    </div>
                <div class="modal-footer">
                  <button id="cancelModal" type="button" class="btn btn-default cancel">{{ __('messages.cancel') }}</button>
                  <button type="submit" class="btn btn-primary">{{ __('messages.createField') }}</button>
                </div>
                </form>
            </div>
          </div>
        </div>
        <div class="modal fade bs-example-modal-bg" tabindex="-1" role="dialog" aria-hidden="true"  id="myModal3">
          <div class="modal-dialog modal-bg">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel3">{{ __('messages.settings') }}</h4>
                </div>
                <form id="modalok" action="javascript:updateField();" >
                    <div class="modal-body">
                      <div class="row">
                        <input type="hidden" id="shape" >
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.fieldName') }}</p>
                            <input type="text" id="field_name_up" class="form-control" required>
                        </div>
                        <div class="form-group col-lg-6" id="id_field_div_up">
                            <p>{{ __('messages.idField') }}</p>
                            <select id="idField_up" class="form-control">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.allowMultiMarks') }}</p>
                            <select id="multiMark_up" class="form-control">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.output') }}</p>
                            <select id="output_up" class="form-control">
                                <option value="1">A - Z</option>
                                <option value="2">0, 1, 2 ...</option>
                                <option value="3">1, 2, 3 ...</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <p>{{ __('messages.concatenate') }}</p>
                            <select id="concatenate_up" class="form-control">
                                <option value="0">{{ __('messages.no') }}</option>
                                <option value="1">{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" id="old_name"/>
                    <input type="hidden" id="old_output"/>
                <div class="modal-footer">
                  <button id="cancelModal" type="button" class="btn btn-default cancel" data-dismiss="modal" aria-label="Close">{{ __('messages.cancel') }}</button>
                  <button type="submit" class="btn btn-primary">{{ __('messages.updateField') }}</button>
                </div>
                </form>
            </div>
          </div>
        </div>
        <canvas id="canvas"></canvas>
        <div class="modal" id="loading">
                <br><br><br><br><br><br><br><br><br>
                <div class="circle"></div>
                <div class="circle1"></div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/dtables.js') }}?t={{rand(10000, 99999)}}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.1/processing-api.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/pdfjs/build/pdf.js') }}"></script>

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
        $('.cancel').click(function(){
            $('#myModal2').modal('hide');
            $("#rows").val("");
            $("#columns").val("");
            $("#field_name").val("");
            $("#field_orientation").val("");
            $("#multiMark").val("");
            $('#field_orientation').prop('disabled', false);
            $('#rows').prop('disabled', false);
            $('#columns').prop('disabled', false);
            $('#multiMark').prop('disabled', false);
            $('#output').prop('disabled', false);
            $('#concatenate').prop('disabled', false);
            $("#id_field_div").hide();
            $('#commands').show();
            boxes[2].x=10000;
            boxes[3].y=10000;
            invalidate();
            $('#cancel').hide();
            $("#markwidth").hide();
            $("#markheight").hide();
            $("#markradius").hide();
            $("#readcorners").hide();
            $("#thresholdbar").hide();
        });
        $('#modalok').submit(function(){;
            $('#myModal2').modal('hide');
        });
        $('#omr-sq').click(function(){
            $("#id_field_div").show();
            $('#shape').val('1');
            $('#myModal2').modal('show');
            $('#commands').hide();
            $('#cancel').show();
            $("#markwidth").show();
            $("#markheight").show();

        });
        $('#omr-sc').click(function(){
            $("#id_field_div").show();
            $('#shape').val('2');
            $('#myModal2').modal('show');
            $('#commands').hide();
            $('#cancel').show();
            $("#markradius").show();
        });
        $('#ocr').click(function(){
            $('#shape').val('4');
            $('#myModal2').modal('show');
            $('#field_orientation').prop('disabled', true);
            $('#rows').prop('disabled', true);
            $('#columns').prop('disabled', true);
            $('#multiMark').prop('disabled', true);
            $('#output').prop('disabled', true);
            $('#concatenate').prop('disabled', true);
            $('#commands').hide();
            $('#cancel').show();
            $("#id_field_div").hide();
        });
        $('#bcr').click(function(){
            $("#id_field_div").show();
            $('#shape').val('5');
            $('#myModal2').modal('show');
            $('#commands').hide();
            $('#field_orientation').prop('disabled', true);
            $('#rows').prop('disabled', true);
            $('#columns').prop('disabled', true);
            $('#multiMark').prop('disabled', true);
            $('#output').prop('disabled', true);
            $('#concatenate').prop('disabled', true);
            $('#commands').hide();
            $('#cancel').show();
        });
        $('#img').click(function(){
            $('#shape').val('3');
            $('#myModal2').modal('show');
            $('#commands').hide();
            $('#field_orientation').prop('disabled', true);
            $('#rows').prop('disabled', true);
            $('#columns').prop('disabled', true);
            $('#multiMark').prop('disabled', true);
            $('#output').prop('disabled', true);
            $('#concatenate').prop('disabled', true);
            $('#commands').hide();
            $("#id_field_div").hide();
            $('#cancel').show();
        });
        $('#delete').click(function(){
            $('#shape').val('6');
            $('#commands').hide();
            $('#cancel').show();
        });
        $('#width').change(function(){
            width = $('#width').val();
            boxes[2].w = width;
            boxes[3].w = width;
            invalidate();
        });
        $('#height').change(function(){
            height = $('#height').val();
            boxes[2].h = height;
            boxes[3].h = height;
            invalidate();
        });
        $('#radius').change(function(){
            radius = $('#radius').val();
            boxes[0].r = radius;
            boxes[1].r = radius;
            invalidate();
        });
        $('#threshold').click(function(){
            $('#commands').hide();
            $('#cancel').show();
            $("#readcorners").show();
            $("#thresholdbar").show();
        });
        $('#thresholdinput').change(function(){
            tempCanvas = document.createElement("canvas")
            tempCanvas.width = img.width;
            tempCanvas.height = img.height;
            tempCtx = tempCanvas.getContext("2d");
            tempCtx.clearRect(0, 0, tempCanvas.width, tempCanvas.height);
            tempCtx.drawImage(img, 0, 0, img.width, img.height);
            tobwimg=tempCtx.getImageData(0,0,tempCanvas.width, tempCanvas.height);
            threshold=$('#thresholdinput').val();
            $('#inputThresholdValue').html(threshold);
            toblackWhite(tobwimg, ctx);
        });
        $('#readcorners').click(function(){
          set_sheet_corners();
          $('#cancel').hide();
        });
        $('#inputThresholdValue').html(threshold);
        $('#thresholdinput').val(threshold);
        $("#readcorners").hide();
        $("#thresholdbar").hide();
        var input = document.getElementById('file_input');
        input.addEventListener('change', handleFiles);
        PDFJS.disableWorker = true;
        var pdf = document.getElementById('pdf');
        pdf.addEventListener('change', pdftocanvas);
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
    </script>

    @yield('fscript')

@endsection
