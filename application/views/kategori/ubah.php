<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form class="user" action="<?= site_url("kategori/change/" . $kategori->id) ?>" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Nama Kategoir</label>
                                    <input type="text" class="form-control form-control-user" value="<?= $kategori->nama ?>" name="nama" placeholder="Masukkan nama kategori" required>
                                </div>
                                <input type="submit" name="ubah" value="Ubah" class="btn btn-primary">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>