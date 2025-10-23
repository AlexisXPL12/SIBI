<?php
class vistaModelo
{
    protected static function obtener_vista($vista)
    {

        $palabras_permitidas_n1 = ['inicio','categorias','nueva-categoria','carreras','nueva-carrera', 'usuarios', 'nuevo-usuario', 'instituciones', 'nueva-institucion', 'ambientes', 'nuevo-ambiente', 'bienes',  'nuevo-bien', 'movimientos', 'nuevo-movimiento', 'clientes-api', 'nuevo-cont-request','cont-request', 'nuevo-token','tokens', 'nuevo-cliente-api','reporte-movimiento','imprimir-movimiento','reporte-bienes', 'imprimir-bienes','reporte-carrera', 'imprimir-carrera','reporte-usuarios', 'imprimir-usuarios','reporte-ambientes', 'imprimir-ambientes','reporte-categorias', 'imprimir-categorias','imprimir-movimientosg','actualizar-ambiente','actualizar-bien','actualizar-carreras','actualizar-categorias','actualizar-cliente','actualizar-cont','actualizar-usuario','actualizar-token','api-bienes'];

        if (in_array($vista, $palabras_permitidas_n1)) {

            if (is_file("./src/view/" . $vista . ".php")) {
                $contenido = "./src/view/" . $vista . ".php";
            } else {
                $contenido = "404";
            }
        } elseif ($vista == "inicio" || $vista == "index") {
            $contenido = "inicio.php";
        } elseif ($vista == "login") {
            $contenido = "login";
        }elseif ($vista == "reset-password") {
            $contenido = "reset-password";
        }
        else {
            $contenido = "404";
        }

        return $contenido;
    }
}
