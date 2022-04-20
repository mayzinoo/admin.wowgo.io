<?php
header('Content-Type: application/json');


$conn=pg_connect("host=localhost dbname=wowgodb user=postgres password=123");
$stat = pg_connection_status($conn);
  if ($stat === PGSQL_CONNECTION_OK) {
     //echo 'Connection status ok';
  } else {
      //echo 'Connection status bad';
  }   
$id=$_REQUEST['userid'];
//echo $id;
//$result = pg_query($conn, "SELECT coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as result,DATE(created) as mydate FROM plays where user_id='".$id."' ORDER BY id desc LIMIT 5");
$result= pg_query($conn, "select coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as result ,DATE(created) as mydate From plays where user_id='".$id."' Group by Date(created) Order By Date(created) desc Limit 5");
//echo $result;
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

$arr=pg_fetch_all($result);
if ($arr == 'false') {
        echo pg_last_error();
     }

//echo $arr;
pg_close($conn);
//echo 'ddddd';
echo json_encode($arr);
?>
