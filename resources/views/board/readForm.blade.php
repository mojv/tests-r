@extends('layouts.dashboard')

@section('style')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

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
<div id="results" hidden>
    <button type="button" id="settable">{{ __('messages.setTables') }}</button>
    <button type="button" id="gradeImg" class="cutting" hidden="">{{ __('messages.gradeImage') }}</button>
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ __('messages.OMR') }} <small>{{ __('messages.results') }}</small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable-buttons2" class="table table-striped table-bordered dataTable datatable-buttons">
          <thead>
            <tr id="resultsFormOmrHead">
              <th>ID</th>
            </tr>
          </thead>
          <tbody id="resultsFormOmrBody">

          </tbody>
        </table>
      </div>
    </div>
    <div class="x_panel" id='qr_table'>
      <div class="x_title">
        <h2>{{ __('messages.QRCode') }} <small>{{ __('messages.results') }}</small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable-buttons3" class="table table-striped table-bordered dataTable datatable-buttons">
          <thead>
            <tr id="resultsFormBcrHead">
            </tr>
          </thead>
          <tbody id="resultsFormBcrBody">

          </tbody>
        </table>
      </div>
    </div>
    <div class="x_panel" id='ocr_table'>
      <div class="x_title">
        <h2>{{ __('messages.OCR') }} <small>{{ __('messages.results') }}</small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable-buttons1" class="table table-striped table-bordered dataTable datatable-buttons">
          <thead>
            <tr id="resultsFormOcrHead">
            </tr>
          </thead>
          <tbody id="resultsFormOcrBody">

          </tbody>
        </table>
      </div>
    </div>
    <div class="x_panel cutting" id='img_table' hidden>
      <div class="x_title">
        <h2>{{ __('messages.img') }} <small>{{ __('messages.results') }}</small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable-buttons4" class="table table-striped table-bordered dataTable datatable-buttons">
          <thead>
            <tr id="resultsFormImgHead">
            </tr>
          </thead>
          <tbody id="resultsFormImgBody">

          </tbody>
        </table>
      </div>
    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"  id="modal_cuttings" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:gradeImage2();">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel2">{{ __('messages.img') }}</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                      <div align="center"><img id="temp_img" style="max-width: 800px;"></div>
                      <input id="gradeImage" type="text" class="form-control" name="gradeImage" autofocus placeholder="{{ __('messages.gradeImage') }}" required>
                   </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">{{ __('messages.gradeImage') }}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
<div id="files_input">
    {{ __('messages.imagesFiles') }}<input type="file" id="files" name="files[]" multiple />
    {{ __('messages.PDFFile') }}<input id='pdf' type='file'/>
</div>

