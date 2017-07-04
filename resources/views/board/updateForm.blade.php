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
                  <td width="40%" align="center">{{ $form->form_name }}</td>
                  <td align="center"><button  type="button" class="btn btn-success btn-xs" value="{{ $form->id }}" onclick="editName(this.value)">{{ __('messages.changeName') }}</button><a href="{{ route('editForm', $form->id) }}"><button  type="button" class="btn btn-success btn-xs" value="{{ $form->id }}" >{{ __('messages.editForm') }}</button></a><a href="{{ route('deleteForm', $form->id) }}"><button  type="button" class="btn btn-warning btn-xs" value="{{ $form->id }}" onclick="deleteform(this.value)">{{ __('messages.deleteForm') }}</button></a><button  type="button" class="btn btn-success btn-xs" value="{{ $form->id }}" onclick="shareForm(this.value)">{{ __('messages.shareForm') }}</button></td>
                  <input type="hidden" id="formfile_{{ $form->id }}" value="{{ $form->formfile }}" />
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

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

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="shareFormModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
            <h4 class="modal-title" id="myModalLabel2">{{ __('messages.addNewUser') }}</h4>
          </div>

          <div class="modal-body">
            <p>{{ __('messages.addNewUserIntusction') }}</p>
            <div class="input-group">
              <span class="input-group-btn">
                <button id="searchUser" type="button" class="btn btn-primary">{{ __('messages.search') }}</button>
              </span>
              <input type="text" class="form-control" id='addUser'>
            </div>  
            <div align="center" hidden="" id="userProfile"> 
              <div class="profile_details">
                <div class="well profile_view">
                  <div class="col-sm-12">
                    <h4 class="brief"><i id="userName"></i></h4>
                    <div class="left col-xs-7">
                      <h2 id="userCountry"></h2>
                      <p><strong>{{ __('messages.company') }}: </strong><i id="userCompany"></i></p>
                      <ul class="list-unstyled">
                      <li><i class="fa fa-venus-mars"></i> {{ __('messages.gender') }}: </li><p id="userGender"></p>
                      <li><i class="fa fa-envelope"></i> {{ __('messages.eMailAddress') }}: </li><p id="userEmail"></p>
                      </ul>
                    </div>
                    <div class="right col-xs-5 text-center">
                      <img id="userPhoto" src="" alt="" class="img-circle img-responsive" style="max-width: 120px; height: auto; ">
                    </div>
                  </div>
                  <div class="col-xs-12 bottom text-center">           
                    <div align="center">
                      <button type="button" class="btn btn-primary btn-xs" id="searchFormButton">
                        <i class="fa fa-share-alt"> </i> {{ __('messages.shareForm') }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <h2>{{ __('messages.shareFormList') }}</h2>
            <table id="body_shareWith2" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>{{ __('messages.name') }}</th>
                  <th>{{ __('messages.eMailAddress') }}</th>
                  <th>{{ __('messages.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($shareforms as $shareform)
                <tr class="sh_forms form_{{ $shareform->form_id }}" id="shareForm_{{$shareform->id}}">
                  <td align="center">{{ $shareform->users->name }} {{ $shareform->users->lastName }}</td>
                  <td align="center">{{ $shareform->users->email }}</td>
                  <td align="center"><button  type="button" class="btn btn-warning btn-xs" value="{{$shareform->id}}" onclick="stopSharing(this.value)">{{ __('messages.stopSharing') }}</button></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>

          </div>
          <div class="modal-footer">  
          <div align="left"><h2>{{ __('messages.updateFormFile') }}</h2> </div>                
             <form role="form" method="POST" action="{{ route('uploadFormfile') }}" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}  
                <div class="input-group">
                  <span class="input-group-btn">
                    <button id="searchUser" type="submit" class="btn btn-primary">{{ __('messages.upload') }}</button>
                  </span>
                  <input type="file" class="form-control" id='addUser' name="formfile" id="formfile" required>
                </div>   
                <div align="left"><a id="seeCurrentFile" href="" target="_blank">{{ __('messages.seeCurrentFile') }}</a></div>                        
                <input type="hidden" id="hform_id2" name="form_id" />
            </form>
          </div>          
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="fileFormModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form role="form" method="POST" action="{{ route('uploadFormfile') }}" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel2">{{ __('messages.uploadForm') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.formFileRecom') }}</p>
                    <label  >{{ __('messages.formfileType') }}</label>
                    <input id="formfile" class="form-control" type="file" name="formfile" required>
                    <input type="hidden" id="hform_id" name="form_id" />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">{{ __('messages.cancel') }}</button>
                  <button type="submit" class="btn btn-primary">{{ __('messages.upload') }}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    <!-- /modals -->
    <script>
        var formId_share;
        var userId_share;       
        function editName(value){
            $("#editNameModal").modal("show");
            $("#form_id").val(value);
        }
        function shareForm(value){
          str = $("#formfile_"+value).val();
          formId_share=value;
            if (str == "" || str == null) {
                $("#fileFormModal").modal("show");
                $("#hform_id").val(value);
            }else {
                $('#seeCurrentFile').attr('href', "{{ asset('storage') }}/" + str);
                $("#hform_id2").val(value);
                $("#shareFormModal").modal("show");
                $('#userProfile').hide();
                $(".sh_forms").hide();
                $(".form_"+value).show();
            }
        }
        $("#searchUser").click(function(){
            userEmail = $('#addUser').val();
            var token = $("input[name='_token']").val();
            $.ajax({
                async: true,
                url: '{{route('shareForm')}}',
                headers: {"X-CSRF-TOKEN": token},
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({email: userEmail}), 
                success: function (data) {
                    userId_share=data.id;
                    $('#userProfile').show();
                    $('#userEmail').html(data.email);
                    if (data.gender == 0){
                      $('#userGender').html("{{ __('messages.male') }}");
                    }else{
                      $('#userGender').html("{{ __('messages.female') }}");
                    }
                    $('#userCompany').html(data.company);
                    $('#userCountry').html(data.country);
                    $('#userName').html(data.name + " " + data.lastName);  
                    if (data.photo == "" || data.photo == null) {
                      $('#userPhoto').attr('src', "{{ asset('images/user.png') }}");
                    }else{
                      $('#userPhoto').attr('src', "{{ asset('storage') }}/" + data.photo);
                    }                 
                },
                error:function(){
                    userId_share="";
                    $('#userProfile').hide();
                    alert("{{ __('messages.noUsers') }} !");
                }              
            })
        });
        $("#searchFormButton").click(function(){
            var token = $("input[name='_token']").val();
            $.ajax({
                async: true,
                url: '{{route('shareFormCreate')}}',
                headers: {"X-CSRF-TOKEN": token},
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({user_id: userId_share, form_id: formId_share}), 
                success: function (data) {
                  $('#body_shareWith2 tr:last').after('<tr class="sh_forms form_' + formId_share + '" id="shareForm_' + data[0].id + '"><td align="center">'+ data[1].name + ' ' + data[1].lastName + '</td><td align="center">' + data[1].email + '</td><td align="center"><button  type="button" class="btn btn-warning btn-xs" value="' + data[0].id + '" onclick="stopSharing(this.value)">{{ __("messages.stopSharing") }}</button></td></tr>'); 
                    $('#userProfile').hide();                 
                },         
                error:function(){
                    userId_share="";
                    $('#userProfile').hide();
                    alert("{{ __('messages.duplicateUser') }} !");
                } 
            })
        });
        function stopSharing(value){
            var token = $("input[name='_token']").val();
            $.ajax({
                async: true,
                url: '{{route('shareFormDelete')}}',
                headers: {"X-CSRF-TOKEN": token},
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({id: value}), 
                success: function (data) {
                    if (data=='error') {
                      alert("{{ __('messages.errorOccurred') }} !");                      
                    }else{
                      $('#shareForm_'+data).remove();
                    }
                },         
                error:function(){
                  alert("{{ __('messages.errorOccurred') }} !");   
                } 
            })          
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
