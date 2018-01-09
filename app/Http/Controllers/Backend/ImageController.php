<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{

    public function upload( Request $request )
    {
        return upload( $request );
    }
}
