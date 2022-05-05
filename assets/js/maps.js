let table = document.getElementById("table");
if (table) {
	table = table.innerHTML;
}
const BASE_URL = document.getElementById("base-url").innerText;

const markerHtmlStyles = `
  width: 5px;
  height: 5px;
  display: block;
  left: -2.5px;
  top: -2.5px;
  position: relative;
  border-radius: 50%;
  border: 1px solid #FFFFFF`;

const newIcon = L.divIcon({
	className: "my-custom-pin",
	iconAnchor: [0, 3],
	labelAnchor: [-3, 0],
	popupAnchor: [0, -18],
	html: `<span style="background-color: green; ${markerHtmlStyles};" />`,
});
const newIcon2 = L.divIcon({
	className: "my-custom-pin",
	iconAnchor: [0, 3],
	labelAnchor: [-3, 0],
	popupAnchor: [0, -18],
	html: `<span style="background-color: blue; ${markerHtmlStyles};" />`,
});

const fetchData = async (url) => {
	const response = await fetch(url);
	const data = await response.json();
	return new Promise((resolve, reject) => {
		resolve(data);
	});
};
const postData = async (url, data) => {
	const response = await fetch(url, {
		method: "POST",
		headers: {
			Accept: "application/json",
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	});
	const dataResponse = await response.json();
	return new Promise((resolve, reject) => {
		resolve(dataResponse);
	});
};

if (document.getElementById("map")) {
	// let map = L.map("map").setView([0.501947, 101.443751], 12);
	L.mapbox.accessToken =
		"pk.eyJ1Ijoid2VzbGllMTAiLCJhIjoiY2t5MTBydnV2MDZ5YTJ3bXI1anRlNnEyZyJ9.LK5NkKV6Yq3lTLBVuE_bzg";
	const map = L.mapbox
		.map("map")
		.setView([0.501947, 101.443751], 12)
		.addLayer(L.mapbox.styleLayer("mapbox://styles/mapbox/streets-v11"));

	if (table == "titik_rute") {
		const SideBar = document.getElementById("side-bar");
		const loading = document.getElementById("loading");
		let markerGroup = L.layerGroup().addTo(map),
			objekWisata = L.layerGroup().addTo(map),
			lineGroup = L.layerGroup().addTo(map);

		const reloadPoint = () => {
			markerGroup = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}titik_Rute/all`).then((data) => {
				data.forEach((titik) => {
					const { id, lat_coord, long_coord } = titik;
					const marker = L.marker([lat_coord, long_coord], {
						icon: newIcon,
					}).addTo(markerGroup);
					marker.id = id;

					marker.addEventListener("click", () => {
						SideBar.innerHTML = `<h1>${marker.id}</h1><button id="hapus" class="btn btn-primary">Hapus titik ini</button>`;
						const hapus = document.getElementById("hapus");

						hapus.addEventListener("click", async () => {
							marker.remove();
							hapus.remove();
							SideBar.innerHTML = "";
							await fetchData(`${BASE_URL}titik_Rute/delete/${marker.id}`);
						});
					});
				});
			});
			objekWisata = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}wisata/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((wisata) => {
						const marker = L.marker([
							wisata.lat_coord,
							wisata.long_coord,
						]).addTo(objekWisata);
						marker.id = wisata.id;
						marker.nama = wisata.nama;
						marker.gambar = wisata.gambar;
						marker.alamat = wisata.alamat;
						marker.jam_buka = wisata.jam_buka;
						marker.no_telp = wisata.no_telp;
						marker.kategori = wisata.kategori;

						marker.addEventListener("click", () => {
							SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            <img src="${BASE_URL}${
								marker.gambar
							}" class="img-fluid" />
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${
															marker.jam_buka ? marker.jam_buka : "tidak ada"
														}</p>
                            <p>No Telp: ${
															marker.no_telp ? marker.no_telp : "tidak ada"
														}</p>
                            <p>Kategori: ${marker.kategori}</p>
                            `;
						});
					});
				}
			});
		};

		const reloadLine = () => {
			lineGroup = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}rute/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((titik) => {
						const {
							id,
							titik_awal,
							titik_akhir,
							lat_awal,
							id_kedua,
							long_awal,
							lat_akhir,
							long_akhir,
						} = titik;
						if (id_kedua != null) {
							const line = L.polyline(
								[
									[lat_awal, long_awal],
									[lat_akhir, long_akhir],
								],
								{ color: "grey" }
							).addTo(lineGroup);
							line.id = id;
							line.titik_awal = titik_awal;
							line.titik_akhir = titik_akhir;
							line.id_kedua = id_kedua;
						} else {
							const line = L.polyline(
								[
									[lat_awal, long_awal],
									[lat_akhir, long_akhir],
								],
								{ color: "red" }
							)
								.arrowheads({ size: "5px" })
								.addTo(lineGroup);
							line.id = id;
							line.titik_awal = titik_awal;
							line.titik_akhir = titik_akhir;
						}
					});
				}
			});
		};

		let point = [];
		reloadPoint();
		reloadLine();

		map.addEventListener("click", (e) => {
			SideBar.innerHTML = "";
			const coord = e.latlng;
			const marker = L.marker([coord.lat, coord.lng], { icon: newIcon2 }).addTo(
				markerGroup
			);
			point.push({ lat: coord.lat, long: coord.lng });

			marker.addEventListener("click", () => {
				SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus titik ini</button>`;
				const hapus = document.getElementById("hapus");

				hapus.addEventListener("click", () => {
					const markerCoord = marker.getLatLng();
					point = point.filter(
						(item) =>
							item.lat !== markerCoord.lat && item.long !== markerCoord.lng
					);
					marker.remove();
					hapus.remove();
				});
			});
		});

		const tambahTitik = document.getElementById("tambahTitik");
		tambahTitik.addEventListener("click", async () => {
			if (point.length > 0) {
				loading.style.display = "inline-block";
				tambahTitik.disabled = true;
				const result = await postData(`${BASE_URL}titik_Rute/add`, point);
				if (result.status) {
					point = [];
					map.removeLayer(markerGroup);
					reloadPoint();
					alert(result.message);
				} else {
					alert(result.message);
				}
				tambahTitik.disabled = false;
				loading.style.display = "none";
			} else {
				alert("Belum ada titik rute");
			}
		});
	} else if (table == "rute") {
		const SideBar = document.getElementById("side-bar");
		const loading = document.getElementById("loading");
		const resetTitik = document.getElementById("reset-titik");

		let markerGroup = L.layerGroup().addTo(map),
			lineGroup = L.layerGroup().addTo(map),
			circleGroup = L.layerGroup().addTo(map),
			objekWisata = L.layerGroup().addTo(map);
		let titik_awal = {},
			titik_akhir = {};
		let lines = [];
		let flag = true;

		const reset = () => {
			titik_awal = {};
			titik_akhir = {};
			flag = true;
			SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik pertama</h1>`;
			map.removeLayer(circleGroup);
			circleGroup = L.layerGroup().addTo(map);
		};

		let way = "one-way";
		const oneWay = document.getElementById("one-way");
		const twoWay = document.getElementById("two-way");
		oneWay.addEventListener("click", () => {
			way = oneWay.value;
			reset();
		});
		twoWay.addEventListener("click", () => {
			way = twoWay.value;
			reset();
		});

		SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik pertama</h1>`;
		resetTitik.addEventListener("click", () => {
			reset();
		});

		const reloadLine = () => {
			lineGroup = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}rute/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((titik) => {
						const {
							id,
							titik_awal,
							titik_akhir,
							lat_awal,
							id_kedua,
							long_awal,
							lat_akhir,
							long_akhir,
						} = titik;
						if (id_kedua != null) {
							const line = L.polyline(
								[
									[lat_awal, long_awal],
									[lat_akhir, long_akhir],
								],
								{ color: "grey" }
							).addTo(lineGroup);
							line.id = id;
							line.titik_awal = titik_awal;
							line.titik_akhir = titik_akhir;
							line.id_kedua = id_kedua;
							line.addEventListener("click", () => {
								SideBar.innerHTML = `<h1>${line.id} dan ${line.id_kedua}</h1><br><button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
								const hapus = document.getElementById("hapus");

								hapus.addEventListener("click", async () => {
									line.remove();
									hapus.remove();
									SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${
										flag ? "pertama" : "kedua"
									}</h1>`;
									await fetchData(`${BASE_URL}rute/delete/${line.id}`);
									await fetchData(`${BASE_URL}rute/delete/${line.id_kedua}`);
								});
							});
						} else {
							const line = L.polyline(
								[
									[lat_awal, long_awal],
									[lat_akhir, long_akhir],
								],
								{ color: "red" }
							)
								.arrowheads({ size: "5px" })
								.addTo(lineGroup);
							line.id = id;
							line.titik_awal = titik_awal;
							line.titik_akhir = titik_akhir;
							line.addEventListener("click", () => {
								SideBar.innerHTML = `<h1>${line.id}</h1><br><button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
								const hapus = document.getElementById("hapus");

								hapus.addEventListener("click", async () => {
									line.remove();
									hapus.remove();
									SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${
										flag ? "pertama" : "kedua"
									}</h1>`;
									await fetchData(`${BASE_URL}rute/delete/${line.id}`);
								});
							});
						}
					});
				}
			});
		};

		const reloadPoint = () => {
			markerGroup = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}titik_Rute/all`).then((data) => {
				data.forEach((titik) => {
					const { id, lat_coord, long_coord } = titik;
					const marker = L.marker([lat_coord, long_coord], {
						icon: newIcon,
					}).addTo(markerGroup);
					marker.id = id;

					marker.addEventListener("click", () => {
						const coord = marker.getLatLng();
						if (flag) {
							L.circle([coord.lat, coord.lng], {
								radius: 30,
								color: way == "one-way" ? "blue" : "green",
							}).addTo(circleGroup);
							titik_awal = {
								id: marker.id,
								lat: coord.lat,
								long: coord.lng,
							};
							flag = !flag;
							SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik kedua</h1>`;
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
										return (
											(el.titik_awal.id == titik_awal.id &&
												el.titik_akhir.id == titik_akhir.id) ||
											(el.titik_awal.id == titik_akhir.id &&
												el.titik_akhir.id == titik_awal.id)
										);
									});
									if (!check) {
										lines.push({
											titik_awal: titik_awal,
											titik_akhir: titik_akhir,
										});
										const line = L.polyline(
											[
												[titik_awal.lat, titik_awal.long],
												[titik_akhir.lat, titik_akhir.long],
											],
											{ color: "blue" }
										)
											.arrowheads({ size: "5px" })
											.addTo(lineGroup);
										line.titik_awal = titik_awal;
										line.titik_akhir = titik_akhir;

										line.addEventListener("click", () => {
											SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
											const hapus = document.getElementById("hapus");

											hapus.addEventListener("click", async () => {
												line.remove();
												hapus.remove();
												lines = lines.filter((el) => {
													return (
														el.titik_awal != line.titik_awal ||
														el.titik_akhir != line.titik_akhir
													);
												});
												SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${
													flag ? "pertama" : "kedua"
												}</h1>`;
											});
										});
									} else {
										alert("Silahkan cari rute lain");
										reset();
									}
								} else {
									const check = lines.some((el) => {
										return (
											el.titik_awal.id == titik_awal.id &&
											el.titik_akhir.id == titik_akhir.id
										);
									});
									if (!check) {
										lines.push(
											{
												titik_awal: titik_awal,
												titik_akhir: titik_akhir,
											},
											{
												titik_awal: titik_akhir,
												titik_akhir: titik_awal,
											}
										);
										const line = L.polyline(
											[
												[titik_awal.lat, titik_awal.long],
												[titik_akhir.lat, titik_akhir.long],
											],
											{ color: "green" }
										).addTo(lineGroup);
										line.titik_awal = titik_awal;
										line.titik_akhir = titik_akhir;

										line.addEventListener("click", () => {
											SideBar.innerHTML = `<button id="hapus" class="btn btn-primary">Hapus rute ini</button>`;
											const hapus = document.getElementById("hapus");

											hapus.addEventListener("click", async () => {
												line.remove();
												hapus.remove();
												lines = lines.filter((el) => {
													return (
														(el.titik_awal != line.titik_awal ||
															el.titik_akhir != line.titik_akhir) &&
														(el.titik_awal != line.titik_akhir ||
															el.titik_akhir != line.titik_awal)
													);
												});
												SideBar.innerHTML = `<h1 class="text-dark">Silahkan pilih titik ${
													flag ? "pertama" : "kedua"
												}</h1>`;
											});
										});
									} else {
										alert("Silahkan cari rute lain");
										reset();
									}
								}
								reset();
							} else {
								reset();
							}
						}
					});
				});
			});
			objekWisata = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}wisata/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((wisata) => {
						const marker = L.marker([
							wisata.lat_coord,
							wisata.long_coord,
						]).addTo(objekWisata);
						marker.id = wisata.id;
						marker.nama = wisata.nama;
						marker.gambar = wisata.gambar;
						marker.background = wisata.background;
						marker.alamat = wisata.alamat;
						marker.jam_buka = wisata.jam_buka;
						marker.no_telp = wisata.no_telp;
						marker.kategori = wisata.kategori;
						marker.rating = wisata.rating;

						marker.addEventListener("click", () => {
							let gambar = "";
							if (marker.gambar != 0) {
								gambar = `<img src="${BASE_URL}${marker.background}" class="img-fluid">`;
							} else {
								gambar = `<p>Tidak ada gambar</p>`;
							}

							SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            ${gambar}
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${
															marker.jam_buka ? marker.jam_buka : "tidak ada"
														}</p>
                            <p>No Telp: ${
															marker.no_telp ? marker.no_telp : "tidak ada"
														}</p>
                            <p>Kategori: ${marker.kategori}</p>`;
						});
					});
				}
			});
		};

		reloadPoint();
		reloadLine();

		const tambahRute = document.getElementById("tambahRute");
		tambahRute.addEventListener("click", async () => {
			if (lines.length > 0) {
				loading.style.display = "inline-block";
				tambahRute.disabled = true;
				const result = await postData(`${BASE_URL}rute/add`, lines);
				if (result.status) {
					lines = [];
					map.removeLayer(lineGroup);
					reloadLine();
					alert(result.message);
				} else {
					alert(result.message);
				}
				tambahRute.disabled = false;
				loading.style.display = "none";
				reset();
			} else {
				alert("Belum ada rute");
			}
		});
	} else if (table == "status_rute") {
		const senapelanPolygon = [
			[0.539276982046973, 101.4478611946106],
			[0.5386440087247439, 101.44771099090576],
			[0.5379681218843618, 101.44777536392212],
			[0.5377213695271613, 101.44753932952881],
			[0.5322069881682391, 101.44748568534851],
			[0.5321533463019431, 101.44251823425293],
			[0.5283125874648587, 101.44225001335144],
			[0.5284842415419105, 101.43427848815918],
			[0.5282804023248858, 101.43426775932312],
			[0.5283340442247557, 101.43296957015991],
			[0.5284198712635678, 101.43296957015991],
			[0.5283555009845636, 101.4276373386383],
			[0.5382470593196417, 101.42757296562195],
			[0.5407038538707912, 101.4281952381134],
			[0.5423667491821068, 101.42801284790039],
			[0.545241947738481, 101.42971873283386],
			[0.5445016915247004, 101.43184304237366],
			[0.5425062178003798, 101.43375277519226],
			[0.5412080866907861, 101.43740057945251],
			[0.5410578896849567, 101.44050121307373],
			[0.540886235959458, 101.44209980964659],
			[0.5405107434179897, 101.44238948822021],
			[0.5407574956621405, 101.44286155700684],
			[0.5395130059807914, 101.44555449485779],
			[0.5390945999100255, 101.44611239433289],
			[0.539223340242558, 101.44642353057861],
			[0.5391375133545041, 101.44667029380797],
		];

		const isPointInsidePolygon = (point, poly) => {
			let inside = false;
			let [x, y] = point;
			for (let ii = 0; ii < poly.getLatLngs().length; ii++) {
				let polyPoints = poly.getLatLngs()[ii];
				for (
					let i = 0, j = polyPoints.length - 1;
					i < polyPoints.length;
					j = i++
				) {
					let xi = polyPoints[i].lat,
						yi = polyPoints[i].lng;
					let xj = polyPoints[j].lat,
						yj = polyPoints[j].lng;

					let intersect =
						yi > y != yj > y && x < ((xj - xi) * (y - yi)) / (yj - yi) + xi;
					if (intersect) inside = !inside;
				}
			}

			return inside;
		};

		const SideBar = document.getElementById("side-bar");
		const statusRuteModal = new bootstrap.Modal(
			document.getElementById("statusRuteModal"),
			{
				keyboard: false,
			}
		);
		const hari = [
			"senin",
			"selasa",
			"rabu",
			"kamis",
			"jumat",
			"sabtu",
			"minggu",
		];
		const modalTitle = document.getElementById("modal-title");
		const modalBtn = document.getElementById("modal-btn");
		const status = document.getElementsByClassName("status");

		let objekWisata = L.layerGroup().addTo(map);

		const polygon = L.polygon(senapelanPolygon, {
			noClip: true,
			interactive: false,
		})
			.bringToBack()
			.addTo(map);

		const reloadLine = () => {
			const lineGroup = L.layerGroup().addTo(map);

			fetchData(`${BASE_URL}rute/all`).then((listData) => {
				if (listData.status == "success") {
					listData.data.forEach((titik) => {
						const { id, lat_awal, id_kedua, long_awal, lat_akhir, long_akhir } =
							titik;
						if (
							isPointInsidePolygon([lat_awal, long_awal], polygon) ||
							isPointInsidePolygon([lat_akhir, long_akhir], polygon)
						) {
							if (id_kedua != null) {
								const line = L.polyline(
									[
										[lat_awal, long_awal],
										[lat_akhir, long_akhir],
									],
									{ color: "grey" }
								).addTo(lineGroup);
								line.id = id;
								line.id_kedua = id_kedua;
								line.addEventListener("click", async () => {
									//SELECT rute_id, count(*) FROM `status_rute` group by rute_id

									statusRuteModal.show();
									let action = "add";
									let aksi = "Tambah";
									const listJam = await fetchData(
										`${BASE_URL}status_Rute/get_by_rute_id/${line.id}`
									);
									if (listJam.length > 0) {
										for (let i = 0; i < listJam.length; i++) {
											status[i].value = listJam[i].status;
										}
										action = "update";
										aksi = "Ubah";
									} else {
										for (let i = 0; i < status.length; i++) {
											status[i].value = "sepi";
										}
										action = "add";
										aksi = "Tambah";
									}
									let flag = false;
									modalTitle.innerHTML = `${aksi} status macet di rute ${line.id} dan ${line.id_kedua}`;
									modalBtn.addEventListener("click", async () => {
										let data = [];
										for (let i = 0; i < status.length; i++) {
											data.push({
												hari: hari[Math.floor(i / 15)],
												jam: (i % 15) + 6,
												rute_id: line.id,
												status: status[i].value,
											});
										}
										const data2 = data.map((el) => {
											return {
												hari: el.hari,
												jam: el.jam,
												rute_id: line.id_kedua,
												status: el.status,
											};
										});
										if (!flag) {
											await Promise.all([
												postData(`${BASE_URL}status_Rute/${action}`, data),
												postData(`${BASE_URL}status_Rute/${action}`, data2),
											]);
											flag = true;
										}

										statusRuteModal.hide();
									});
								});
							} else {
								const line = L.polyline(
									[
										[lat_awal, long_awal],
										[lat_akhir, long_akhir],
									],
									{ color: "red" }
								)
									.arrowheads({ size: "5px" })
									.addTo(lineGroup);
								line.id = id;
								line.addEventListener("click", async () => {
									statusRuteModal.show();
									let action = "add";
									let aksi = "Tambah";
									const listJam = await fetchData(
										`${BASE_URL}status_Rute/get_by_rute_id/${line.id}`
									);
									if (listJam.length > 0) {
										for (let i = 0; i < listJam.length; i++) {
											status[i].value = listJam[i].status;
										}
										action = "update";
										aksi = "Ubah";
									} else {
										for (let i = 0; i < status.length; i++) {
											status[i].value = "sepi";
										}
										action = "add";
										aksi = "Tambah";
									}
									let flag = false;
									modalTitle.innerHTML = `${aksi} status macet di rute ${line.id}`;
									modalBtn.addEventListener("click", async () => {
										let data = [];
										for (let i = 0; i < status.length; i++) {
											data.push({
												hari: hari[Math.floor(i / 15)],
												jam: (i % 15) + 6,
												rute_id: line.id,
												status: status[i].value,
											});
										}
										if (!flag) {
											await postData(`${BASE_URL}status_Rute/${action}`, data);
											flag = true;
										}

										statusRuteModal.hide();
									});
								});
							}
						}
					});
				}
			});
		};

		const reloadPoint = () => {
			objekWisata = L.layerGroup().addTo(map);
			fetchData(`${BASE_URL}wisata/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((wisata) => {
						const marker = L.marker([
							wisata.lat_coord,
							wisata.long_coord,
						]).addTo(objekWisata);
						marker.id = wisata.id;
						marker.nama = wisata.nama;
						marker.gambar = wisata.gambar;
						marker.background = wisata.background;
						marker.alamat = wisata.alamat;
						marker.jam_buka = wisata.jam_buka;
						marker.no_telp = wisata.no_telp;
						marker.kategori = wisata.nama_kategori;
						marker.rating = wisata.rating;

						marker.addEventListener("click", () => {
							let gambar = "";
							if (marker.gambar != 0) {
								gambar = `<img src="${BASE_URL}${marker.background}" class="img-fluid">`;
							} else {
								gambar = `<p>Tidak ada gambar</p>`;
							}

							SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            ${gambar}
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${
															marker.jam_buka ? marker.jam_buka : "tidak ada"
														}</p>
                            <p>No Telp: ${
															marker.no_telp ? marker.no_telp : "tidak ada"
														}</p>
                            <p>Kategori: ${marker.kategori}</p>`;
						});
					});
				}
			});
		};

		reloadPoint();
		reloadLine();
	} else if (table == "wisata") {
		const lokasiModal = new bootstrap.Modal(
			document.getElementById("lokasiModal"),
			{
				keyboard: false,
			}
		);
		const modalBtn = document.getElementById("modal-btn");
		const pilihLokasi = document.getElementById("pilih-lokasi");
		const latitude = document.getElementById("latitude");
		const longitude = document.getElementById("longitude");
		let marker = null;
		pilihLokasi.addEventListener("click", () => {
			lokasiModal.show();
			setTimeout(() => {
				map.invalidateSize();
			}, 300);
		});
		map.addEventListener("click", (e) => {
			const coord = e.latlng;
			if (marker != null) {
				marker.remove();
			}
			marker = L.marker([coord.lat, coord.lng]).addTo(map);
			latitude.value = coord.lat;
			longitude.value = coord.lng;
		});
		modalBtn.addEventListener("click", () => {
			lokasiModal.hide();
		});
	} else if (table == "map") {
		const SideBar = document.getElementById("side-bar");
		let objekWisata = L.layerGroup().addTo(map);
		let posisi = L.layerGroup().addTo(map);
		let lines = L.layerGroup().addTo(map);

		const reloadObjekWisata = () => {
			objekWisata = L.layerGroup().addTo(map);
			posisi = L.layerGroup().addTo(map);

			const initPosisi = [0.534155, 101.451561];

			const posisiSekarang = L.marker(initPosisi, {
				draggable: true,
			})
				.addTo(posisi)
				.bindPopup("This is my position")
				.openPopup();
			posisiSekarang._icon.style.filter = "hue-rotate(150deg)";

			document.getElementById("resetPosisi").addEventListener("click", () => {
				posisiSekarang.setLatLng(initPosisi);
				map.removeLayer(lines);
				lines = L.layerGroup().addTo(map);
			});

			fetchData(`${BASE_URL}wisata/all`).then((data) => {
				if (data.status == "success") {
					data.data.forEach((wisata) => {
						const marker = L.marker([
							wisata.lat_coord,
							wisata.long_coord,
						]).addTo(objekWisata);
						marker.id = wisata.id;
						marker.nama = wisata.nama;
						marker.gambar = wisata.gambar;
						marker.background = wisata.background;
						marker.alamat = wisata.alamat;
						marker.jam_buka = wisata.jam_buka;
						marker.no_telp = wisata.no_telp;
						marker.kategori = wisata.nama_kategori;
						marker.rating = wisata.rating;

						marker.addEventListener("click", () => {
							SideBar.innerHTML = `
                            <h6 class="text-dark">Id: ${marker.id}</h6>
                            <h6 class="text-dark">Nama: ${marker.nama}</h6>
                            <a href="${BASE_URL}gambar/list/${
								marker.id
							}" class="text-underline">Cek Gambar</a>
                            <p>Alamat: ${marker.alamat}</p>
                            <p>Jam Buka: ${
															marker.jam_buka ? marker.jam_buka : "tidak ada"
														}</p>
                            <p>No Telp: ${
															marker.no_telp ? marker.no_telp : "tidak ada"
														}</p>
                            <p>Kategori: ${marker.kategori}</p>
                            <br>
                            <button class="btn btn-primary rute" id="${
															marker.id
														}">Route</button>
                            <button class="btn btn-primary peta" id="${
															marker.id
														}">Peta Wisata</button>`;
							const rute = document.getElementsByClassName("rute");
							for (let i = 0; i < rute.length; i++) {
								rute[i].addEventListener("click", () => {
									map.removeLayer(lines);
									lines = L.layerGroup().addTo(map);
									fetchData(
										`${BASE_URL}map/rute/${posisiSekarang.getLatLng().lat}/${
											posisiSekarang.getLatLng().lng
										}/${rute[i].id}`
									).then((data) => {
										const color = ["red", "green", "blue"];
										for (let j = 0; j < data.length; j++) {
											const path = data[j].path.map((path) => [
												parseFloat(path.lat),
												parseFloat(path.long),
											]);
											L.polyline(path, { color: color[j] }).addTo(lines);
										}
									});
								});
							}
							const peta = document.getElementsByClassName("peta");
							for (let i = 0; i < peta.length; i++) {
								peta[i].addEventListener("click", () => {
									map.removeLayer(lines);
									lines = L.layerGroup().addTo(map);
									fetchData(
										`${BASE_URL}map/peta/${posisiSekarang.getLatLng().lat}/${
											posisiSekarang.getLatLng().lng
										}/${peta[i].id}`
									).then((data) => {
										console.log(data);
										for (let j = 0; j < data.length; j++) {
											const path = data[j].path.map((path) => [
												parseFloat(path.lat),
												parseFloat(path.long),
											]);
											L.polyline(path, {
												color: `#${Math.floor(
													Math.random() * 16777215
												).toString(16)}`,
											})
												.addTo(lines)
												.bindPopup(`Jalur ${j + 1}`);
										}
									});
								});
							}
						});
					});
				}
			});
		};

		reloadObjekWisata();
	}
}
