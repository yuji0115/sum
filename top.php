<!DOCTYPE html>
<?php
    $dsn = "mysql:dbname=samurai_study;host=db-test.ccpibylalhxo.ap-northeast-1.rds.amazonaws.com;charset=utf8mb4";
    $username = "";
    $password = '';
    $driverOptions =  [
                // エラー時の挙動設定。DB接続エラーになった場合などに情報を出力
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // FETCH_ASSOC 結果取得時にループで処理できるようにするための設定
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $sql ="SELECT * FROM yoyaku order by table_id";
    
    $pdo = new PDO($dsn, $username, $password, $driverOptions);    

    $stmt = $pdo->query($sql);
    $yoyaku_list=[];
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
//        echo json_encode($result);
        $yoyaku_list[$result["table_id"]][]=$result;
    }
        $TIME_LIST = [
      "17:00",
      "18:00",
      "19:00",
      "20:00",
      "21:00",
      "22:00",
      "23:00",
      "24:00"
    ];
    $list=[];
    
    $TABLE_LIST = [1, 2, 3,4,5];
    foreach($TABLE_LIST as $table) {
      foreach($TIME_LIST as $time) {
        if (!isset($yoyaku_list[$table])) {
          $list[$table][$time] = [];
          continue;
        }
        foreach($yoyaku_list[$table] as $yoyaku) {
          if (strtotime($yoyaku["start_at"]) <= strtotime($time) && strtotime($time) < strtotime($yoyaku["end_at"])){
            $list[$table][$time] = $yoyaku;
            break;
          }
        }
        if(!isset($list[$table][$time])){
          $list[$table][$time] = [];
        }
      }
    }
    $food_master = [
        1=>"鍋コース",
        2=>"飲み食べ放題",
        3=>"席予約"
        ];

    /*
    foreach($stmt as $row){
        $guest_number=$row["guest_number"];
        $start_at=$row["start_at"];
        $end_at=$row["end_at"];
        $food_id=$row["food_id"];
        $table_id=$row["table_id"];
        $guest_name=$row["guest_name"];
    }
    */
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="./top.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>飲食店の予約管理</title>
</head>

<body>
<div class="d-flex flex-row bd-highlight mb-3">
    <div class="p-2 bd-highlight">
        <div class="header">
            <div class="icon">
                <img src="./shop_icon.png"></img>
            </div>
        </div>
    </div>
    <div class="p-2 bd-highlight">
        <div class="shop_name">
            <h2>〇〇店</h2>
        </div>
        
        <div class="info_area">
            <a href="https://yahoo.co.jp" type="button" class="btn btn-primary shop_detail">店舗詳細</a>
           <button type="button" class="btn btn-primary yoyaku_detail">予約一覧</button>
        </div>
    </div>
</div>
<form action="./kakutei.php" method="post">
    <div class="input">
        <div class="d-flex justify-content-between">
            <div class="mb-3">
                <label for="people" class="col-sm col-form-label">予約人数</label>
                <select class="form-select form-select-sm" name="guest_number" aria-label=".form-select-sm example">
                    <option selected>予約人数</option>
                    <option value="1名">1</option>
                    <option value="2名">2</option>
                    <option value="3名">3</option>
                    <option value="4名">4</option>
                    <option value="5名">5</option>
                    <option value="6名">6</option>
                    <option value="7名">7</option>
                    <option value="8名">8</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_time" class="col-sm col-form-label">開始時間</label>
                <input class="form-control form-control-sm" type="text" placeholder="開始時間" name="start_at" aria-label=".form-control-sm example">
            </div>
            <div class="mb-3">
                <label for="end_time" class="col-sm col-form-label">終了時間</label>
                <input class="form-control form-control-sm" type="text" placeholder="終了時間" name="end_at" aria-label=".form-control-sm example">
            </div>
            <div class="mb-3">
                <label for="food" class="col-sm col-form-label">コース内容</label>
                <select class="form-select form-select-sm" name="food_id" aria-label=".form-select-sm example">
                    <option selected>コース内容</option>
                    <?php foreach($food_master as $food_id => $food_name):?>
                        <option value="<?php echo $food_id; ?>"><?php echo $food_name; ?></option>
                    <?php endforeach; ?>
                  
                </select>
            </div>
            <div class="mb-3">
                <label for="table" class="col-sm col-form-label">テーブル番号</label>
                <select class="form-select form-select-sm" name="table_id" aria-label=".form-select-sm example">
                    <option selected>テーブル番号</option>
                    <option value="1">１番</option>
                    <option value="2">２番</option>
                    <option value="3">３番</option>
                    <option value="4">４番</option>
                    <option value="5">５番</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="custmer_name" class="col-sm col-form-label">ご予約名</label>
                <input class="form-control form-control-sm" type="text" placeholder="予約者名" name="guest_name" aria-label=".form-control-sm example">
            </div>
                <button type="submit" class="kakutei_btn btn btn-outline-danger btn-sm">確定</button>
        </div>
    </div>
</form>
    <div class="yoyaku_list">
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">予約リスト</th>
      <?php foreach($TIME_LIST as $time):?>
          <th scope="col"><?php echo $time ?></th>
      <?php endforeach;?>
      
    </tr>
  </thead>
  <tbody>
      <?php foreach($list as $table_id => $yoyakus): ?>
          <tr>
           <th scope="row"><?php echo $table_id."番テーブル" ?></th>
           <?php foreach($yoyakus as $yoyaku): ?>
           
            <?php if(!empty($yoyaku)):?>
               <td class="table-danger">
               <ul>
                   <li>
                       <?php echo $yoyaku["guest_name"]?>
                   </li>
                   <li>
                        <?php echo $food_master[$yoyaku["food_id"]] ?>
                   </li>
               </ul>
               </td>
            <?php else: ?>
               <td></td>
            <?php endif; ?>
           
           <?php endforeach; ?>
          </tr>
      <?php endforeach; ?>
      
   
  </tbody>
</table>
    </div>
</body>
</html>