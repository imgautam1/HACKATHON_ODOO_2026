<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(["body" => "<tr><td colspan='4'>Session expired. Please login again.</td></tr>"]);
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['action']) && $_POST['action'] == "fetch_user_logs"){
    // Fetch activity logs for the current user in the current session
    $query = "SELECT action, module AS tableName, created_at 
              FROM activity_logs 
              WHERE user_id = ? 
              ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $html = "";
    $count = 1;
    while($row = $result->fetch_assoc()){
        $html .= "<tr>
                    <td>{$count}</td>
                    <td class='action'>{$row['action']}</td>
                    <td class='tableName'>{$row['tableName']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        $count++;
    }

    if($count == 1){
        $html = "<tr><td colspan='4'>No activity found for this session.</td></tr>";
    }

    echo json_encode(["body" => $html]);
    exit();
}
?>