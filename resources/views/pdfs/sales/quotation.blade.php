

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <!-- The above 3 meta tags must come first in the head; any other head content must come after these tags -->
      <title>Sales Quotation</title>
      <style>
         @page {
            margin: 0px;
         }
         body {
            font-size: 12px;
            margin: 0px;
            font-family: sans-serif;
         }
         div .inline {
            color: black;
            float: left;
            line-height: 1;
            font-size: 13px;
         }
         .row {
            width: 100%;
         }
         div .row:after {
            clear: both;
            width: 100%;
         }
         .report-title {
            float: left;
            font-size: 1.3em;
            font-weight: 100;
            font-family: sans-serif;
            text-align: center;
            width: 100%;
         }
         .right {
            float: right;
         }
         .header {
            font-size: 12px;
            color: #aaa;
            line-height: 1.5;
         }
         table {
            width: 100%;
            border-collapse: collapse;
         }
         td {
            margin: 0;
            border: none;
            padding: 5px;
            text-align: left;
            font-size: 12px;
            border-bottom: #ddd 1px solid;
            border-right: #ddd 1px solid;
            border-top: #ddd 1px solid;
            border-left: #ddd 1px solid;
         }
         tr {
            border-bottom: #ddd 10px solid;
            width: 100%;
         }
         th {
            padding: 3px;
            background: #eee;
            border-bottom: 1px solid #eee;
            font-size: 9px;
            font-weight: bold;
            color: black;
         }
         td a {
            text-decoration: none;
            color: black;
         }
         .summary-table {
            width: 30%;
            margin-bottom: 20px;
            color: black
         }
         .summary-table tr {
            border-bottom: #eee 0.5pt solid;
            border-top: #eee 0.5pt solid
         }
         .key {
            padding: 10px;
            font-weight: bold;
            background: #eee
         }
         .value {
            padding: 10px;
            text-align: center;
         }
         .col-md-6 {
            width: 50%;
         }
         .col-md-2 {
            width: 20%;
         }
         .dev-color {
            color: #222;
         }
         .dev-color2 {
            color: #222;
         }
         h1,h2,h3,h4,h5,h6,strong,th{
            color: #222;
         }
      </style>
   </head>
   
   <body>
      <!-- ///// -->
      <div style="margin-top: 20px; width: 100%; text-align:center;">
         <img style="width: 160px;padding:10px" src="{{ asset(Auth::user()->cooperative->logo)}}" alt="Shamba Records">
         <h1 align="center" class="dev-color"> {{ 'Customer Sales Quotation' }}</h1>
         <div style="width: 100%; clear: both;"></div>
      </div>
      <div style="width: 100%; padding: 20px">
         <l><strong>Cooperative: </strong>{{ Auth::user()->cooperative->name }}</l>
         <br>
         <l><strong>Address: </strong>{{ Auth::user()->cooperative->email }}</l>
         <br>
         <l><strong>Phone: </strong>{{ Auth::user()->cooperative->contact_details }}</l>
         <br>
         <l><strong>Email: </strong>{{ Auth::user()->cooperative->email }}</l>
         <br>
         <h3 align="center">Customer Details</h3>
         <hr/>
         <!-- members reporrt -->
            <div style="width: 100%; padding: 20px; text-align:left">
               <l><strong>Customer:</strong> {{ $data['sale']->farmer_id ? $data['sale']->farmer->user->first_name." ".$data['sale']->farmer->user->other_names : $data['sale']->customer->name }}</l><br/>
               <l><strong>Email:</strong> {{ $data['sale']->farmer_id ? $data['sale']->farmer->user->email : $data['sale']->customer->email }}</l><br/>
               <l><strong>Phone:</strong> {{ $data['sale']->farmer_id ? $data['sale']->farmer->phone_no : $data['sale']->customer->phone_number }}</l><br/>
            </div>            
            <h4 align="center">Products</h4>
            <!-- savings total variable -->
            @php $totals = 0; $user = Auth::user(); @endphp
               <table>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Discount</th>
                  </tr>
                  <tbody>
                    @foreach($data['items'] as $key => $item)
                    @php $totals += $item->amount; @endphp
                        <tr>
                            <td>{{++$key }}</td>                                        
                            <td>{{ $item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name }}</td>
                            <td>{{ $item->quantity }} {{ $item->manufactured_product_id ? $item->manufactured_product->unit->name : $item->collection->product->unit->name }}</td>
                            <td>{{$user->cooperative->currency}} {{ $item->amount }}</td>
                            <td>{{$user->cooperative->currency}} {{ $item->discount }}</td>
                        </tr>
                    @endforeach
                </tbody>
               </table>
               
               <hr/>   
         <!-- .end members report -->
         <table>
           
            <tr>
               <th>Total Price:</th>
               <td>{{ $totals }}</td>
            </tr>
         </table>
      </div>
      <div style="width: 100%; clear: both;"></div>
      <footer
         style="position: absolute;
         bottom: 0;
         width: 100%;
         text-align: center;
         background-color: #222;
         padding: 10px;
         color:#FFF;
         line-height: 1.6;">
         <p>
            Quotation
         </p>
         <p>
            Disclaimer: This record is produced for your personal use by []
         </p>
         <p>
         This document was electronically generated from {{ config('app.name') }} [ {{ config('app.url') }} ]
         </p>
      </footer>
      <!-- ////// -->
   </body>
</html>
