<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function loadViews($self, $route, $data)
{
    $self->load->view('template/header', $data);
    $self->load->view($route);
    $self->load->view('template/footer');
}
