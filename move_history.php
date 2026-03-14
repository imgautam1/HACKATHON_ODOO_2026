<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
header("Location:index.php");
exit();
}

$data=mysqli_query($conn,"SELECT * FROM move_history ORDER BY move_id DESC");

?>

<!DOCTYPE html>
<html>
<head>

<title>Move History</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php include "components/sidebar.php"; ?>

<div class="flex-1 p-8">

<h1 class="text-3xl font-bold mb-6">Inventory Move History</h1>


<div class="bg-white rounded shadow overflow-hidden">

<table class="w-full text-left">

<thead class="bg-gray-200">

<tr>

<th class="p-3">ID</th>
<th class="p-3">Product</th>
<th class="p-3">SKU</th>
<th class="p-3">Type</th>
<th class="p-3">From</th>
<th class="p-3">To</th>
<th class="p-3">Quantity</th>
<th class="p-3">Date</th>
<th class="p-3">Time</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3"><?php echo $row['move_id']; ?></td>
<td class="p-3"><?php echo $row['product_name']; ?></td>
<td class="p-3"><?php echo $row['sku']; ?></td>
<td class="p-3"><?php echo $row['movement_type']; ?></td>
<td class="p-3"><?php echo $row['from_location']; ?></td>
<td class="p-3"><?php echo $row['to_location']; ?></td>
<td class="p-3"><?php echo $row['quantity']; ?></td>
<td class="p-3"><?php echo $row['movement_date']; ?></td>
<td class="p-3"><?php echo $row['movement_time']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>