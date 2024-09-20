<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'admin') {
  header("Location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Feedback | Sales & Marketing System</title>
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #ecf0f1;
      color: #333;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .feedback-details {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 12px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .status-pending {
      color: #ffc107;
    }
    .status-reviewed {
      color: #007bff;
    }
    .status-resolved {
      color: #28a745;
    }
    .no-record {
      text-align: center;
      color: #888;
      padding: 20px;
    }
    .status-form {
      display: flex;
      align-items: center;
    }
    .status-form select {
      margin-right: 10px;
    }
    .status-form button {
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      color: #fff;
      background-color: #007bff;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Feedback</h1>
      </div>

      <div class="feedback-details">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Email</th>
              <th>Description</th>
              <th>Created At</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('../includes/connect.php');

            $query = "SELECT * FROM feedback ORDER BY created_at DESC";
            $result = $con->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $status_class = '';
                switch ($row['status']) {
                  case 'pending':
                    $status_class = 'status-pending';
                    break;
                  case 'reviewed':
                    $status_class = 'status-reviewed';
                    break;
                  case 'resolved':
                    $status_class = 'status-resolved';
                    break;
                }
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['created_at']}</td>
                        <td class='{$status_class}'>{$row['status']}</td>
                        <td>
                          <form action='update_feedback.php' method='POST' class='status-form'>
                            <input type='hidden' name='feedback_id' value='{$row['id']}'>
                            <select name='status'>
                              <option value='pending' " . ($row['status'] === 'pending' ? 'selected' : '') . ">Pending</option>
                              <option value='reviewed' " . ($row['status'] === 'reviewed' ? 'selected' : '') . ">Reviewed</option>
                              <option value='resolved' " . ($row['status'] === 'resolved' ? 'selected' : '') . ">Resolved</option>
                            </select>
                            <button type='submit'>Update</button>
                          </form>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='6' class='no-record'>No feedback found</td></tr>";
            }

            $con->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
