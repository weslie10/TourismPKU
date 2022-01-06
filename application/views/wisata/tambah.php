<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form class="user" action="<?= site_url("wisata/add") ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control form-control-user" name="nama" placeholder="Masukkan nama objek wisata" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar</label>
                                    <input type="file" name="gambar" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat (Kecamatan dan Kelurahan termasuk)</label>
                                    <textarea name="alamat" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Jam Buka</label>
                                    <textarea name="jam_buka" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-user" name="no_telp" placeholder="0812-3456-7890" required>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKategori) > 0) : ?>
                                        <label for="">Kategori</label>
                                        <select name="kategori" class="form-control">
                                            <?php foreach ($listKategori as $kategori) : ?>
                                                <option value="<?= $kategori->id ?>"><?= $kategori->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kategorinya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Latitude</label>
                                    <input type="text" class="form-control form-control-user" name="lat" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Longitude</label>
                                    <input type="text" class="form-control form-control-user" name="long" required>
                                </div>
                            </div>
                        </div>

                        <input type="submit" id="tambah" name="tambah" value="Tambah" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    const tambah = document.getElementById('tambah');
    tambah.disabled = <?= count($listKategori) == 0 ?>
</script>