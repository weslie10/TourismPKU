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
            <div id="table" class="d-none">rute</div>
            <div class="row">
                <div class="col-9 position-relative">
                    <div id="map" style="height: 70vh;"></div>
                    <div class="row align-items-center">
                        <div class="col mt-3">
                            <button id="tambahRute" class="btn btn-primary">Tambah</button>
                            <div id="loading" style="display: none" class="spinner-border spinner-border-sm text-dark ml-3" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="way" id="one-way" value="one-way" checked>
                                        <label class="form-check-label">
                                            One-way
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="way" id="two-way" value="two-way">
                                        <label class="form-check-label">
                                            Two-way
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-primary" id="reset-titik">Reset Titik</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 2vh;"></div>
                    <div class="card" style="height: 30vh;">
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
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-primary"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Rute one-way yang baru</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-success"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Rute two-way yang baru</span>
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