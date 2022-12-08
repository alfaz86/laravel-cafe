@extends('layout')

@section('title', 'Menu')

@section('content')
<div class="card card-datatable shadow">
    <div class="p-3">
        <button class="btn btn-theme-blue" onclick="getAddForm()">Add</button>
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
                    <th>#Action</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<div id="wrapper"></div>
<div class="modal fade" id="delete-modal" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete-menu" action="#" method="DELETE" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Delete Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Apakah anda yakin ingin menghapus data ini?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme-g" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" id="delete-modal-confirm" onclick="confirmDelete(this.dataset.id, true)" data-id="">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let [fileImgName, fileImgData] = ['', '']
    function getAddForm()
    {
        $.post('{{route('menu.create.modal')}}', { 
            _token:'{{ csrf_token() }}', fileImgName, fileImgData
        }, (data, status) => {
            $('#wrapper').html(data);
            $("#create-modal").modal('show');
        });
    }

    function getEditForm(id)
    {
        $.post('{{route('menu.edit.modal')}}', { 
            _token:'{{ csrf_token() }}', id
        }, (data, status) => {
            $('#wrapper').html(data);
            $("#edit-modal").modal('show');
        });
    }

    var menuTable = $('#menuTable').DataTable({
        searchDelay: 500,
        pageLength: 10,
        language: datatableLanguage(),
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('menu.table')}}',
            data: function ( d ) {
                return $.extend( {}, d, {
                    "extra_search": $('#extra').val(),
                    search: $("input[type='search']").val()
                } );
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
            { data: (d) => {
                return `<img src="${d.img}" class="img-thumbnail-table text-center">`;
            }, orderable: false, className: 'text-center' },
            { data: (d) => d.name, name: 'name' },
            { data: (d) => formatNumber(d.price), name: 'price' },
            { data: (d) => d.type, name: 'type' },
            { data: (d) => d.action, orderable: false },
        ],
        initComplete: function(settings, json) {
            removeSortingIndex("index");
        }
    });

    function removeSortingIndex(id){
        $(`#${id}`).removeClass('sorting_asc');
    }

    function confirmDelete(id, run = false){
        $("#delete-modal-confirm").attr("data-id", id);
        $("#delete-modal").modal('show');

        if (run) {
            $.ajax({
                url: '{{ route('menu.delete') }}',
                type: 'DELETE',
                data: ({
                    _token: '{{ csrf_token() }}',
                    id: id
                }),
                success: function(result) {
                    $("#delete-modal").modal('hide');
                    menuTable.ajax.reload(()=>{
                        removeSortingIndex('index')
                    });
                }
            });
        }
    }
</script>
@endsection