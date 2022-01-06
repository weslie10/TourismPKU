const listTable = [
    "#table-wisata",
    "#table-kategori",
    "#table-kecamatan",
    "#table-kelurahan",
    "#table-titik-rute",
]
$(document).ready(() => {
    listTable.forEach(el => {
        $(el).DataTable();
    });
});