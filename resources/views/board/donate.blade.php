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
            		    <input name="cmd" type="hidden" value="_s-xclick" />
            		    <input name="hosted_button_id" type="hidden" value="YVC76H3XMD6KL" />
                    <table>
                    <tr><td><input type="hidden" name="on0" value="Donations">Donations</td></tr><tr><td><select name="os0">
                            <option value="Donate">Donate $1.00 USD</option>
                            <option value="Donate">Donate $5.00 USD</option>
                            <option value="Donate">Donate $10.00 USD</option>
                            <option value="Donate">Donate $15.00 USD</option>
                            <option value="Donate">Donate $20.00 USD</option>
                            <option value="Donate">Donate $50.00 USD</option>
                    </select> </td></tr>
                    </table>
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>

                  </div>
                </div>
              </div>
            </div>

@endsection
