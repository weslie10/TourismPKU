<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Titik Rute</h1>

                    <a href="<?= site_url('titik_rute/tambah') ?>" class="btn btn-primary mb-4">Lihat Peta</a>
                    <h6 class="text-dark mb-4">NB: untuk menambahkan dan menghapus titik rute, silahkan menekan tombol lihat peta</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-titik-rute" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listTitikRute as $titikRute) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $titikRute->lat_coord ?></td>
                                        <td><?= $titikRute->long_coord ?></td>
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