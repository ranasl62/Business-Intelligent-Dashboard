<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PGW;
use App\Log;
class PGWInvoiceController extends Controller
{
	use Log;
    public function pgwinvoice(){
        return PGW::all();
    }
    public function index(){
        $this->log('PGW Invoice','PGW Invoice ','PGW');
        $data['title']="PGW Invoice";
        return view('pgw.pgwinvoice',compact('data'));
    }
}
