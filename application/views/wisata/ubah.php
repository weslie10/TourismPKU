<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div id="table" class="d-none">wisata</div>
                    <form class="user" action="<?= site_url("wisata/change") ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control form-control-user" value="<?= $wisata->nama ?>" name="nama" placeholder="Masukkan nama objek wisata" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar</label>
                                    <input type="file" name="gambar" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat (Kecamatan dan Kelurahan termasuk)</label>
                                    <textarea name="alamat" cols="30" rows="3" class="form-control"><?= $wisata->alamat ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Jam Buka</label>
                                    <textarea name="jam_buka" cols="30" rows="3" class="form-control"><?= $wisata->jam_buka ?></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-user" value="<?= $wisata->no_telp ?>" name="no_telp" placeholder="0812-3456-7890" required>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKategori) > 0) : ?>
                                        <label for="">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control">
                                            <?php foreach ($listKategori as $kategori) : ?>
                                                <option value="<?= $kategori->id ?>" <?= $wisata->kategori == $kategori->id ? "selected" : "" ?>><?= $kategori->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kategorinya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Lokasi</label><br>
                                    <button type="button" class="btn btn-info" id="pilih-lokasi">Pilih lokasi</button>
                                </div>
                                <input type="hidden" id="latitude" value="<?= $wisata->lat_coord ?>" class="form-control form-control-user" name="lat" required>
                                <input type="hidden" id="longitude" value="<?= $wisata->long_coord ?>" class="form-control form-control-user" name="long" required>
                            </div>
                        </div>

                        <input type="submit" id="tambah" name="tambah" value="Tambah" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="lokasiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Pilih lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="height:40vh"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn" class="btn btn-primary">Pilih</button>
            </div>
        </div>
    </div>
</div>
<script>
    const tambah = document.getElementById('tambah');
    const kategori = document.getElementById('kategori');
    tambah.disabled = kategori.children.length == 0;
</script>