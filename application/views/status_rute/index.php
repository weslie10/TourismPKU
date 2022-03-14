<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-dark">Tabel Status Rute</h1>

                    <a href="<?= site_url('status_Rute/tambah') ?>" class="btn btn-primary mb-4">Lihat Peta</a>
                    <h6 class="text-dark">NB: untuk menambahkan dan mengubah status rute, silahkan menekan tombol lihat peta</h6>

                    <!-- <div class="table-responsive">
                        <table class="table table-bordered" id="table-status-rute" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Rute ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($listStatusRute as $statusRute) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $statusRute->hari ?></td>
                                        <td><?= $statusRute->jam ?></td>
                                        <td><?= $statusRute->rute_id ?></td>
                                        <td><?= $statusRute->status ?></td>
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