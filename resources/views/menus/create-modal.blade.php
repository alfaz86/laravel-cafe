<div class="modal fade" id="create-modal" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <form id="create-menu" action="{{route('menu.create')}}" method="POST" enctype="multipart/form-data">
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
                            <input type="file" name="img" class="file" accept="image/*" required>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file" value="{{ $fileImgName }}">
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-theme-blue">Browse...</button>
                                </div>
                            </div>
                            <img src="{{ $fileImgData }}" id="preview" class="img-thumbnail">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Nama Menu</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Nama Menu" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" class="form-control" name="price" id="price" autocomplete="off" placeholder="Rp 0" onkeyup="this.value = formatNumber(this.value)" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Jenis</label>
                                <input type="text" class="form-control" name="type" id="type" autocomplete="off" placeholder="Makanan" required>
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
    $(document).on("click", ".browse", function() {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });

    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);
        fileImgName = fileName;

        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview").src = e.target.result;
            fileImgData = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    });

    $("form#create-menu").submit(function(e) {
        e.preventDefault();

        let data = $(this).closest('form').serializeArray();
        data[data.length] = {
            name: 'img',
            value: fileImgData
        }

        $.post("{{route('menu.create')}}", data, function( data ) {
            $("#create-modal").modal("hide");
            $("form#create-menu")[0].reset();
            fileImgName = '';
            fileImgData = '';

            menuTable.ajax.reload(()=>{
                removeSortingIndex('index')
            });
        });
    });
</script>