<?php
echo json_encode($_POST);

$dsn = "mysql:dbname=samurai_study;host=db-test.ccpibylalhxo.ap-northeast-1.rds.amazonaws.com;charset=utf8mb4";
$username = "";
$password = '';
$driverOptions =  [
            // エラー時の挙動設定。DB接続エラーになった場合などに情報を出力
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // FETCH_ASSOC 結果取得時にループで処理できるようにするための設定
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    // PDOのインスタンス化
    $pdo = new PDO($dsn, $username, $password, $driverOptions);    
    $guest_number=$_POST["guest_number"];
    $start_at=$_POST["start_at"];
    $end_at=$_POST["end_at"];
    $food_id=$_POST["food_id"];
    $table_id=$_POST["table_id"];
    $guest_name=$_POST["guest_name"];
    
    // SQLの設定
     $stmt = $pdo->prepare('INSERT INTO yoyaku (guest_number,start_at,end_at,food_id,table_id,guest_name) '
                            .'values (:guest_number,:start_at,:end_at,:food_id,:table_id,:guest_name)');
    $stmt->bindValue(':guest_number', $guest_number, PDO::PARAM_INT);
    $stmt->bindValue(':start_at',date("Y-m-d H:i:s", strtotime($start_at)) , PDO::PARAM_STR);
    $stmt->bindValue(':end_at',date("Y-m-d H:i:s", strtotime($end_at)), PDO::PARAM_STR);
    $stmt->bindValue(':food_id', $food_id, PDO::PARAM_INT);
    $stmt->bindValue(':table_id', $table_id, PDO::PARAM_INT);
    $stmt->bindValue(':guest_name', $guest_name, PDO::PARAM_STR);
   
   $stmt->execute();
    
   
} catch (PDOException $e) {
    // 例外時の出力
    echo $e->getMessage();
}
header('Location: https://c2818076388d42eabab367543f395f67.vfs.cloud9.ap-northeast-1.amazonaws.com/yoyaku_system/top.php');