<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{
    public function home()
    {
        $this->load->view('pages/Home');
    }

    public function products()
    {
        $this->load->view('pages/Products');
    }

    public function about()
    {
        $this->load->view('pages/AboutUs');
    }

    public function about_more()
    {
        $this->load->view('pages/AboutUsMore');
    }

    public function tradables()
    {
        $this->load->view('pages/Tradables');
    }

    public function trade_now()
    {
        $this->load->view('pages/TradeNow');
    }

    public function trading()
    {
        $this->load->view('pages/Trading');
    }

    public function ordering()
    {
        $this->load->view('pages/Ordering');
    }

    public function notification()
    {
        $this->load->view('pages/Notification');
    }

    public function order_notification()
    {
        $this->load->view('pages/OrderNotification');
    }

    public function news()
    {
        $this->load->view('pages/News');
    }

    public function contact()
    {
        $this->load->view('pages/Contact');
    }

    public function test()
    {
        $this->load->view('pages/test');
    }
}
