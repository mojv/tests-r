@extends('layouts.dashboard')

@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{{ __('messages.usefulThings') }}</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row">


                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('images/omr-bubbles.jpg') }}" alt="image" />
                            <div class="mask">
                              <p> {{ __('messages.OMRBubble') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/omr-bubble-font.zip') }}" download="true"><i class="fa fa-download"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.OMRBubbleFont') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('images/omr-square.jpg') }}" alt="image" />
                            <div class="mask">
                              <p> {{ __('messages.OMRSquare') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/omr-square-font.zip') }}" download="true"><i class="fa fa-download"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.OMRSquareFont') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('images/omr-rectangles.jpg') }}" alt="image" />
                            <div class="mask">
                              <p> {{ __('messages.OMRRectangle') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/omr-rectangle-font.zip') }}" download="true"><i class="fa fa-download"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.OMRRectangleFont') }}</p>
                          </div>
                        </div>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
        <!-- /page content -->

@endsection
