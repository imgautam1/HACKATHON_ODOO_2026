<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
header("Location:index.php");
exit();
}

$user_id=$_SESSION['user_id'];

$user=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE user_id='$user_id'"));
?>

<h2>My Profile</h2>

<table border="1">

<tr>
<td>Name</td>
<td><?php echo $user['name']; ?></td>
</tr>

<tr>
<td>Email</td>
<td><?php echo $user['email']; ?></td>
</tr>

<tr>
<td>Role</td>
<td><?php echo $user['role']; ?></td>
</tr>

<tr>
<td>Created</td>
<td><?php echo $user['created_at']; ?></td>
</tr>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>