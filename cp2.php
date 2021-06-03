<?php

header("Content-Type: text/html;charset=utf-8");
error_reporting(0);

//conexion a la BD
$conn = mysqli_connect(
    'localhost',
    'root',
    '',
    'consultas_cp',
    '3306'
) or die("Error de coneccion.");

//variable que recibe el CP de la URL
//http://localhost/consultas/cp.php?cp=97217
$cp = $_GET["cp"];

//consulta a la BD donde obtiene los resultados del CP
$sql = 'SELECT * FROM cp WHERE d_codigo = ' . $cp;

mysqli_set_charset($conn, "utf8");

if (!$result = mysqli_query($conn, $sql)) die();

//se insertan los resultados de la consulta en el array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        
    $data[] = array(
        'cp'=>$row['d_codigo'],
        'colonia'=>$row['d_asenta'],
        //'d_tipo_asenta'=>$row['d_tipo_asenta'],
        'D_mnpio'=>$row['D_mnpio'],
        'd_estado'=>$row['d_estado'],
        'd_ciudad'=>$row['d_ciudad'],
        /*'d_CP'=>$row['d_CP'],
        'c_estado'=>$row['c_estado'],
        'c_oficina'=>$row['c_oficina'],
        'c_tipo_asenta'=>$row['c_tipo_asenta'],
        'c_mnpio'=>$row['c_mnpio'],
        'id_asenta_cpcons'=>$row['id_asenta_cpcons'],
        'd_zona'=>$row['d_zona'],
        'c_cve_ciudad'=>$row['c_cve_ciudad']*/
    );
}

//array que guarda las colonias que pertenecen al mismo CP
$col=array();

foreach($data as $d){
    $col[]=$d['colonia'];
}

//array con los datos que se van a imprimir en pantalla
foreach($data as $d){
    $data=array(
        'cp'=>$d['cp'],
        'colonia'=>$col,
        'estado'=>$d['d_estado'],
        'municipio'=>$d['D_mnpio'],
        'ciudad'=>$d['d_ciudad']
    );
}

$close = mysqli_close($conn);

echo "<pre>";
//convierte los resultados en json
echo json_encode($data, JSON_UNESCAPED_UNICODE);
echo "</pre>";