<div id="redForm_run" hidden>
  <div class="row">
    <div class="form-group col-lg-2" align="center">
      <br>
      <a id="run" class="btn btn-app">
        <i class="fa fa-print"></i> FormRead
      </a>
    </div>
    <div class="form-group col-lg-2" align="center">
    <h2>{{ __('messages.markDarkness') }}</h2> <input type="number" min="0" max="100" id="darkness" value="30" class="form-control">
    </div>
    <div class="form-group col-lg-3" align="center">
      <h2>{{ __('messages.OCRLanguage') }}</h2> <select id="langsel" class="form-control" onchange="window.lastFile && recognizeFile(window.lastFile)">
         <option value='afr'     > Afrikaans             </option>
         <option value='ara'     > Arabic                </option>
         <option value='aze'     > Azerbaijani           </option>
         <option value='bel'     > Belarusian            </option>
         <option value='ben'     > Bengali               </option>
         <option value='bul'     > Bulgarian             </option>
         <option value='cat'     > Catalan               </option>
         <option value='ces'     > Czech                 </option>
         <option value='chi_sim' > Chinese               </option>
         <option value='chi_tra' > Traditional Chinese   </option>
         <option value='chr'     > Cherokee              </option>
         <option value='dan'     > Danish                </option>
         <option value='deu'     > German                </option>
         <option value='ell'     > Greek                 </option>
         <option value='eng'     selected> English                </option>
         <option value='enm'     > English (Old)         </option>
         <option value='meme'     > Internet Meme                </option>
         <option value='epo'     > Esperanto             </option>
         <option value='epo_alt' > Esperanto alternative </option>
         <option value='equ'     > Math                  </option>
         <option value='est'     > Estonian              </option>
         <option value='eus'     > Basque                </option>
         <option value='fin'     > Finnish               </option>
         <option value='fra'     > French                </option>
         <option value='frk'     > Frankish              </option>
         <option value='frm'     > French (Old)          </option>
         <option value='glg'     > Galician              </option>
         <option value='grc'     > Ancient Greek         </option>
         <option value='heb'     > Hebrew                </option>
         <option value='hin'     > Hindi                 </option>
         <option value='hrv'     > Croatian              </option>
         <option value='hun'     > Hungarian             </option>
         <option value='ind'     > Indonesian            </option>
         <option value='isl'     > Icelandic             </option>
         <option value='ita'     > Italian               </option>
         <option value='ita_old' > Italian (Old)         </option>
         <option value='jpn'     > Japanese              </option>
         <option value='kan'     > Kannada               </option>
         <option value='kor'     > Korean                </option>
         <option value='lav'     > Latvian               </option>
         <option value='lit'     > Lithuanian            </option>
         <option value='mal'     > Malayalam             </option>
         <option value='mkd'     > Macedonian            </option>
         <option value='mlt'     > Maltese               </option>
         <option value='msa'     > Malay                 </option>
         <option value='nld'     > Dutch                 </option>
         <option value='nor'     > Norwegian             </option>
         <option value='pol'     > Polish                </option>
         <option value='por'     > Portuguese            </option>
         <option value='ron'     > Romanian              </option>
         <option value='rus'     > Russian               </option>
         <option value='slk'     > Slovakian             </option>
         <option value='slv'     > Slovenian             </option>
         <option value='spa'     > Spanish               </option>
         <option value='spa_old' > Old Spanish           </option>
         <option value='sqi'     > Albanian              </option>
         <option value='srp'     > Serbian (Latin)       </option>
         <option value='swa'     > Swahili               </option>
         <option value='swe'     > Swedish               </option>
         <option value='tam'     > Tamil                 </option>
         <option value='tel'     > Telugu                </option>
         <option value='tgl'     > Tagalog               </option>
         <option value='tha'     > Thai                  </option>
         <option value='tur'     > Turkish               </option>
         <option value='ukr'     > Ukrainian             </option>
         <option value='vie'     > Vietnamese            </option>
      </select>
    </div>
    <div class="form-group col-lg-3" align="center">
      <h2>{{ __('messages.threshold') }} <a id="see_threshold"><span class="fa fa-eye"></span></a><a id="hide_threshold" hidden><span class="fa fa-eye-slash" hidden></span></a></h2><input type="range" min="1" max="255" id="threshold" value="147" class="form-control"><span id="thresholdValue"></span>
    </div>
  </div>
</div>

<canvas id="threshold_canvas" width="300" height="300" style="z-index:1" hidden></canvas>

<div id="preview">
    <output id="list"></output><br>
    <output id="list2"></output><br>
    <div id="status"></div>
</div>
<p id="demo"></p>

        <div class="modal" id="loading">
                <br><br><br><br><br><br><br><br><br>
                <div class="circle"></div>
                <div class="circle1"></div>
        </div>

