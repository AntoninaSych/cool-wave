<h1>
    Your order status was changed into "{{$order->status}}"
</h1>

<table>
    <tr>
        <th>Order ID</th>
        <td>

            <a href="{{ isset($forAdmin) ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}">
                {{$order->id}}
            </a>
        </td>
    </tr>
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>

    <tr>
        <th>Info</th>
        <td>
            <p>
                Link to your order:
                <a href="{{route('order.view', $order, true)}}">Order #{{$order->id}}</a>
            </p>

        </td>
    </tr>
</table>
<table>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>
                <img src="{{$item->product->image}}" style="width: 100px">
            </td>
            <td>{{$item->product->title}}</td>
            <td>${{$item->unit_price * $item->quantity}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
    @endforeach
</table>