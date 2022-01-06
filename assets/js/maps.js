let table = document.getElementById('table');
if (table) {
    table = table.innerHTML;
}
const BASE_URL = document.getElementById('base-url').innerText;

const markerHtmlStyles = `
  width: 1rem;
  height: 1rem;
  display: block;
  left: -0.5rem;
  top: -0.5rem;
  position: relative;
  border-radius: 1rem 1rem 0;
  transform: rotate(45deg);
  border: 1px solid #FFFFFF`

const newIcon = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 6],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -36],
  html: `<span style="background-color: red; ${markerHtmlStyles};" />`
})
const newIcon2 = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 6],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -36],
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
            fetchData(`${BASE_URL}/rute/all`).then(data=>{
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
                    point = point.filter((item)=> item.lat !== markerCoord.lat && item.long !== markerCoord.lng);
                    marker.remove();
                    hapus.remove();
                })
            })
        });

        const tambahTitik = document.getElementById('tambahTitik');
        tambahTitik.addEventListener('click', async () => {
            const result = await postData(`${BASE_URL}titik_rute/add`,point);
            if (result.status) {
                point = [];
                map.removeLayer(markerGroup);
                reloadMap();
                alert(result.message);
            } else {
                alert(result.message)
            }
            
        });
    } else if (table == "rute") {
        const SideBar = document.getElementById('side-bar');
        let lineGroup = L.layerGroup().addTo(map);
        let lines = [];
        let titik_awal = {}, titik_akhir = {};
        let flag = true;
        SideBar.innerHTML="Silahkan pilih titik pertama";

        const reloadLine = () => {
            lineGroup = L.layerGroup().addTo(map);
            fetchData(`${BASE_URL}rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, titik_awal, titik_akhir, lat_awal, long_awal, lat_akhir, long_akhir} = titik;
                    const line = L.polyline([
                        [lat_awal, long_awal],
                        [lat_akhir, long_akhir]
                    ], {color: 'red'}).addTo(lineGroup);
                    line.id = id;
                    line.titik_awal = titik_awal;
                    line.titik_akhir = titik_akhir;

                    line.addEventListener('click', () => {
                        console.log(line.id);
                    })
                });
            })
        }

        const reloadPoint = () => {
            markerGroup = L.layerGroup().addTo(map)
            fetchData(`${BASE_URL}titik_rute/all`).then(data=>{
                data.forEach(titik => {
                    const {id, lat_coord, long_coord} = titik;
                    const marker = L.marker([lat_coord, long_coord], {icon: newIcon}).addTo(markerGroup);
                    marker.id = id;

                    marker.addEventListener('click',() => {
                        const coord = marker.getLatLng()
                        if (flag) {
                            titik_awal = {
                                id: marker.id,
                                lat: coord.lat,
                                long: coord.lng,
                            };
                            flag = !flag;
                            SideBar.innerHTML="Silahkan pilih titik kedua";
                        } else {
                            titik_akhir = {
                                id: marker.id,
                                lat: coord.lat,
                                long: coord.lng,
                            };
                            flag = !flag;
                            lines.push({
                                titik_awal: titik_awal, 
                                titik_akhir: titik_akhir
                            });

                            const line = L.polyline([
                                [titik_awal.lat, titik_awal.long],
                                [titik_akhir.lat, titik_akhir.long]
                            ], {color: 'blue'}).addTo(lineGroup);
                            
                            titik_awal = {};
                            titik_akhir = {};

                            line.addEventListener('click', ()=>{
                                SideBar.innerHTML = `<h1>${marker.id}</h1><button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
                                const hapus = document.getElementById('hapus');
                
                                hapus.addEventListener('click',async () => {
                                    line.remove();
                                    hapus.remove();
                                    SideBar.innerHTML = "";
                                    // await fetchData(`${BASE_URL}/titik_rute/delete/${marker.id}`);
                                })
                            })
                            SideBar.innerHTML="Silahkan pilih titik pertama";
                        }
                    })
                });
            });
            fetchData(`${BASE_URL}rute/all`).then(data=>{
                // data.forEach(titik => {
                    // const {id, lat_coord, long_coord} = titik;
                    // const marker = L.marker([lat_coord, long_coord], {icon: newIcon}).addTo(markerGroup);
                    // marker.id = id;

                    // marker.addEventListener('click',() => {
                    //     SideBar.innerHTML = `<h1>${marker.id}</h1><button id="hapus" class="btn btn-primary">Hapus titik ini</button>`;
                    //     const hapus = document.getElementById('hapus');
        
                    //     hapus.addEventListener('click',async () => {
                    //         marker.remove();
                    //         hapus.remove();
                    //         SideBar.innerHTML = "";
                    //         await fetchData(`${BASE_URL}/titik_rute/delete/${marker.id}`);
                    //     })
                    // })
                // });
            });
        }
        
        reloadPoint();
        reloadLine();

        const tambahRute = document.getElementById('tambahRute');
        tambahRute.addEventListener('click', async () => {
            const result = await postData(`${BASE_URL}rute/add`,lines);
            if (result.status) {
                lines = [];
                map.removeLayer(lineGroup);
                reloadLine();
                alert(result.message);
            } else {
                alert(result.message)
            }
        });
    }
}
