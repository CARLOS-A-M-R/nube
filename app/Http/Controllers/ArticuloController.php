<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function obtenerArticulos() {
        $datos = Articulo::all();

        return response()->json($datos);
    }

    public function urlImagen() {
        $datos = DB::table('imagenes')
        ->join('articulos', 'referencia_imagen', '=', 'cod_articulo')
        /* ->select('articulos.cod_articulo','articulos.descripcion_articulo','articulos.prov_habitual_articulo',
                    'articulos.referencia_articulo','articulos.precio_articulo', 'articulos.familia_articulo', 'articulos.unidad_palet_articulo',
                'imagenes.url_imagen', 'imagenes.referencia_imagen')
        ->get(); */
        ->select('imagenes.url_imagen', 'imagenes.referencia_imagen', 'articulos.descripcion_articulo',
                'articulos.referencia_articulo', 'articulos.unidad_palet_articulo', 'articulos.precio_articulo')
                ->get();        

        return response()->json($datos);
    }
}
