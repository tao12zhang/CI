<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

    }

	public function index()
	{

        $this->load->view('zt/welcome_message');
	}
}
