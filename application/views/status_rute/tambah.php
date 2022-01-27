<style>
    .kotak {
        border: 1px solid black;
        width: 30px;
        height: 30px;
    }
</style>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div id="table" class="d-none">status_rute</div>
            <div class="row">
                <div class="col-9 position-relative">
                    <div id="map" style="height: 75vh;"></div>
                </div>
                <div class="col-3">
                    <div class="card" style="height: 40vh;">
                        <div class="card-body overflow-auto" id="side-bar">
                        </div>
                    </div>
                    <div style="height: 2vh;"></div>
                    <div class="row">
                        <div class="col">
                            <div class="card" style="height: 30vh;">
                                <div class="card-body">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-danger"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Titik/Rute one-way yang sudah ada</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-secondary"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Rute two-way yang sudah ada</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusRuteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Status macet di rute ini</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="modal-form">
                    <?php
                    $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
                    ?>
                    <?php for ($i = 0; $i < count($days); $i++) : ?>
                        <?php if ($i != 0) : ?>
                            <hr>
                        <?php endif; ?>
                        <h1 class="text-dark"><?= $days[$i] ?></h1>
                        <div class="row">
                            <?php $idx = 0; ?>
                            <?php for ($j = 6; $j <= 20; $j++) : ?>
                                <?php if ($idx % 4 == 0) : ?>
                                    <div class="col-3">
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label><?= $j < 10 ? "0" . $j : $j ?>.00</label>
                                        <select class="form-control status" name="jam-<?= $i ?>-<?= $j ?>">
                                            <option value="sepi">Sepi</option>
                                            <option value="ramai">Ramai</option>
                                            <option value="macet">Macet</option>
                                        </select>
                                    </div>
                                    <?php if ($idx % 4 == 3 || $j == 20) : ?>
                                    </div>
                                <?php endif; ?>
                                <?php $idx++; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </div>
</div>