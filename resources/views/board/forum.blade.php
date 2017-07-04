@extends('layouts.dashboard')

@section('content')

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{{ __('messages.forum') }}<small></small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <iframe id="forum_embed"
                    src="javascript:void(0)"
                    scrolling="no"
                    frameborder="0"
                    width="900"
                    height="700">
                    </iframe>
                    <script type="text/javascript">
                        document.getElementById('forum_embed').src =
                       'https://groups.google.com/forum/embed/?place=forum/formread'
                       + '&showsearch=true&showpopout=true&showtabs=false'
                       + '&parenturl=' + encodeURIComponent(window.location.href);
                    </script>

                  </div>
                </div>
              </div>
            </div>

@endsection
