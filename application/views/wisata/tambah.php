<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div id="table" class="d-none">wisata</div>
                    <form class="user" action="<?= site_url("wisata/add") ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control form-control-user" name="nama" placeholder="Masukkan nama objek wisata" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="alamat" cols="30" rows="2" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-user" name="no_telp" placeholder="0812-3456-7890">
                                </div>
                                <div class="form-group">
                                    <label for="">Jam Buka</label>
                                    <textarea name="jam_buka" id="editor"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <?php if (count($listKecamatan) > 0) : ?>
                                        <label for="">Kecamatan</label>
                                        <input type="hidden" name="kecamatan" id="h-kecamatan">
                                        <select id="kecamatan" class="form-control">
                                            <option value="-1">Silahkan pilih kecamatannya</option>
                                            <?php foreach ($listKecamatan as $kecamatan) : ?>
                                                <option value="<?= $kecamatan->id ?>"><?= $kecamatan->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kecamatannya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKelurahan) > 0) : ?>
                                        <label for="">Kelurahan</label>
                                        <select name="kelurahan" id="kelurahan" class="form-control">
                                            <option value="">Silahkan pilih kelurahannya</option>
                                            <?php foreach ($listKelurahan as $kelurahan) : ?>
                                                <option value="<?= $kelurahan->nama ?>"><?= $kelurahan->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kelurahannya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <?php if (count($listKategori) > 0) : ?>
                                        <label for="">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control">
                                            <?php foreach ($listKategori as $kategori) : ?>
                                                <option value="<?= $kategori->id ?>"><?= $kategori->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <h5>Harap masukkan kategorinya terlebih dahulu</h5>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="">rating</label>
                                    <input type="number" step="0.1" min="0" max="5" class="form-control form-control-user" oninput="javascript: if (this.value > 5) this.value = 5" type="number" name="rating">
                                </div>
                                <div class="form-group">
                                    <label for="">Lokasi</label><br>
                                    <button type="button" class="btn btn-info" id="pilih-lokasi">Pilih lokasi</button>
                                </div>
                                <input type="hidden" id="latitude" class="form-control form-control-user" name="lat" required>
                                <input type="hidden" id="longitude" class="form-control form-control-user" name="long" required>
                            </div>
                        </div>
                        <input type="submit" id="tambah" name="tambah" value="Tambah" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="lokasiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Pilih lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="height:40vh"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn" class="btn btn-primary">Pilih</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/vendor/ckeditor5-build-classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    const tambah = document.getElementById('tambah');
    const kategori = document.getElementById('kategori');
    const kecamatan = document.getElementById('kecamatan');
    const kelurahan = document.getElementById('kelurahan');
    const hKecamatan = document.getElementById('h-kecamatan');
    tambah.disabled = kategori.children.length == 0 && kecamatan.children.length == 0 && kelurahan.children.length == 0;
    kecamatan.addEventListener('change', (e) => {
        hKecamatan.value = kecamatan.options[kecamatan.selectedIndex].text;
        fetch(`${BASE_URL}kelurahan/get_data_by_kecamatan_id/${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    kelurahan.innerHTML = "";
                    data.forEach(el => {
                        kelurahan.innerHTML += `<option value="${el.nama}">${el.nama}</option>`
                    });
                } else {
                    kelurahan.innerHTML = `<option value="">Tidak ada kelurahannya, silahkan pilih kecamatan lain</option>`;
                }
            })
    })
</script>