<script type="text/javascript" src="{{ asset('js/dtables.js') }}"></script>
<script src="{{ asset('js/qcode-decoder.min.js') }}"></script>
<script src="https://cdn.rawgit.com/naptha/tesseract.js/1.0.7/dist/tesseract.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.1/processing-api.min.js"></script>
<script type="text/javascript" src="https://rawgithub.com/mozilla/pdf.js/gh-pages/build/pdf.js"></script>
<script>
    $body = $("body");
    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
         ajaxStop: function() { $body.removeClass("loading"); }
    });
    /*$(function () {
        $('.modal').modal({
            show: false,
            keyboard: false,
            backdrop: 'static'
        });
    });*/
      // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
    } else {
      alert('The File APIs are not fully supported in this browser.');
    }
    var ctx;
    var imageURLs=[];
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
    document.getElementById('pdf').addEventListener('change', handleFileSelectPdf);
    document.getElementById('run').addEventListener('click', read);
    var imgs=[];
    var relativeCoord = [];
    var MAX_RATIO_A4 = 0.728;
    var MIN_RATIO_A4 = 0.704;
    var MAX_RATIO_LETTER = 0.776;
    var MIN_RATIO_LETTER = 0.768;
    var threshold_canvas = document.getElementById('threshold_canvas');
    var threshold_ctx;
    var threshold_img = new Image;
    $('#thresholdValue').html(threshold);
    $('#gradeImg').click(function() {
      gradeImage();
    });
    $('#see_threshold').click(function() {
      $('#see_threshold').hide();
      $('#hide_threshold').show();
      $('#threshold_canvas').show();
    });
    $('#hide_threshold').click(function() {
      $('#see_threshold').show();
      $('#hide_threshold').hide();
      $('#threshold_canvas').hide();
    });
    $('#threshold').change(function() {
      threshold=$('#threshold').val();
      $('#thresholdValue').html(threshold);
      threshold_img = document.getElementsByName('forms')[0];
      threshold_ctx = threshold_canvas.getContext("2d");
      threshold_ctx.clearRect(0, 0, threshold_canvas.width, threshold_canvas.height);
      threshold_canvas.width = threshold_img.width;
      threshold_canvas.height = threshold_img.height;
      threshold_ctx.drawImage(threshold_img, 0, 0, threshold_img.width, threshold_img.height);
      tobwimg=threshold_ctx.getImageData(0,0,threshold_canvas.width, threshold_canvas.height);
      toblackWhite(tobwimg, threshold_ctx);
    });

    <?php foreach ($formcoords as $box) {
        echo "relativeCoord.push([" . $box['x'] . ", " . $box['y'] . ", " . $box['w'] . ", " . $box['h'] . ", " . $box['r'] . ", '"  . $box['field_name'] . "', " . $box['q_id'] . ", '" . $box['q_option'] . "', " . $box['shape'] . ", " . $box['multiMark'] . ", " . $box['idField'] . ", " . $box['concatenate'] . "]);";
    } ?>

</script>

@endsection

@section('script')
<script src="{{ asset('js/qcode-decoder.min.js') }}"></script>
<script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.7/dist/tesseract.js'></script>
<script src="{{ asset('js/dtables.js') }}"></script>
<script>
    set_tables();
</script>

@endsection

@section('script1')
    <!-- Datatables -->
     <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
     <script src="{{ asset('vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
     <script src="{{ asset('vendors/jszip/dist/jszip.min.js') }}"></script>
     <script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
     <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script>

@endsection

@section('script2')
    <!-- Datatables -->
    <script>
      $('#settable').click(function() {
        $(this).hide();
        $('#gradeImg').hide();

        var head = $('#datatable-buttons4 thead tr th');
        var body = $('#datatable-buttons4 tbody tr');
        $("#datatable-buttons2 thead tr th:first-child").after(head);
        $("#datatable-buttons2 tbody tr").each(function(i) {
          $('td:first-child', this).after(body.eq(i).children());
        });
        $('#img_table').remove();
        var head = $('#datatable-buttons1 thead tr th');
        var body = $('#datatable-buttons1 tbody tr');
        $("#datatable-buttons2 thead tr th:first-child").after(head);
        $("#datatable-buttons2 tbody tr").each(function(i) {
          $('td:first-child', this).after(body.eq(i).children());
        });
        $('#ocr_table').remove();
        var head = $('#datatable-buttons3 thead tr th');
        var body = $('#datatable-buttons3 tbody tr');
        $("#datatable-buttons2 thead tr th:first-child").after(head);
        $("#datatable-buttons2 tbody tr").each(function(i) {
          $('td:first-child', this).after(body.eq(i).children());
        });
        $('#qr_table').remove();
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons2").length) {
            $("#datatable-buttons2").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
      //$(document).ready(function() { $('.dataTable').dataTable(); });
    </script>
    <!-- /Datatables -->
@endsection
