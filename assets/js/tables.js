const listTable = [
    "#table-wisata",
    "#table-kategori",
    "#table-kecamatan",
    "#table-kelurahan",
    "#table-titik-rute",
    "#table-rute",
    "#table-status-rute",
]
$(document).ready(() => {
    listTable.forEach(el => {
        $(el).DataTable();
    });
});