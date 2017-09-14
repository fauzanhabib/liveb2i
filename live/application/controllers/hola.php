<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class hola extends MY_Site_Controller {

	function index(){
        echo $_SERVER['SERVER_NAME'];
	}

}
