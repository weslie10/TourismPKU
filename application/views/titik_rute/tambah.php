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
            <div id="table" class="d-none">titik_rute</div>
            <div class="row">
                <div class="col-9">
                    <div id="map" style="height: 70vh;"></div>
                    <div class="row align-items-center">
                        <div class="col mt-3">
                            <button id="tambahTitik" class="btn btn-primary">Tambah</button>
                            <div id="loading" style="display: none" class="spinner-border spinner-border-sm text-dark ml-3" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" col-3">
                    <div class="row">
                        <div class="col">
                            <div class="card" style="height: 40vh;">
                                <div class="card-body overflow-auto" id="side-bar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 2vh;"></div>
                    <div class="row">
                        <div class="col">
                            <div class="card" style="height: 30vh;">
                                <div class="card-body">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak" style="background-color: green"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Titik yang Sudah Ada</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-primary"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Titik Baru</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-danger"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Rute one-way</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-2">
                                            <div class="kotak bg-secondary"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Rute two-way</span>
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