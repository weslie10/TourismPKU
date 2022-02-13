<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-dark">Tabel Gambar Wisata</h1>

            <a href="<?= site_url('gambar/tambah/' . $wisata_id) ?>" class="btn btn-primary mb-4">Tambah</a>

            <div class="table-responsive">
                <table class="table table-bordered" id="table-gambar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width:40%">Gambar</th>
                            <th>Status</th>
                            <th>Objek Wisata</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($listGambar as $gambar) : ?>
                            <?php
                            $badge = '<span class="badge badge-success">Background</span>';
                            if ($gambar->id != $gambar->background) {
                                $badge = '<span class="badge badge-danger">Biasa</span>';
                            }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img src="<?= base_url($gambar->url) ?>" class="img-fluid"></td>
                                <td><?= $badge ?></td>
                                <td><?= $gambar->nama_wisata ?></td>
                                <td>
                                    <a href="<?= site_url('gambar/pilih/' . $wisata_id . '/' . $gambar->id) ?>">Pilih Sebagai Background</a> |
                                    <a href="<?= site_url('gambar/ubah/' . $gambar->id) ?>">Ubah</a> |
                                    <a href="<?= site_url('gambar/hapus/' . $gambar->id) ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->