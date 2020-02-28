@extends('layouts.dashboard')

@section('content')
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel" style="height:600px;">
            <div class="x_content">
              <div class="row">

                <div class="col-md-12">

                  <!-- price element -->
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="pricing">
                      <div class="title">
                        <h2>{{ env('APP_NAME')}} Basic</h2>
                        <h1>$5 USD</h1>
                      </div>
                      <div class="x_content">
                        <div class="">
                          <div class="pricing_features">
                            <ul class="list-unstyled text-left">
                              <li><i class="fa fa-check text-success"></i> {{__('messages.read')}} <strong> 1.000 </strong> {{__('messages.noRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeInRestriction')}}</li>
                              <li><i class="fa fa-times text-danger"></i> {{__('messages.freeSupport')}}</li>
                            </ul>
                          </div>
                        </div>
                        <div class="pricing_footer">
                          <form action="{!! URL::to('paypal') !!}" method="post" target="_top">
                            {{ csrf_field() }}
        	                  <input id="amount" type="text" name="amount" value="5" hidden></p>
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- price element -->

                  <!-- price element -->
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="pricing ui-ribbon-container">
                      <div class="ui-ribbon-wrapper">
                        <!--<div class="ui-ribbon">
                          30% Off
                        </div>-->
                      </div>
                      <div class="title">
                        <h2>{{ env('APP_NAME')}} Normal</h2>
                        <h1>$10 USD</h1>
                      </div>
                      <div class="x_content">
                        <div class="">
                          <div class="pricing_features">
                            <ul class="list-unstyled text-left">
                              <li><i class="fa fa-check text-success"></i> {{__('messages.read')}} <strong> 3.000 </strong> {{__('messages.noRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeInRestriction')}}</li>
                              <li><i class="fa fa-times text-danger"></i> {{__('messages.freeSupport')}}</li>
                            </ul>
                          </div>
                        </div>
                        <div class="pricing_footer">
                          <form action="{!! URL::to('paypal') !!}" method="post" target="_top">
                            {{ csrf_field() }}
        	                  <input id="amount" type="text" name="amount" value="10" hidden></p>
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- price element -->

                  <!-- price element -->
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="pricing">
                      <div class="title">
                        <h2>{{ env('APP_NAME')}} Pro</h2>
                        <h1>$50 USD</h1>
                      </div>
                      <div class="x_content">
                        <div class="">
                          <div class="pricing_features">
                            <ul class="list-unstyled text-left">
                              <li><i class="fa fa-check text-success"></i> {{__('messages.read')}} <strong> 20.000 </strong> {{__('messages.noRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeInRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeSupport')}}</li>
                            </ul>
                          </div>
                        </div>
                        <div class="pricing_footer">
                          <form action="{!! URL::to('paypal') !!}" method="post" target="_top">
                            {{ csrf_field() }}
        	                  <input id="amount" type="text" name="amount" value="50" hidden></p>
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- price element -->

                  <!-- price element -->
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="pricing">
                      <div class="title">
                        <h2>{{ env('APP_NAME')}} Premium</h2>
                        <h1>$100 USD</h1>
                      </div>
                      <div class="x_content">
                        <div class="">
                          <div class="pricing_features">
                            <ul class="list-unstyled text-left">
                              <li><i class="fa fa-check text-success"></i> {{__('messages.read')}} <strong> 100.000 </strong> {{__('messages.noRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeInRestriction')}}</li>
                              <li><i class="fa fa-check text-success"></i> {{__('messages.freeSupport')}}</li>
                            </ul>
                          </div>
                        </div>
                        <div class="pricing_footer">
                          <form action="{!! URL::to('paypal') !!}" method="post" target="_top">
                            {{ csrf_field() }}
        	                  <input id="amount" type="text" name="amount" value="100" hidden></p>
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- price element -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
