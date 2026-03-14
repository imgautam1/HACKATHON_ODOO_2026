<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs - CoreInventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fb;
        }
        .content-wrapper { flex: 1 0 auto; }
        footer { flex-shrink: 0; padding: 15px; background: #111; color: #ccc; text-align: center; }
        .card-header { background-color: #0d6efd; color: white; font-weight: 600; font-size: 1.2rem; }
        .search-box { max-width: 300px; margin-bottom: 15px; }
        .table th { background-color: #e3f2fd; color: #0d6efd; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Activity Logs
            </div>
            <div class="card-body">
                <input type="text" class="form-control search-box" id="searchLogs" placeholder="🔍 Search by action or table...">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="logsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Table</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadData() {
    $.ajax({
        url:"ajax_crud.php",
        method:"POST",
        data: { action: "fetch_user_logs" },
        success:function(data){
            let res = JSON.parse(data);
            $("#tableBody").html(res.body);
        }
    });
}

$(document).ready(function(){
    loadData();

    $("#searchLogs").on("keyup", function(){
        const value = $(this).val().toLowerCase();
        $("#logsTable tbody tr").filter(function(){
            $(this).toggle(
                $(this).find(".action").text().toLowerCase().includes(value) ||
                $(this).find(".tableName").text().toLowerCase().includes(value)
            );
        });
    });
});
</script>

<footer>
    CoreInventory Inventory System © <?= date("Y") ?>
</footer>

</body>
</html>