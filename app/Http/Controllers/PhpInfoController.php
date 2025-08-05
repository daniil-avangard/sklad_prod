<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhpInfoController extends Controller
{
    public function index()
    {
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();

        // Optionally, you can clean up the output if needed
        // $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);

        return response($pinfo);
    }
}
