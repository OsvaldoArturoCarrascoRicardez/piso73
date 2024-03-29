<?php

function fooBarBazBaz(): string
{
    return 'FooBarBazBaz';
}

// funciones para usar con formatFechaLarga
function getDia(int $index): string
{
    // el indice debe ser entre 0 y 6
    $listaDias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    return $listaDias[$index];
}

function getMes(int $index): string
{
    // el indice debe ser entre 0 y 11
    $listMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $listMonths[$index];
}

function getMesCorto(int $index): string
{
    // el indice debe ser entre 0 y 11
    $listaMeses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    return $listaMeses[$index];
}

function formatFechaLarga(int $fecha_)
{
    // Show:  Miercoles 02 de Febrero del 2022
    return '' . getDia(date('w', $fecha_)) . " " . date('d', $fecha_) . " de " . getMes(date('n', $fecha_) - 1) . " de " . date('Y', $fecha_);
}

function formatFechaDMESA(int $fecha_)
{
    // Show:  07 de Enero de 2022
    return '' . date('d', $fecha_) . " de " . getMes(date('n', $fecha_) - 1) . " de " . date('Y', $fecha_);
}

function formatFechaCortaM3(int $fecha_)
{
    // Show:  07 de Enero de 2022
    return '' . date('d', $fecha_) . "-" . getMesCorto(date('n', $fecha_) - 1) . "-" . date('Y', $fecha_);
}

function formatFecha_d_Mes_a(int $fecha_)
{
    // Show:  07 Enero 2022
    return '' . date('d', $fecha_) . " " . getMes(date('n', $fecha_) - 1) . " " . date('Y', $fecha_);
}

function getListNameDaysDesc()
{
    $weekDays = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");

    $lista = array();
    $lista[] = $weekDays[date('w', strtotime(' 0 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -1 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -2 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -3 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -4 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -5 day'))];
    $lista[] = $weekDays[date('w', strtotime(' -6 day'))];

    return $lista;
}

/* Lista de los meses del actual al anterior para gráficas*/
function getListMonthYearDesc()
{

    // el índice debe ser entre 0 y 11
    $listaMeses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    $lista = array();

    for ($i = 0; $i < 12; $i++) {
        $lista[] = $listaMeses[date('n', strtotime(' -' . $i . ' month')) - 1];
    }

    return $lista;
}

// tomada de w3
function test_input($data_)
{
    $dato = trim($data_);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

function format_peso($value)
{
    return '$' . number_format($value, 2);
}

function format_numero($value)
{
    return number_format($value, 2);
}

function agrupa_por_prod($data)
{
    $groups = array();
    foreach ($data as $item) {

        if ($item->is_customize !== null && strtolower($item->is_customize) === 'open') {
            $item->product_id = 999999999;
        }

        $key = $item->product_id;

        if (!array_key_exists($key, $groups)) {
            $groups[$key] = array(
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'horas' => array(),
                /* json de hora */
                'product_name' => '',

            );
            $groups[$key]['horas'][] = $item->hora_consumo;
        } else {
            $groups[$key]['quantity'] = $groups[$key]['quantity'] + $item->quantity;
            $groups[$key]['horas'][] = $item->hora_consumo;
        }
    }
    return $groups;
}

function getDateWiseScore($data)
{
    $groups = array();
    foreach ($data as $item) {
        $key = $item['evaluation_category_id'];
        if (!array_key_exists($key, $groups)) {
            $groups[$key] = array(
                'id' => $item['evaluation_category_id'],
                'score' => $item['score'],
                'itemMaxPoint' => $item['itemMaxPoint'],
            );
        } else {
            $groups[$key]['score'] = $groups[$key]['score'] + $item['score'];
            $groups[$key]['itemMaxPoint'] = $groups[$key]['itemMaxPoint'] + $item['itemMaxPoint'];
        }
    }
    return $groups;
}




