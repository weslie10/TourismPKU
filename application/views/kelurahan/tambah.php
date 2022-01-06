<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form class="user" action="<?= site_url("kelurahan/add") ?>" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Nama Kelurahan</label>
                                    <input type="text" class="form-control form-control-user" name="nama" placeholder="Masukkan nama kelurahan" required>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKecamatan) > 0) : ?>
                                        <label for="">Kecamatan</label>
                                        <select name="kecamatan_id" class="form-control">
                                            <?php foreach ($listKecamatan as $kecamatan) : ?>
                                                <option value="<?= $kecamatan->id ?>"><?= $kecamatan->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kecamatannya terlebih dahulu</h6>
                                        <?php endif; ?>
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
    tambah.disabled = <?= count($listKecamatan) == 0 ?>
</script>