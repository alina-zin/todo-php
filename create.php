<?php
// kuka tahansa saa kutsua palvelua:
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,OPTIONS');
header('Access-Control-Allow-Headers: Accept,Content-Type,Access-Control-Allow-Header');
// palautettava tieto on JSON-muodossa:
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}
 //luetaan tieto kentästä; FILTER_SANITIZE_STRING - tekstille
$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);


try {
    $db = new PDO('mysql:host=localhost;dbname=todo;charset=utf8','root','');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //sql lause
    $query = $db->prepare('insert into task(description) value (:description)');
    $query->bindValue(':description', $description,PDO::PARAM_STR);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(), 'description' => $description);
    echo json_encode($data);
    }
catch(PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    echo json_encode($error);
}