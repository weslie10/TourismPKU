<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function loadViews($self, $route, $data)
{
    $self->load->view('template/header', $data);
    $self->load->view($route);
    $self->load->view('template/footer');
}

function convertRad($deg)
{
    return $deg * (pi() / 180);
}

function getDist($lat1, $long1, $lat2, $long2)
{
    $R = 6371; // Radius of the earth in km
    $dLat = convertRad($lat2 - $lat1);  // deg2rad below
    $dLon = convertRad($long2 - $long1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(convertRad($lat1)) * cos(convertRad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c; // Distance in km
    return $d;
}

function convertDist($radius)
{
    return 0.008994 * $radius;
}
