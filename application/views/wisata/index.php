<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-dark">Tabel Wisata</h1>

            <a href="<?= site_url('wisata/tambah') ?>" class="btn btn-primary mb-4">Tambah</a>

            <div class="table-responsive">
                <table class="table table-bordered" id="table-wisata" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Gambar</th>
                            <th>Alamat</th>
                            <th style="width:15%">Jam Buka</th>
                            <th>No Telp</th>
                            <th>Kategori</th>
                            <th>Rating</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($listWisata as $wisata) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $wisata->nama ?></td>
                                <td>
                                    <?php if ($wisata->gambar != 0) : ?>
                                        <img src="<?= base_url($wisata->background) ?>" class="img-fluid">
                                    <?php else : ?>
                                        <p>Tidak ada gambar</p>
                                    <?php endif; ?>
                                </td>
                                <td><?= $wisata->alamat ?></td>
                                <td><?= $wisata->jam_buka ?></td>
                                <td><?= $wisata->no_telp ?></td>
                                <td><?= $wisata->nama_kategori ?></td>
                                <td><?= $wisata->rating ?></td>
                                <td><?= $wisata->lat_coord ?></td>
                                <td><?= $wisata->long_coord ?></td>
                                <td>
                                    <a href="<?= site_url('wisata/ubah/' . $wisata->id) ?>">Ubah</a> |
                                    <a href="<?= site_url('wisata/hapus/' . $wisata->id) ?>">Hapus</a> |
                                    <a href="<?= site_url('gambar/list/' . $wisata->id) ?>">Gambar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>