<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = file_get_contents(env('API_PATH')."hris-api.php");
        
        return $employees ;
    }
}
