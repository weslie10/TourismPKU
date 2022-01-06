<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form class="user" action="<?= site_url("kelurahan/change/" . $kelurahan->id) ?>" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Nama Kelurahan</label>
                                    <input type="text" class="form-control form-control-user" value="<?= $kelurahan->nama ?>" name="nama" placeholder="Masukkan nama kecamatan" required>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKecamatan) > 0) : ?>
                                        <label for="">Kecamatan</label>
                                        <select name="kecamatan_id" class="form-control">
                                            <?php foreach ($listKecamatan as $kecamatan) : ?>
                                                <option value="<?= $kecamatan->id ?>" <?= ($kecamatan->id == $kelurahan->kecamatan_id) ? "selected" : "" ?>><?= $kecamatan->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kecamatannya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <input type="submit" id="ubah" name="ubah" value="Ubah" class="btn btn-primary">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const ubah = document.getElementById('ubah');
    ubah.disabled = <?= count($listKecamatan) == 0 ?>
</script>