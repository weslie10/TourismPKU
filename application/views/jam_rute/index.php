<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Jam Rute</h1>

                    <a href="<?= site_url('jam_Rute/tambah') ?>" class="btn btn-primary mb-4">Lihat Peta</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-jam-rute" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jam</th>
                                    <th>Rute ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listJamRute as $jamRute) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $jamRute->jam ?></td>
                                        <td><?= $jamRute->rute_id ?></td>
                                        <td><?= $jamRute->status ?></td>
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