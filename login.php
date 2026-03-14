<?php
session_start();
include "db.php";

$error="";
$success="";

/* LOGIN */

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$query="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($conn,$query);

if(mysqli_num_rows($result)==1){

$row=mysqli_fetch_assoc($result);

if(password_verify($password,$row['password'])){

$_SESSION['user_id']=$row['user_id'];
$_SESSION['name']=$row['name'];

header("Location: dashboard.php");
exit();

}else{
$error="Invalid Email or Password";
}

}else{
$error="Invalid Email or Password";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Login</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-96">

<h2 class="text-2xl font-bold text-center mb-6">LOGIN FORM</h2>

<?php if($error!=""){ ?>
<p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
<?php } ?>

<?php if($success!=""){ ?>
<p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
<?php } ?>

<form method="POST" class="space-y-4">

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

<input 
type="password" 
name="password"
id="password"
placeholder="Enter Password"
class="w-full border border-gray-500 rounded px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
required>

<span 
class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500"
onmouseenter="showPassword()"
onmouseleave="hidePassword()">

👁️

</span>

</div>

<div class="flex items-center gap-2">

<input type="checkbox" id="remember">

<label class="text-sm text-gray-700">Remember Me</label>

</div>

<button 
name="login"
class="text-white bg-blue-500 rounded-md mx-auto block hover:bg-blue-600 transition font-semibold p-2 w-full">

Login

</button>

<div class="flex items-center my-4">

<div class="flex-grow h-px bg-gray-300"></div>

<span class="mx-2 text-sm text-gray-500">OR</span>

<div class="flex-grow h-px bg-gray-300"></div>

</div>

<div class="space-y-4">

<a href="google-login.php"
class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">

<img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">

<span class="text-sm font-medium">Login with Google</span>

</a>

<a href="facebook-login.php"
class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700 transition">

<img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5">

<span class="text-sm font-medium">Login with Facebook</span>

</a>

</div>

<a href="#" class="text-sm text-blue-500 hover:underline text-center block">
Forgot Password?
</a>

<p class="text-center text-sm mt-4">
Don't have an account?
<a href="signup.php" class="text-blue-500 hover:underline">Sign Up</a>
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