@extends('layouts.dashboard')

@section('content')

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{{ __('messages.donate') }}<small></small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="HUC3P5ADZYZQU">
                    <table>
                    <tr><td><input type="hidden" name="on0" value="DONATE">DONATE</td></tr><tr><td><select name="os0">
                    	<option value="Option 1">Option 1 $1.00 USD</option>
                    	<option value="Option 2">Option 2 $5.00 USD</option>
                    	<option value="Option 3">Option 3 $10.00 USD</option>
                    	<option value="Option 4">Option 4 $15.00 USD</option>
                    	<option value="Option 5">Option 5 $20.00 USD</option>
                    	<option value="Option 6">Option 6 $30.00 USD</option>
                    	<option value="Option 7">Option 7 $50.00 USD</option>
                    </select> </td></tr>
                    </table>
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>

                  </div>
                </div>
              </div>
            </div>

@endsection
