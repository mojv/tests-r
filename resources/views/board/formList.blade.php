@extends('layouts.dashboard')

@section('style')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection


@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{ __('messages.yourForms') }}</h2>
            <div class="clearfix"></div>
          </div>        
          <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>{{ __('messages.formName') }}</th>
                  <th>{{ __('messages.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($forms as $form)
                <tr>
                  <td width="80%">{{ $form->form_name }}</td>
                  <td align="right"><a href="{{ route('readForm', $form->id) }}"><button  type="button" class="btn btn-success btn-xs" value="{{ $form->id }}" >{{ __('messages.readForms') }}</button></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{ __('messages.formsSharedWithYou') }}</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="datatable2" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>{{ __('messages.formName') }}</th>
                  <th>{{ __('messages.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($shareForms as $shareForm)
                <tr>
                  <td align="center">{{ $shareForm->forms->form_name }}</td>
                  <td align="center"><a href="{{ route('readSharedForm', $shareForm->forms->id) }}"><button  type="button" class="btn btn-success btn-xs" value="{{ $shareForm->forms->id }}" >{{ __('messages.readForms') }}</button></a><a href="{{ asset('storage') }}/{{ $shareForm->forms->formfile }}" download=""><button  type="button" class="btn btn-success btn-xs" value="{{ $shareForm->forms->id }}" >{{ __('messages.download') }}</button></a><a href="{{ route('stopShareForm', $shareForm->forms->id) }}"><button  type="button" class="btn btn-warning btn-xs" value="{{ $shareForm->forms->id }}" >{{ __('messages.stopSharing') }}</button></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>    
    <!-- modal -->

    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="editNameModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form role="form" method="POST" action="{{ route('editName') }}">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel2">{{ __('messages.changeName') }}</h4>
                </div>
                <div class="modal-body">
                  <p>{{ __('messages.WriteFormNewName') }}</p>
                  <div class="form-group{{ $errors->has('form_name') ? ' has-error' : '' }}">
                      <input type="hidden" name="form_id" id="form_id">
                      <input id="form_name" type="text" class="form-control" name="form_name" value="{{ old('form_name') }}" autofocus placeholder="{{ __('messages.formName') }}" required>
                        @if ($errors->has('form_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('form_name') }}</strong>
                            </span>
                        @endif
                   </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">{{ __('messages.cancel') }}</button>
                  <button type="submit" class="btn btn-primary">{{ __('messages.changeName') }}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    <!-- /modals -->
    <script>
        function editName(value){
            $("#editNameModal").modal("show");
            $("#form_id").val(value);
        }
    </script>


@endsection


@section('script1')
    <!-- Datatables -->
     <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
     <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
     <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
     <script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
     <script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
     <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
     <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
     <script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
     <script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
     <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
     <script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
     <script src="vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
     <script src="vendors/jszip/dist/jszip.min.js"></script>
     <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
     <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
@endsection

@section('script2')
    <!-- Datatables -->
    <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
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
        $('#datatable2').dataTable();
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
    </script>
    <!-- /Datatables -->
@endsection
