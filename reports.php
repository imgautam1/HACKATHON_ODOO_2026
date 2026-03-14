<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
header("Location:index.php");
exit();
}

$data=mysqli_query($conn,"SELECT * FROM reports");
?>

<h2>Reports</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Delivery ID</th>
<th>Report Name</th>
<th>Date</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?php echo $row['report_id']; ?></td>
<td><?php echo $row['delivery_id']; ?></td>
<td><?php echo $row['report_name']; ?></td>
<td><?php echo $row['generated_at']; ?></td>
</tr>

<?php } ?>
</table>