<?php
session_start();
include "db.php";

$error="";
$success="";

if(isset($_POST['signup'])){

$name = mysqli_real_escape_string($conn,$_POST['name']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$check = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$check);

if(mysqli_num_rows($result) > 0){

$error = "Email already registered!";

}else{

$query = "INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$password','$role')";

if(mysqli_query($conn,$query)){
$success = "Account created successfully!";
}else{
$error = "Signup failed!";
}

}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Signup</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-96">

<h2 class="text-2xl font-bold text-center mb-6">CREATE ACCOUNT</h2>

<?php if($error!=""){ ?>
<p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
<?php } ?>

<?php if($success!=""){ ?>
<p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
<?php } ?>

<form method="POST" class="space-y-4">

<div>
<label class="block text-sm font-medium mb-1">Full Name</label>

<input 
type="text" 
name="name"
placeholder="Enter Your Name"
class="w-full border border-gray-500 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
required>
</div>

<div>
<label class="block text-sm font-medium mb-1">Email</label>

<input 
type="email" 
name="email"
placeholder="Enter Your Email"
class="w-full border border-gray-500 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
required>
</div>

<div class="relative">

<label class="block text-sm font-medium mb-1">Password</label>

<input 
type="password"
name="password"
id="password"
placeholder="Enter Password"
class="w-full border border-gray-500 rounded px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
required>

<span 
class="absolute right-3 top-10 cursor-pointer text-gray-500"
onmouseenter="showPassword()"
onmouseleave="hidePassword()">

👁️

</span>

</div>

<div>

<label class="block text-sm font-medium mb-1">Role</label>

<select 
name="role"
class="w-full border border-gray-500 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

<option value="staff">Staff</option>
<option value="manager">Manager</option>

</select>

</div>

<button 
type="submit"
name="signup"
class="w-full text-white bg-blue-500 rounded-md hover:bg-blue-600 transition font-semibold p-3">

Create Account

</button>

<p class="text-center text-sm mt-4">

Already have an account?

<a href="login.php" class="text-blue-500 hover:underline">Login</a>

</p>

</form>

</div>

</body>

<script>

function showPassword(){
document.getElementById("password").type="text";
}

function hidePassword(){
document.getElementById("password").type="password";
}

</script>

</html>