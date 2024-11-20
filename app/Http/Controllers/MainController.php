<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        // load appdata file from app folder
        // Estou dizendo que dentro da past app, vou buscar esse arquivo
        $this->app_data = require(app_path('AppData.php'));
    }

    public function showData() {
        return response()->json($this->app_data);
    }
}
