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
    const map = L.map('map').setView([0.501947, 101.443751], 12);

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

        const reloadMap = () => {
            markerGroup = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}titik_rute/all`).then(data=>{
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
                            await fetchData(`${BASE_URL}titik_rute/delete/${marker.id}`);
                        })
                    })
                });
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
                const result = await postData(`${BASE_URL}titik_rute/add`,point);
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
            circleGroup = L.layerGroup().addTo(map);
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
            fetchData(`${BASE_URL}titik_rute/all`).then(data=>{
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
    }
}
