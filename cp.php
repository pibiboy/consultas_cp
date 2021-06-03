<?php

header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);

//conexion a la BD
$conn = mysqli_connect(
    'localhost',
    'root',
    '',
    'consultas_cp',
    '3306'
) or die("Error de coneccion.");

mysqli_set_charset($conn, "utf8");

//variable que recibe el CP de la URL
//http://localhost/consultas/cp.php?cp=97217
$cp = $_GET["cp"];

$info = array();
$data = array();

//mensajes de error
//no se recibio un CP por la URL
if($cp == null){
    $info[] = array(
        'error'=>true,
        'code_error'=>1,
        'error_message'=>'No se recibio el cp',
        'response'=>null
    );

    $json = json_encode($info);

    echo "<pre>"; 
    echo json_encode(json_decode($json), JSON_PRETTY_PRINT); 
    echo "</pre>";

    die();
}

//numero de caracteres invalido
else if(strlen($cp) != 5){
    $info[] = array(
        'error'=>true,
        'code_error'=>2,
        'error_message'=>'La longitud del cp es invalida',
        'response'=>null
    );

    $json = json_encode($info);

    echo "<pre>"; 
    echo json_encode(json_decode($json), JSON_PRETTY_PRINT); 
    echo "</pre>";

    die();
}

//consulta a la BD donde obtiene los resultados del CP
$sql = 'SELECT * FROM cp WHERE d_codigo = ' . $cp;

if(!$result = mysqli_query($conn, $sql)) die();

//se insertan los resultados de la consulta en el array
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    
    $info[] = array(
        'error'=>false,
        'code_error'=>0,
        'error_message'=>null,
        'response'=>    $data[] = array(
            'cp'=>$row['d_codigo'], //cp
            'colonia'=>$row['d_asenta'],//col
            //'d_tipo_asenta'=>$row['d_tipo_asenta'],
            'estado'=>$row['d_estado'],//est
            'municipio'=>$row['D_mnpio'],//muni
            'ciudad'=>$row['d_ciudad'],//ciu
            /*'d_CP'=>$row['d_CP'],
            'c_estado'=>$row['c_estado'],
            'c_oficina'=>$row['c_oficina'],
            'c_tipo_asenta'=>$row['c_tipo_asenta'],
            'c_mnpio'=>$row['c_mnpio'],
            'id_asenta_cpcons'=>$row['id_asenta_cpcons'],
            'd_zona'=>$row['d_zona'],
            'c_cve_ciudad'=>$row['c_cve_ciudad']*/
        )
    );

}

//CP no encontrado en la BD
if(empty($info)){
    $info[] = array(
        'error'=>true,
        'code_error'=>3,
        'error_message'=>'No se encontro el cp',
        'response'=>null
    );

    $json = json_encode($info);

    echo "<pre>"; 
    echo json_encode(json_decode($json), JSON_PRETTY_PRINT); 
    echo "</pre>";

    die();
}

$close = mysqli_close($conn);

//convierte los resultados en json
$json = json_encode($info);

//formato vertical
echo "<pre>"; 
echo json_encode(json_decode($json), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 
echo "</pre>";