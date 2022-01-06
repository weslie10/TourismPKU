<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Kategori</h1>

                    <a href="<?= site_url('kategori/tambah') ?>" class="btn btn-primary mb-4">Tambah</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-kategori" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listKategori as $kategori) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $kategori->nama ?></td>
                                        <td><a href="<?= site_url('kategori/ubah/' . $kategori->id) ?>">Ubah</a> |
                                            <a href="<?= site_url('kategori/hapus/' . $kategori->id) ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">Hapus</a>
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