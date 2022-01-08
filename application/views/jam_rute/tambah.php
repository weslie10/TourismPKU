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
            <div id="table" class="d-none">jam_rute</div>
            <div class="row">
                <div class="col-9 position-relative">
                    <div id="map" style="height: 75vh;"></div>
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
<div class="modal fade" id="jamRuteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Jam di rute ini</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-form">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>06.00</label>
                                <select class="form-control status" name="jam6">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>07.00</label>
                                <select class="form-control status" name="jam7">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>08.00</label>
                                <select class="form-control status" name="jam8">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>09.00</label>
                                <select class="form-control status" name="jam9">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>10.00</label>
                                <select class="form-control status" name="jam10">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>11.00</label>
                                <select class="form-control status" name="jam11">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>12.00</label>
                                <select class="form-control status" name="jam12">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>13.00</label>
                                <select class="form-control status" name="jam13">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>14.00</label>
                                <select class="form-control status" name="jam14">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>15.00</label>
                                <select class="form-control status" name="jam15">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>16.00</label>
                                <select class="form-control status" name="jam16">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>17.00</label>
                                <select class="form-control status" name="jam17">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>18.00</label>
                                <select class="form-control status" name="jam18">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>19.00</label>
                                <select class="form-control status" name="jam19">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>20.00</label>
                                <select class="form-control status" name="jam20">
                                    <option value="sepi">Sepi</option>
                                    <option value="ramai">Ramai</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </div>
</div>