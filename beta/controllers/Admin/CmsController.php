<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;
use Response;

class CmsController extends Controller
{

	public function index(){
        return view('admin.cms.index');
    }
}