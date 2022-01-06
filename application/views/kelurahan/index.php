<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-9">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Kelurahan</h1>

                    <a href="<?= site_url('kelurahan/tambah') ?>" class="btn btn-primary mb-4">Tambah</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-kelurahan" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kecamatan</th>
                                    <th>Nama Kelurahan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listKelurahan as $kelurahan) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $kelurahan->nama_kecamatan ?></td>
                                        <td><?= $kelurahan->nama ?></td>
                                        <td><a href="<?= site_url('kelurahan/ubah/' . $kelurahan->id) ?>">Ubah</a> |
                                            <a href="<?= site_url('kelurahan/hapus/' . $kelurahan->id) ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">Hapus</a>
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