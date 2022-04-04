<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Max File: 2MB, Format: .jpg, .jpeg, .png</h5>
                    <div class="row mx-0">
                        <form action="<?= site_url("gambar/add/" . $wisata_id) ?>" enctype="multipart/form-data" id="imageUpload" class="dropzone" method="POST" style="width: 100%;">
                    </div>
                    <h3 class="mt-3">NB: Form akan mengupload 3 file setiap saat ketika klik tombol tambah. Jadi kalau masih ada sisa, silahkan klik tambah hingga selesai</h3>
                    <button type="submit" id="tambah" class="btn btn-primary">Tambah</button>
                    <a class="ml-2" href="<?= site_url("gambar/list/" . $wisata_id) ?>">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    const myDropzone = new Dropzone(".dropzone", {
        addRemoveLinks: true,
        dictRemoveFile: "Delete",
        paramName: "files[]",
        autoProcessQueue: false,
        maxFilesize: 2,
        parallelUploads: 3,
        uploadMultiple: true,
        acceptedFiles: ".jpeg,.jpg,.png"
    });
    myDropzone.on("complete", function(file) {
        setTimeout(() => {
            myDropzone.removeFile(file);
        }, 2000);
    });
    myDropzone.on("maxfilesexceeded", (file) => {
        myDropzone.removeFile(file);
    })

    const tambah = document.getElementById("tambah");
    tambah.addEventListener('click', (e) => {
        e.preventDefault();
        myDropzone.processQueue();
    })
</script>