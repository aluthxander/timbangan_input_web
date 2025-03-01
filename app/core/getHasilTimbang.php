<?php



//ganti com31 dengan alamat port yang digunakan
//exec('mode com11: baud=9600 data=8 stop=1 parity=n xon=on');
exec('mode com4: baud=9600 data=8 stop=1 parity=n xon=off dtr=off rts=off');

//ganti COM31 dengan alamat port yang digunakan
$fd = dio_open('COM4', O_RDWR);


//while (1) {
 //   $data = dio_read($fd, 256);
  
   // if ($data) {
     //  echo $data;
   // }
 // }


  $data = dio_read($fd, 256);
  
    if ($data) {

      $pos= strpos($data,"WT");
      $berat= substr($data,$pos,10);
      $berat1= str_replace("WT:","",$berat);
      $berat2= str_replace("g","",$berat1);
      echo $berat2;


    }





/*
$q = intval($_GET['q']);

include('../koneksi.php'); //memanggil file koneksi
$sql="SELECT * FROM barang WHERE id = '".$q."'";
$datas = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
while($row = mysqli_fetch_assoc($datas)) 
{
    echo $row['berat_gram'];
}
*/













/*
$con = mysqli_connect('localhost','peter','abc123');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM user WHERE id = '".$q."'";
$result = mysqli_query($con,$sql);

echo "<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
<th>Age</th>
<th>Hometown</th>
<th>Job</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['FirstName'] . "</td>";
  echo "<td>" . $row['LastName'] . "</td>";
  echo "<td>" . $row['Age'] . "</td>";
  echo "<td>" . $row['Hometown'] . "</td>";
  echo "<td>" . $row['Job'] . "</td>";
  echo "</tr>";
}
echo "</table>";
*/


/*

<?php
//ganti com31 dengan alamat port yang digunakan
//exec('mode com11: baud=9600 data=8 stop=1 parity=n xon=on');
exec('mode com4: baud=9600 data=8 stop=1 parity=n xon=off dtr=off rts=off');

//ganti COM31 dengan alamat port yang digunakan
$fd = dio_open('COM4', O_RDWR);


//while (1) {
//    $data = dio_read($fd, 256);
  
//    if ($data) {
//       echo $data;
//    }
//  }


  $data = dio_read($fd, 256);
  
    if ($data) {
       echo $data;
    }
?>


<!DOCTYPE html>
<html>
<body>

<?php
$pos= strpos("000.00g STATUS:UNSTEADY STEP:NONE TARE:NONE ZERO:NATURAL CT:20833pcs UW:0.01000g WT:208.33g TYPE:22002 ID:1 DATE:24-05-14 TIME:00-23-12 TEMP:27.8C BAT:6.9V(EXT) MODE:COUNT REF:1000.00g STATUS:UNSTEADY S","WT");

echo substr("000.00g STATUS:UNSTEADY STEP:NONE TARE:NONE ZERO:NATURAL CT:20833pcs UW:0.01000g WT:208.33g TYPE:22002 ID:1 DATE:24-05-14 TIME:00-23-12 TEMP:27.8C BAT:6.9V(EXT) MODE:COUNT REF:1000.00g STATUS:UNSTEADY S",$pos,10);
?> 

</body>
</html>




*/

//mysqli_close($koneksi);


?>