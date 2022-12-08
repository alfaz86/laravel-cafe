@extends('layout')

@section('title', 'Home Page')

@section('content')
<form class="form-inline my-2">
    <div class="input-group mb-2 w-100">
        <input class="form-control mr-sm-2 p-4 input-search" type="search" name="search" id="search" placeholder="Cari..." aria-label="Cari..." autocomplete="off">
        <div class="input-group">
            <button class="input-group-text btn btn-outline-success" type="button" onclick="filterMenu()">
                <i class="bi bi-search mr-3 ml-3"></i>
            </button>
        </div>
    </div>
</form>
<div class="row">
    @foreach ($menus as $menu)
    <input type="hidden" class="filter" value="{{ $menu->name }}" data-id="{{ $menu->id }}">
    <div class="col-12 col-md-3 card-menu-{{ $menu->id }}">
        <div class="card mt-2 mb-2 shadow bg-white rounded">
            <div class="p-3 div-img-home">
                <div class="img-cover" style="background-image: url('{{ $menu->img }}')">

                </div>
            </div>
            <div class="card-body text-center">
                <h5 class="card-text mb-0" style="font-weight: bold;">{{ $menu->name }}</h5>
                <h5 class="card-text" style="font-weight: bold; color: var(--theme-g-1)">{{ \Helper::formatRupiah($menu->price) }}</h5>
                @if (auth()->user())
                    @if (auth()->user()->role == 'customer')
                        <div class="btn btn-theme-blue" onclick="addToCart({{ $menu->id }})">+ tambah</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- The Modal -->
<div id="myModal" class="modal-checkout">

    <!-- Modal content -->
    <div class="modal-content-checkout">
        <form action="{{ route('order.create') }}" method="POST" id="add-cart">
            @csrf
            <div class="modal-header">
                <div class="d-flex bd-highlight f-bold div-text">
                    <div class="flex-grow-1 bd-highlight">
                        <span class="text-header w-100" style="font-weight: bold;">Checkout</span>
                    </div>
                    <div class="bd-highlight d-none">
                        <span class="close close-checkout">&times;</span>
                    </div>
                </div>
            </div>
            <!-- Modal Pesanan -->
            <div class="modal-body" style="height: 60vh; overflow-y: scroll;">
                <div id="list-menu">
    
                </div>
            </div>
            <div class="div-total modal-footer">
                <table class="w-100">
                    <tr>
                        <td class="w-50">
                            <div class="mr-3">
                                <span style="font-weight: bold;">Total </span>
                                <span id="elgrandtotal" style="font-weight: bold;">Rp 0</span>
                                <input type='hidden' id='grand-total' name="grand_total"/>
                            </div>
                        </td>
                        <td class="w-50 text-right">
                            <button class="btn btn-theme-blue" type="submit">Buat Order</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $( window ).mousemove(function() {
        let widthWindow = $(window).width();
        let widthContainer = $(".container").width();
        if (widthContainer >= 720) {
            $(".modal-content-checkout").css({
                "width": widthContainer,
                "margin-left": (widthWindow - widthContainer)/2,
                "border-top-left-radius": "10px",
                "border-top-right-radius": "10px",
            });
            $(".div-two").css("margin-left", 80);
            $(".order-list").css("margin-left", 20);
        } else {
            $(".modal-content-checkout").css({
                "width": "100%",
                "margin-left": 0,
                "border-top-left-radius": "10px",
                "border-top-right-radius": "10px",
            });
            $(".div-two").css("margin-left", 0);
            $(".order-list").css("margin-left", 0);
        }
    });

    function addToCart(id){
        $.ajax({
            type: "POST",
            url: "{{ route('menu.add.cart') }}",
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            success: function(data) {
                $("#CheckOut").html(data)
            }
        });
    }

    var modal = document.getElementById("myModal");
    var btn = document.getElementById("myBtn");

    if (btn) {
        btn.onclick = function () {
            $.ajax({
                type: "GET",
                url: "{{ route('menu.show.cart') }}",
                success: function(data) {
                    $("#list-menu").html(data["element"]);
                    $("#elgrandtotal").html(data["total"]);
                    $("#grand-total").val(formatNumber(data["total"], false));
                    modal.style.display = "block";
                }
            });
        }
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    $("#search").keypress(function(e) {
        if (e.key === "Enter") {
            e.preventDefault();

            filterMenu()
        }
    });

    function filterMenu(){
        let input = $("#search").val();
        let filter = $(".filter")
        Array.from(filter).forEach(element => {
            if (element.value.toLowerCase().match(input.toLowerCase())) {
                $(`.card-menu-${element.dataset.id}`).removeClass("d-none");
            } else {
                $(`.card-menu-${element.dataset.id}`).addClass("d-none");
            }
        })
    }
</script>

<!-- Checkout Script -->
<script>
    const editMenu = (id, opsi = null) => {
        const qty = document.getElementById(`qty-${id}`)
        const price = document.getElementById(`price-${id}`)
        const subtotal = document.getElementById(`subtotal-${id}`)
        const label_subtotal = document.getElementById(`label-subtotal-${id}`)
        
        if (Number(qty.value) < 1) qty.value = 1
        if (opsi == 'plus') qty.value = Number(qty.value) + 1
        if (opsi == 'min') {
            qty.value = Number(qty.value) == 1
                ? 1
                : Number(qty.value) - 1
        }

        const total = price.value * qty.value
        subtotal.value = total
        label_subtotal.innerHTML = formatNumber(total)

        calculateGrandTotal()
    }

    const calculateGrandTotal = () => {
        const subtotal = $(".subtotal");
        let grandtotal = 0
        Array.from(subtotal).forEach(element => {
            grandtotal += Number(element.value)
        })
        $("#elgrandtotal").html(formatNumber(grandtotal));
        $("#grand-total").val(grandtotal);
    }

    const deleteMenu = (id) => {
        $.ajax({
            url: '{{ route('menu.delete.cart') }}',
            type: 'DELETE',
            data: ({
                _token: '{{ csrf_token() }}',
                id: id
            }),
            success: function(result) {
                $(`#row-${id}`).remove();
                $("#CheckOut").html(result)
                calculateGrandTotal()
            }
        });
    }

    $("form#add-cart").submit(function(e) {
        e.preventDefault();

        let data = $(this).closest('form').serializeArray();

        $.post("{{route('order.create')}}", data, function( data ) {
            $("#CheckOut").html(data);
            
            setTimeout(() => {
                modal.style.display = "none";
                location.href = "{{ route('orders') }}";
            }, 1000);
        });
    });
</script>
@endsection
