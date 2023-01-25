<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;


class LevelsController extends Controller
{
    public function getAll()
    {
        $levels = Level::all();
        return ApiResponseController::response('Consulta Exitosa', 200, $levels);
    }
}
