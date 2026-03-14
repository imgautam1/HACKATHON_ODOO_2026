<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CoreInventory - Smart Inventory Management</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>

html{
scroll-behavior:smooth;
}

body{
background:#f7fafc;
}

/* Feature hover animation */
.feature-card{
transition:all .3s ease;
}

.feature-card:hover{
transform:translateY(-8px);
box-shadow:0 20px 25px rgba(0,0,0,0.15);
}

/* Button animation */
.btn{
transition:all .3s ease;
}

.btn:hover{
transform:scale(1.05);
}

/* Fade animation */
.fade{
opacity:0;
transform:translateY(40px);
transition:all 0.8s ease;
}

.fade.show{
opacity:1;
transform:translateY(0);
}

</style>

</head>

<body>

<!-- NAVBAR -->

<nav class="bg-white shadow sticky top-0 z-50">

<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<h1 class="text-2xl font-bold text-blue-600">
CoreInventory
</h1>

<ul class="flex space-x-8 font-medium">

<li><a href="#home" class="hover:text-blue-600">Home</a></li>
<li><a href="#features" class="hover:text-blue-600">Features</a></li>
<li><a href="#workflow" class="hover:text-blue-600">How It Works</a></li>
<li><a href="#benefits" class="hover:text-blue-600">Benefits</a></li>

</ul>

<div class="space-x-4">

<?php if(isset($_SESSION['user_id'])){ ?>

<a href="dashboard.php" class="btn border px-4 py-2 rounded hover:bg-gray-100">
Dashboard
</a>

<a href="logout.php" class="btn bg-red-500 text-white px-5 py-2 rounded">
Logout
</a>

<?php } else { ?>

<a href="index.php" class="btn border px-4 py-2 rounded hover:bg-gray-100">
Login
</a>

<a href="index.php" class="btn bg-blue-600 text-white px-5 py-2 rounded">
Get Started
</a>

<?php } ?>

</div>

</div>

</nav>


<!-- HERO SECTION -->

<section id="home" class="py-24 text-white relative bg-cover bg-center"
style="background-image:url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d');">

<div class="absolute inset-0 bg-black bg-opacity-60"></div>

<div class="relative max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

<div>

<h1 class="text-5xl font-bold mb-6">
Smart Inventory Management for Modern Warehouses
</h1>

<p class="text-lg mb-8">
Track products, monitor stock movements, and manage warehouses in real time with CoreInventory.
</p>

<div class="space-x-4">

<a href="dashboard.php" class="btn bg-white text-blue-600 px-6 py-3 rounded font-semibold">
Start Managing Inventory
</a>

<button class="btn border border-white px-6 py-3 rounded">
View Demo
</button>

</div>

</div>

<img src="https://cdn-icons-png.flaticon.com/512/2630/2630839.png" class="w-96 mx-auto">

</div>

</section>


<!-- PROBLEMS -->

<section class="py-20 fade">

<div class="max-w-6xl mx-auto text-center px-6">

<h2 class="text-4xl font-bold mb-8">
Problems with Traditional Inventory Systems
</h2>

<div class="grid md:grid-cols-4 gap-8">

<div class="p-6 bg-white rounded shadow">
📄
<h3 class="font-semibold mt-2">Manual Registers</h3>
<p class="text-sm text-gray-600">
Stock tracked on paper leading to errors.
</p>
</div>

<div class="p-6 bg-white rounded shadow">
📊
<h3 class="font-semibold mt-2">Excel Tracking</h3>
<p class="text-sm text-gray-600">
Hard to maintain and update stock records.
</p>
</div>

<div class="p-6 bg-white rounded shadow">
❌
<h3 class="font-semibold mt-2">Stock Mismatch</h3>
<p class="text-sm text-gray-600">
Physical stock differs from records.
</p>
</div>

<div class="p-6 bg-white rounded shadow">
⏱
<h3 class="font-semibold mt-2">Slow Operations</h3>
<p class="text-sm text-gray-600">
Warehouse operations become inefficient.
</p>
</div>

</div>

</div>

</section>


<!-- FEATURES -->

<section id="features" class="py-24 bg-gray-100 fade">

<div class="max-w-7xl mx-auto px-6">

<h2 class="text-4xl font-bold text-center mb-16">
Core Features
</h2>

<div class="grid md:grid-cols-3 gap-10">

<div class="feature-card bg-white p-8 rounded shadow">
📦
<h3 class="text-xl font-semibold mt-4">Product Management</h3>
<p class="text-gray-600 mt-2">
Create products with SKU, category, and stock levels.
</p>
</div>

<div class="feature-card bg-white p-8 rounded shadow">
📥
<h3 class="text-xl font-semibold mt-4">Incoming Stock</h3>
<p class="text-gray-600 mt-2">
Record goods received from suppliers.
</p>
</div>

<div class="feature-card bg-white p-8 rounded shadow">
🚚
<h3 class="text-xl font-semibold mt-4">Delivery Orders</h3>
<p class="text-gray-600 mt-2">
Manage outgoing shipments to customers.
</p>
</div>

<div class="feature-card bg-white p-8 rounded shadow">
🔄
<h3 class="text-xl font-semibold mt-4">Internal Transfers</h3>
<p class="text-gray-600 mt-2">
Move inventory between warehouses or racks.
</p>
</div>

<div class="feature-card bg-white p-8 rounded shadow">
⚖
<h3 class="text-xl font-semibold mt-4">Stock Adjustments</h3>
<p class="text-gray-600 mt-2">
Correct mismatches between recorded and physical stock.
</p>
</div>

<div class="feature-card bg-white p-8 rounded shadow">
🏭
<h3 class="text-xl font-semibold mt-4">Multi-Warehouse</h3>
<p class="text-gray-600 mt-2">
Track stock across multiple locations.
</p>
</div>

</div>

</div>