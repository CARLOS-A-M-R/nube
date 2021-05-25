<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\File;

class ArticuloController extends Controller
{
    public function obtenerArticulos() {
        $datos = Articulo::all();

        return response()->json($datos);
    }

    public function urlImagen() {
        $imagenes = [];
        set_time_limit(0);
        $datos = DB::table('imagenes')
        ->join('articulos', 'referencia_imagen', '=', 'cod_articulo')
        ->select('imagenes.url_imagen', 'imagenes.referencia_imagen')     
        ->get();

    echo 'Advetencia: Esta acción puede demorar tiempo, más o menos como una actualización de windows.';

    Image::truncate();

    foreach ($datos as $valor) {
        $arrayImagenes = array(
            "url" => $valor->url_imagen,
            "referencia" => $valor->referencia_imagen
        );

        if($this->url_exists($arrayImagenes['url'])){
            $imagen = new Image;
            $imagen->url_imagen = $arrayImagenes['url'];
            $imagen->referencia_imagen = $arrayImagenes['referencia'];
            $imagen->save();

        }
    }

    
    if ($imagen) {
        return '¡Imagenes comprobadas desde servidor con exito!';
    } else {
        return '¡Ocurrio un error en la comprobación de imagenes!';
    }

}


    public function url_exists($url) 
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        return ($code === 200); // verifica se recebe "status OK"
    }

    public function mostrarImagenes() 
    {
        $datos = DB::table('images')
        ->join('articulos', 'referencia_imagen', '=', 'cod_articulo')
        ->select('articulos.cod_articulo','articulos.descripcion_articulo','articulos.prov_habitual_articulo',
                    'articulos.referencia_articulo','articulos.precio_articulo', 'articulos.familia_articulo', 'articulos.unidad_palet_articulo',
                'images.url_imagen', 'images.referencia_imagen')
        ->get();

        return response()->json($datos);
    }

 
}
