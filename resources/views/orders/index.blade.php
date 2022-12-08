@extends('layout')

@section('title', 'Order')

@section('content')
@foreach ($orders as $order)
    <div class="card card-datatable shadow mb-3">
        <div class="p-3">
            <span style="font-weight: bold; color: var(--theme-blue)">{{ $order->no_transaction }}</span>
            <br>
            <span style="font-weight: bold; color: var(--theme-g-1)">{{ $order->user->name }}</span>
            <br>
            <span style="font-weight: bold; color: var(--theme-yellow)">{{ Helper::formatRupiah($order->grand_total) }}</span>
        </div>
        <div class="table-responsive p-3">
            <table id="menuTable" class="display table table-bordered">
                <thead>
                    <tr>
                        <th width="1" id="index">No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jenis</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItem as $i => $orderItem)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $orderItem->menu->img }}" class="img-thumbnail-table text-center">    
                            </td>
                            <td>{{ $orderItem->menu->name }}</td>
                            <td>{{ Helper::formatRupiah($orderItem->menu->price) }}</td>
                            <td>{{ $orderItem->menu->type }}</td>
                            <td>{{ $orderItem->qty }}</td>
                            <td>{{ Helper::formatRupiah($orderItem->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
@endsection
