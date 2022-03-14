<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Rute</h1>

                    <a href="<?= site_url('rute/tambah') ?>" class="btn btn-primary mb-4">Lihat Peta</a>
                    <h6 class="text-dark">NB: untuk menambahkan dan menghapus rute, silahkan menekan tombol lihat peta</h6>

                    <!-- <div class="table-responsive">
                        <table class="table table-bordered" id="table-rute" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Titik Awal</th>
                                    <th>Titik Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listRute as $rute) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $rute->titik_awal ?></td>
                                        <td><?= $rute->titik_akhir ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>