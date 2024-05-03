<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5;
      }

      .receipt {
        width: 80mm; /* Set the width based on your thermal printer's paper width */
        margin: 0 auto;
        padding: 10px;
        box-sizing: border-box;
      }

      .logo img {
        width: 100px; /* Set the width based on your logo size */
        height: auto;
        display: block;
        margin: 0 auto 10px;
      }

      /* Add additional styles for your receipt items */
      .item {
        margin-bottom: 5px;
      }
    </style>
</head>
<body>
@php
    $user = Auth::user();
@endphp
<div class="receipt">
    <div class="logo">
        @if($user->cooperative->logo)
            <img src="{{ $user->cooperative->logo }}" alt="logo" height="100">
        @else
            <img src="{{ public_path('assets/images/favicon.png') }}" alt="logo" height="100">
        @endif
    </div>



    <h2>{{ Auth::user()->cooperative->name }}</h2>
    <p>Date: {{\Carbon\Carbon::now()->format('F d, Y')}}</p>
    <hr>

    <div class="item">
        <span>Product Name:</span>
        <span>Product A</span>
    </div>

    <div class="item">
        <span>Price:</span>
        <span>$19.99</span>
    </div>

    <div class="item">
        <span>Quantity:</span>
        <span>2</span>
    </div>

    <hr>

    <p>Total: $39.98</p>

    <div>
        <p>Thank you for your purchase!</p>
    </div>
</div>
</body>
</html>
