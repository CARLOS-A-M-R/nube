<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;

class ArticuloController extends Controller
{
    public function obtenerArticulos() {
        $datos = Articulo::all();

        return response()->json($datos);
    }
}