<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{$quotation->quotation_number}}</title>
</head>
<style>
    table {
        width: 100;
    }
</style>

<body>
    <div style="text-align: center;">
        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate(route('common.view-quotation', $quotation->id))) !!} ">
    </div>
    <div style="text-align: right">
        <div>{{$quotation->created_at}}</div>
    </div>
    <div>
        <h3>Quotation: {{$quotation->quotation_number}}</h3>
    </div>
    <div>
        Customer: {{$quotation->customer->name}}
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    <div class="font-weight-bold item_number">{{$item->number}}</div>
                    <!-- <div>Nescafe 10 Kgs</div> -->
                </td>
                <td>KES {{$item->price}}</td>
                <td class="item_quantity">{{$item->quantity}}</td>
                <td>KES {{$item->price * $item->quantity}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <div>Total: <span style="font-weight: bold;">KES. {{$quotation->total_price}}</span> </div>
    </div>
</body>

</html>