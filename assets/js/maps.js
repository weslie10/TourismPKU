let table = document.getElementById('table');
if (table) {
    table = table.innerHTML;
}
const BASE_URL = document.getElementById('base-url').innerText;

const markerHtmlStyles = `
  width: 0.5rem;
  height: 0.5rem;
  display: block;
  left: -0.25rem;
  top: -0.25rem;
  position: relative;
  border-radius: 0.5rem 0.5rem 0;
  transform: rotate(45deg);
  border: 1px solid #FFFFFF`

const newIcon = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 3],
  labelAnchor: [-3, 0],
  popupAnchor: [0, -18],
  html: `<span style="background-color: red; ${markerHtmlStyles};" />`
})
const newIcon2 = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 3],
  labelAnchor: [-3, 0],
  popupAnchor: [0, -18],
  html: `<span style="background-color: blue; ${markerHtmlStyles};" />`
})

const fetchData = async (url) => {
    const response = await fetch(url);
    const data = await response.json();
    return new Promise((resolve, reject)=>{
        resolve(data);
    });
}
const postData = async (url, data) => {
    const response = await fetch(url,{
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    const dataResponse = await response.json();
    return new Promise((resolve, reject)=>{
        resolve(dataResponse);
    });
}

if (document.getElementById('map')) {
    let map = L.map('map').setView([0.501947, 101.443751], 12);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid2VzbGllMTAiLCJhIjoiY2t5MTBydnV2MDZ5YTJ3bXI1anRlNnEyZyJ9.LK5NkKV6Yq3lTLBVuE_bzg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoid2VzbGllMTAiLCJhIjoiY2t5MTBydnV2MDZ5YTJ3bXI1anRlNnEyZyJ9.LK5NkKV6Yq3lTLBVuE_bzg'
    }).addTo(map);

    if(table == "titik_rute") {
        const SideBar = document.getElementById('side-bar');
        const loading = document.getElementById('loading');
        let markerGroup = L.layerGroup().addTo(map);
        let objekWisata = L.layerGroup().addTo(map);

        const reloadMap = () => {
            markerGroup = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}titik_Rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, lat_coord, long_coord} = titik;
                    const marker = L.marker([lat_coord, long_coord], {icon: newIcon}).addTo(markerGroup);
                    marker.id = id;

                    marker.addEventListener('click',() => {
                        SideBar.innerHTML = `<h1>${marker.id}</h1><button id="hapus" class="btn btn-primary">Hapus titik ini</button>`;
                        const hapus = document.getElementById('hapus');
        
                        hapus.addEventListener('click',async () => {
                            marker.remove();
                            hapus.remove();
                            SideBar.innerHTML = "";
                            await fetchData(`${BASE_URL}titik_Rute/delete/${marker.id}`);
                        })
                    })
                });
            });
            objekWisata = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}wisata/all`).then(data=>{
                if (data.status == "success") {
                    data.data.forEach(wisata => {
                        const marker = L.marker([wisata.lat_coord,wisata.long_coord]).addTo(objekWisata);
                        marker.id = wisata.id;
                        marker.nama = wisata.nama
                        marker.gambar = wisata.gambar;
                        marker.alamat = wisata.alamat;
                        marker.jam_buka = wisata.jam_buka;
                        marker.no_telp = wisata.no_telp;
                        marker.kategori = wisata.kategori;

                        marker.addEventListener('click',() => {
                            SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            <img src="${BASE_URL}${marker.gambar}" class="img-fluid" />
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${marker.jam_buka ? marker.jam_buka : "tidak ada"}</p>
                            <p>No Telp: ${marker.no_telp ? marker.no_telp : "tidak ada"}</p>
                            <p>Kategori: ${marker.kategori}</p>
                            `;
                        })
                    });
                }
            });
        }
        
        let point = [];
        reloadMap();

        map.addEventListener('click', (e) => {
            SideBar.innerHTML = "";
            const coord = e.latlng;
            const marker = L.marker([coord.lat, coord.lng], {icon: newIcon2}).addTo(markerGroup);
            point.push({lat: coord.lat, long: coord.lng});

            marker.addEventListener('click',() => {
                SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus titik ini</button>`;
                const hapus = document.getElementById('hapus');

                hapus.addEventListener('click',() => {
                    const markerCoord = marker.getLatLng();
                    point = point.filter((item) => item.lat !== markerCoord.lat && item.long !== markerCoord.lng);
                    marker.remove();
                    hapus.remove();
                })
            })
        });

        const tambahTitik = document.getElementById('tambahTitik');
        tambahTitik.addEventListener('click', async () => {
            if (point.length > 0) {
                loading.style.display = "inline-block";
                tambahTitik.disabled = true;
                const result = await postData(`${BASE_URL}titik_Rute/add`,point);
                if (result.status) {
                    point = [];
                    map.removeLayer(markerGroup);
                    reloadMap();
                    alert(result.message);
                } else {
                    alert(result.message)
                }
                tambahTitik.disabled = false;
                loading.style.display = "none";
            } else {
                alert('Belum ada titik rute');
            }
        });
    } else if (table == "rute") {
        const SideBar = document.getElementById('side-bar');
        const loading = document.getElementById('loading');
        const resetTitik = document.getElementById('reset-titik');

        let markerGroup = L.layerGroup().addTo(map),
            lineGroup = L.layerGroup().addTo(map),
            circleGroup = L.layerGroup().addTo(map),
            objekWisata = L.layerGroup().addTo(map);
        let titik_awal = {}, titik_akhir = {};
        let lines = [];
        let flag = true;

        const reset = () => {
            titik_awal = {};
            titik_akhir = {};
            flag = true;
            SideBar.innerHTML=`<h1 class="text-dark">Silahkan pilih titik pertama</h1>`;
            map.removeLayer(circleGroup);
            circleGroup = L.layerGroup().addTo(map);
        }
        
        let way = "one-way"
        const oneWay = document.getElementById('one-way');
        const twoWay = document.getElementById('two-way');
        oneWay.addEventListener('click',() => {
            way = oneWay.value;
            reset();
        })
        twoWay.addEventListener('click',() => {
            way = twoWay.value;
            reset();
        })

        SideBar.innerHTML=`<h1 class="text-dark">Silahkan pilih titik pertama</h1>`;
        resetTitik.addEventListener('click', () => {
            reset();
        })

        const reloadLine = () => {
            lineGroup = L.layerGroup().addTo(map);
            fetchData(`${BASE_URL}rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, titik_awal, titik_akhir, lat_awal, id_kedua, long_awal, lat_akhir, long_akhir} = titik;
                    if (id_kedua != null) {
                        const line = L.polyline([
                            [lat_awal, long_awal],
                            [lat_akhir, long_akhir]
                        ], {color: 'grey'}).addTo(lineGroup);
                        line.id = id;
                        line.titik_awal = titik_awal;
                        line.titik_akhir = titik_akhir;
                        line.id_kedua = id_kedua
                        line.addEventListener('click', () => {
                            SideBar.innerHTML = `<h1>${line.id} dan ${line.id_kedua}</h1><br><button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
                            const hapus = document.getElementById('hapus');
            
                            hapus.addEventListener('click',async () => {
                                line.remove();
                                hapus.remove();
                                SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${flag ? "pertama" : "kedua"}</h1>`;
                                await fetchData(`${BASE_URL}rute/delete/${line.id}`);
                                await fetchData(`${BASE_URL}rute/delete/${line.id_kedua}`);
                            })
                        })
                    } else {
                        const line = L.polyline([
                            [lat_awal, long_awal],
                            [lat_akhir, long_akhir]
                        ], {color: 'red'}).arrowheads({size:"10px"}).addTo(lineGroup);
                        line.id = id;
                        line.titik_awal = titik_awal;
                        line.titik_akhir = titik_akhir;
                        line.addEventListener('click', () => {
                            SideBar.innerHTML = `<h1>${line.id}</h1><br><button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
                            const hapus = document.getElementById('hapus');
            
                            hapus.addEventListener('click',async () => {
                                line.remove();
                                hapus.remove();
                                SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${flag ? "pertama" : "kedua"}</h1>`;
                                await fetchData(`${BASE_URL}rute/delete/${line.id}`);
                            })
                        })
                    }
                });
            })
        }

        const reloadPoint = () => {
            markerGroup = L.layerGroup().addTo(map);
            fetchData(`${BASE_URL}titik_Rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, lat_coord, long_coord} = titik;
                    const marker = L.marker([lat_coord, long_coord], 
                        {icon: newIcon}
                    ).addTo(markerGroup);
                    marker.id = id;

                    marker.addEventListener('click',() => {
                        const coord = marker.getLatLng()
                        if (flag) {
                            L.circle([coord.lat, coord.lng], {radius: 30, color: way == "one-way" ? "blue" : "green"}).addTo(circleGroup);
                            titik_awal = {
                                id: marker.id,
                                lat: coord.lat,
                                long: coord.lng,
                            };
                            flag = !flag;
                            SideBar.innerHTML=`<h1 class="text-dark">Silahkan pilih titik kedua</h1>`;
                        } else {
                            if (titik_awal.id != marker.id) {
                                map.removeLayer(circleGroup);
                                circleGroup = L.layerGroup().addTo(map);
                                titik_akhir = {
                                    id: marker.id,
                                    lat: coord.lat,
                                    long: coord.lng,
                                };
                                flag = !flag;
                                if (way == "one-way") {
                                    const check = lines.some((el) => {
                                        return (el.titik_awal.id == titik_awal.id  && el.titik_akhir.id == titik_akhir.id) || (el.titik_awal.id == titik_akhir.id  && el.titik_akhir.id == titik_awal.id);
                                    })
                                    if (!check) {
                                        lines.push({
                                            titik_awal: titik_awal, 
                                            titik_akhir: titik_akhir
                                        });
                                        const line = L.polyline([
                                            [titik_awal.lat, titik_awal.long],
                                            [titik_akhir.lat, titik_akhir.long]
                                        ], {color: 'blue'}).arrowheads({size:"10px"}).addTo(lineGroup);
                                        line.titik_awal = titik_awal;
                                        line.titik_akhir = titik_akhir;
        
                                        line.addEventListener('click', ()=>{
                                            SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
                                            const hapus = document.getElementById('hapus');
                            
                                            hapus.addEventListener('click',async () => {
                                                line.remove();
                                                hapus.remove();
                                                lines = lines.filter((el)=>{
                                                    return el.titik_awal != line.titik_awal || el.titik_akhir != line.titik_akhir;
                                                });
                                                SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${flag ? "pertama" : "kedua"}</h1>`;
                                            })
                                        })
                                    } else {
                                        alert('Silahkan cari rute lain');
                                        reset();
                                    }
                                } else {
                                    const check = lines.some((el) => {
                                        return (el.titik_awal.id == titik_awal.id  && el.titik_akhir.id == titik_akhir.id);
                                    })
                                    if (!check) {
                                        lines.push({
                                            titik_awal: titik_awal, 
                                            titik_akhir: titik_akhir
                                        },{
                                            titik_awal: titik_akhir, 
                                            titik_akhir: titik_awal
                                        });
                                        const line = L.polyline([
                                            [titik_awal.lat, titik_awal.long],
                                            [titik_akhir.lat, titik_akhir.long]
                                        ], {color: 'green'}).addTo(lineGroup);
                                        line.titik_awal = titik_awal;
                                        line.titik_akhir = titik_akhir;
        
                                        line.addEventListener('click', ()=>{
                                            SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
                                            const hapus = document.getElementById('hapus');
                            
                                            hapus.addEventListener('click',async () => {
                                                line.remove();
                                                hapus.remove();
                                                lines = lines.filter((el)=>{
                                                    return (el.titik_awal != line.titik_awal || el.titik_akhir != line.titik_akhir) && (el.titik_awal != line.titik_akhir || el.titik_akhir != line.titik_awal);
                                                });
                                                SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${flag ? "pertama" : "kedua"}</h1>`;
                                            })
                                        })
                                    } else {
                                        alert('Silahkan cari rute lain');
                                        reset();
                                    }
                                }
                                reset();
                            } else {
                                reset();
                            }
                        }
                    })
                });
            });
            objekWisata = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}wisata/all`).then(data=>{
                if (data.status == "success") {
                    data.data.forEach(wisata => {
                        const marker = L.marker([wisata.lat_coord,wisata.long_coord]).addTo(objekWisata);
                        marker.id = wisata.id;
                        marker.nama = wisata.nama
                        marker.gambar = wisata.gambar;
                        marker.alamat = wisata.alamat;
                        marker.jam_buka = wisata.jam_buka;
                        marker.no_telp = wisata.no_telp;
                        marker.kategori = wisata.kategori;

                        marker.addEventListener('click',() => {
                            SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            <img src="${BASE_URL}${marker.gambar}" class="img-fluid" />
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${marker.jam_buka ? marker.jam_buka : "tidak ada"}</p>
                            <p>No Telp: ${marker.no_telp ? marker.no_telp : "tidak ada"}</p>
                            <p>Kategori: ${marker.kategori}</p>
                            `;
                        })
                    });
                }
                
            });
        }
        
        reloadPoint();
        reloadLine();

        const tambahRute = document.getElementById('tambahRute');
        tambahRute.addEventListener('click', async () => {
            if (lines.length > 0) {
                loading.style.display = "inline-block";
                tambahRute.disabled = true;
                const result = await postData(`${BASE_URL}rute/add`,lines);
                if (result.status) {
                    lines = [];
                    map.removeLayer(lineGroup);
                    reloadLine();
                    alert(result.message);
                } else {
                    alert(result.message)
                }
                tambahRute.disabled = false;
                loading.style.display = "none";
                reset();
            } else {
                alert('Belum ada rute')
            }
        });
    } else if (table == "status_rute") {
        const statusRuteModal = new bootstrap.Modal(document.getElementById("statusRuteModal"), {
            keyboard: false
        })
        const hari = ["senin", "selasa", "rabu", "kamis", "jumat", "sabtu", "minggu"];
        const modalTitle = document.getElementById('modal-title');
        const modalBtn = document.getElementById('modal-btn');
        const status = document.getElementsByClassName('status');

        let objekWisata = L.layerGroup().addTo(map);

        const reloadLine = () => {
            lineGroup = L.layerGroup().addTo(map);
            fetchData(`${BASE_URL}rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, lat_awal, id_kedua, long_awal, lat_akhir, long_akhir} = titik;
                    if (id_kedua != null) {
                        const line = L.polyline([
                            [lat_awal, long_awal],
                            [lat_akhir, long_akhir]
                        ], {color: 'grey'}).addTo(lineGroup);
                        line.id = id;
                        line.id_kedua = id_kedua
                        line.addEventListener('click', () => {
                            statusRuteModal.show();
                            let action = "add";
                            modalTitle.innerHTML = `Status macet di rute ${line.id} dan ${line.id_kedua}`
                            fetchData(`${BASE_URL}status_Rute/get_by_rute_id/${line.id}`).then(listJam=>{
                                if (listJam.length > 0) {
                                    for (let i = 0; i < listJam.length; i++) {
                                        status[i].value = listJam[i].status;
                                    }
                                    action = "update";
                                } else {
                                    for (let i = 0; i < status.length; i++) {
                                        status[i].value = "sepi";
                                    }
                                    action = "add";
                                }
                            });
                            modalBtn.addEventListener('click',() => {
                                let data = [];
                                for(let i = 0; i < status.length; i++) {
                                    data.push({
                                        hari: hari[Math.floor(i/15)],
                                        jam: (i%15)+6,
                                        rute_id: line.id,
                                        status: status[i].value
                                    })
                                }
                                const data2 = data.map((el)=> {
                                    return {
                                        hari: el.hari,
                                        jam: el.jam,
                                        rute_id: line.id_kedua,
                                        status: el.status
                                    }
                                })
                                Promise.all([
                                    postData(`${BASE_URL}status_Rute/${action}`,data),
                                    postData(`${BASE_URL}status_Rute/${action}`,data2)
                                ]).then((result) => {
                                    alert(result[0].message);
                                });
                            })
                        });
                    } else {
                        const line = L.polyline([
                            [lat_awal, long_awal],
                            [lat_akhir, long_akhir]
                        ], {color: 'red'}).arrowheads({size:"10px"}).addTo(lineGroup);
                        line.id = id;
                        line.addEventListener('click', () => {
                            statusRuteModal.show();
                            let action = "add";
                            modalTitle.innerHTML = `Status macet di rute ${line.id}`
                            fetchData(`${BASE_URL}status_Rute/get_by_rute_id/${line.id}`).then(listJam=>{
                                if (listJam.length > 0) {
                                    for (let i = 0; i < listJam.length; i++) {
                                        status[i].value = listJam[i].status;
                                    }
                                    action = "update";
                                } else {
                                    for (let i = 0; i < status.length; i++) {
                                        status[i].value = "sepi";
                                    }
                                    action = "add";
                                }
                            });
                            modalBtn.addEventListener('click', async() => {
                                let data = [];
                                for(let i = 0; i < status.length; i++) {
                                    data.push({
                                        hari: hari[Math.floor(i/15)],
                                        jam: (i%15)+6,
                                        rute_id: line.id,
                                        status: status[i].value
                                    })
                                }
                                const result = await postData(`${BASE_URL}status_Rute/${action}`,data);
                                alert(result.message);
                            })
                        });
                    }
                });
            })
        }

        const reloadPoint = () => {
            objekWisata = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}wisata/all`).then(data=>{
                if (data.status == "success") {
                    data.data.forEach(wisata => {
                        const marker = L.marker([wisata.lat_coord,wisata.long_coord]).addTo(objekWisata);
                        marker.id = wisata.id;
                        marker.nama = wisata.nama
                        marker.gambar = wisata.gambar;
                        marker.alamat = wisata.alamat;
                        marker.jam_buka = wisata.jam_buka;
                        marker.no_telp = wisata.no_telp;
                        marker.kategori = wisata.kategori;

                        marker.addEventListener('click',() => {
                            SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            <img src="${BASE_URL}${marker.gambar}" class="img-fluid" />
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${marker.jam_buka ? marker.jam_buka : "tidak ada"}</p>
                            <p>No Telp: ${marker.no_telp ? marker.no_telp : "tidak ada"}</p>
                            <p>Kategori: ${marker.kategori}</p>
                            `;
                        })
                    });
                }
            });
        }
        
        reloadPoint();
        reloadLine();
    } else if (table == "wisata") {
        const lokasiModal = new bootstrap.Modal(document.getElementById("lokasiModal"), {
            keyboard: false
        })
        const modalBtn = document.getElementById('modal-btn');
        const pilihLokasi = document.getElementById('pilih-lokasi');
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        let marker = null;
        pilihLokasi.addEventListener('click', () => {
            lokasiModal.show();
            setTimeout(() => {
                map.invalidateSize();
            },300);
            
        })
        map.addEventListener('click', (e) => {
            const coord = e.latlng;
            if (marker != null) {
                marker.remove();
            }
            marker = L.marker([coord.lat, coord.lng]).addTo(map);
            latitude.value = coord.lat;
            longitude.value = coord.lng;
        })
        modalBtn.addEventListener('click',() => {
            lokasiModal.hide();
        })
    }
}
