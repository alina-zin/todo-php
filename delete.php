<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Accept, Content-Type, Access-Control-Allow-Header');
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_NUMBER_INT);

try {
    $db = new PDO('mysql:host=localhost;dbname=todo;charset:utf8','root','');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $query =$db->prepare('delete from task where id=:id');
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $id);
    echo json_encode($data);
}
catch (PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    echo json_encode($error);
} 