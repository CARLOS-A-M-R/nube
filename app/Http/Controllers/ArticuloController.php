<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ArticuloController extends Controller
{
    //Mostrar Articulos en un url para su consumo en la API
    public function obtenerArticulos() {
        //Variable que establece los datos de articulos
        $datos = Articulo::all();

        //Respuesta en formato JSON para su lectura
        return response()->json($datos);
    }

    //Determinamos todas las imagenes con su direccion remota url
    public function urlImagen() {
        /*Esta función puede tardar en su ejecución, tiene que validar si una imagen en realidad existente en el servidor
        y por ello declaramos que no tenga limite de tiempo para trabajar
        */
        set_time_limit(0);
        //Consultamos con QUERY BUILDER una tabla de la base datos
        $datos = DB::table('imagenes')
        //Realizamos la relacion de la tabla imagenes y articulos
        ->join('articulos', 'referencia_imagen', '=', 'cod_articulo')
        //Seleccionamos registros que queremos que aparezcan
        ->select('imagenes.url_imagen', 'imagenes.referencia_imagen')
        //Obtenemos el resultado    
        ->get();
    //Advertencia para ecordar que el proceso tardara un poco 
    echo 'Advetencia: Esta acción puede demorar tiempo, más o menos como una actualización de windows. C:   ';

    //Antes de guardar las imagenes a la base datos borramos informacion antigua
    Image::truncate();

    //Realizamos un ciclo para almacenar la información en un array
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
    
        return ($code === 200); // verifica     si recibi "status OK"
    }       

    public function mostrarImagenes() 
    {   
        $datos = DB::table('images')
        ->join('articulos', 'referencia_imagen', '=', 'cod_articulo')
        ->select('articulos.cod_articulo','articulos.descripcion_articulo','articulos.prov_habitual_articulo',
                    'articulos.referencia_articulo','articulos.precio_articulo', 'articulos.familia_articulo', 'articulos.unidad_palet_articulo',
                'images.url_imagen', 'images.referencia_imagen', 'images.id', 
                'articulos.observaciones_articulo','articulos.msj_emergente_articulo')
        ->get();

        return response()->json($datos);
    }

    public function mostrarStock () 
    {
        $stock = DB::table('stock')->join('images', 'cod_art_stock', '=', 'referencia_imagen')
        ->select('stock.cod_art_stock','stock.almacen_stock','stock.actual_stock','stock.disponible_stock')
        ->get();

        return response()->json($stock);
    }
}
