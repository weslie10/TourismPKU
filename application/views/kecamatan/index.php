<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Kecamatan</h1>

                    <a href="<?= site_url('kecamatan/tambah') ?>" class="btn btn-primary mb-4">Tambah</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-kecamatan" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kecamatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listKecamatan as $kecamatan) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $kecamatan->nama ?></td>
                                        <td><a href="<?= site_url('kecamatan/ubah/' . $kecamatan->id) ?>">Ubah</a> |
                                            <a href="<?= site_url('kecamatan/hapus/' . $kecamatan->id) ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>