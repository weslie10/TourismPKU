<style>
    .kotak {
        border: 1px solid black;
        width: 30px;
        height: 30px;
    }

    .blue {
        background-color: blue;
    }

    .red {
        background-color: red;
    }
</style>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div id="table" class="d-none">rute</div>
            <div class="row">
                <div class="col-9 position-relative">
                    <div id="map" style="height: 70vh;"></div>
                    <button id="tambahRute" class="btn btn-primary mt-3">Tambah</button>
                </div>
                <div class="col-3">
                    <div class="card" style="height: 40vh;">
                        <div class="card-body" id="side-bar">
                        </div>
                    </div>
                    <div style="height: 2vh;"></div>
                    <div class="row">
                        <div class="col">
                            <div class="card" style="height: 30vh;">
                                <div class="card-body">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-2">
                                            <div class="kotak blue"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Titik Baru</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-2">
                                            <div class="kotak red"></div>
                                        </div>
                                        <div class="col-10">
                                            <span>Titik yang Sudah Ada</span>
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