<div class="modal fade" id="edit-modal" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <form id="edit-menu" action="{{route('menu.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="img">image</label>
                            <div id="msg"></div>
                            <input type="file" name="img" class="file" accept="image/*">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-theme-blue">Browse...</button>
                                </div>
                            </div>
                            <img src="{{ $menu->img }}" id="preview" class="img-thumbnail">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Nama Menu</label>
                                <input type="hidden" class="form-control" name="id" id="id" placeholder="Nama Menu" autocomplete="off" value="{{ $menu->id }}">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Nama Menu" autocomplete="off" value="{{ $menu->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" class="form-control" name="price" id="price" autocomplete="off" placeholder="Rp 0" onkeyup="this.value = formatNumber(this.value)" value="{{ $menu->price }}" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Jenis</label>
                                <input type="text" class="form-control" name="type" id="type" autocomplete="off" placeholder="Makanan" value="{{ $menu->type }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme-g" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-theme-blue">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let imgEdit = '{{ $menu->img }}';
    $(document).on("click", ".browse", function() {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });

    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);

        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview").src = e.target.result;
            imgEdit = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    });

    $("form#edit-menu").submit(function(e) {
        e.preventDefault();

        let data = $(this).closest('form').serializeArray();
        data[data.length] = {
            name: 'img',
            value: imgEdit
        }

        $.ajax({
            url: '{{ route('menu.update') }}',
            type: 'PUT',
            data: data,
            success: function(result) {
                $("#edit-modal").modal('hide');
                menuTable.ajax.reload(()=>{
                    removeSortingIndex('index')
                });
            }
        });
    });
</script>