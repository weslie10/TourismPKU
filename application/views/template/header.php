<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TourismPKU - <?= $title ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/leaflet/leaflet.css" />

</head>

<body id="page-top">
    <div id="base-url" class="d-none"><?= base_url() ?></div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('wisata') ?>">
                <div class="sidebar-brand-text mx-3">TourismPKU</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item <?= ($active == "wisata") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('wisata') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Wisata</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "kategori") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('kategori') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Kategori</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "kecamatan") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('kecamatan') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Kecamatan</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "kelurahan") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('kelurahan') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Kelurahan</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "titik_rute") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('titik_Rute') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Titik Rute</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "rute") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('rute') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Rute</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "status_rute") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('status_Rute') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel Status Rute</span>
                </a>
            </li>

            <li class="nav-item <?= ($active == "map") ? "active" : "" ?>">
                <a class="nav-link" href="<?= site_url('map') ?>">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Maps</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">admin</span>
                                <img class="img-profile rounded-circle" src="<?= base_url() ?>assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->