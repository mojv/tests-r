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
                    <h2>{{ __('messages.formReadTemplates') }}</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row">


                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/Blank_Letter_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p> {{ __('messages.blankLetter') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/Blank_Letter.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/Blank_Letter.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/Blank_Letter.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.blankLetter') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/Blank_A4_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p>{{ __('messages.blankA4') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/Blank_A4.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/Blank_A4.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/Blank_A4.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.blankA4') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/Letter_100_Q_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p>{{ __('messages.letter100Q') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/Letter_100_Q.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/Letter_100_Q.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/Letter_100_Q.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.letter100Q') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/A4_100_Q_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p>{{ __('messages.A4100Q') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/A4_100_Q.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/A4_100_Q.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/A4_100_Q.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.A4100Q') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/Questionnaire_12Q_letter_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p>{{ __('messages.quest15QLetter') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/Questionnaire_12Q_letter.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/Questionnaire_12Q_letter.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/Questionnaire_12Q_letter.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.quest15QLetter') }}</p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-55">
                        <div class="thumbnail">
                          <div class="image view view-first">
                            <img style="width: 100%; display: block;" src="{{ asset('download/Letter_35_Q_ico.jpg') }}" alt="image" />
                            <div class="mask">
                              <p>{{ __('messages.letter35Q') }}</p>
                              <div class="tools tools-bottom">
                                  <a href="{{ asset('download/Letter_35_Q.jpg') }}" download><i class="fa fa-file-image-o"></i></a>
                                <a href="{{ asset('download/Letter_35_Q.pdf') }}" download><i class="fa fa-file-pdf-o"></i></a>
                                <a href="{{ asset('download/Letter_35_Q.docx') }}" download><i class="fa fa-file-word-o"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="caption">
                            <p>{{ __('messages.letter35Q') }}</p>
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
