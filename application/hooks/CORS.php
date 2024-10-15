<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CORS {

    public function set_headers() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
    }
}
