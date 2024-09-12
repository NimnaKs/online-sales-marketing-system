<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer Details | Sales & Marketing System</title>
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"/>
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
      --danger-color: #e74c3c;
      --light-bg: #ecf0f1;
      --card-bg: #fff;
      --text-color: #333;
      --border-radius: 8px;
      --button-radius: 5px;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-color);
      margin: 0;
    }
    .container {
      display: flex;
      min-height: 100vh;
      padding: 20px;
    }
  
    .customer-details {
      background: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f4f4f4;
      font-weight: bold;
      color: var(--text-color);
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .action-buttons {
      display: flex;
      gap: 5px;
      justify-content: center;
    }
    .btn-edit, .btn-delete {
      padding: 8px 12px;
      border: none;
      border-radius: var(--button-radius);
      cursor: pointer;
      font-size: 14px;
      color: #fff;
      text-align: center;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: background-color 0.3s ease;
    }
    .btn-edit {
      background-color: var(--primary-color);
    }
    .btn-edit:hover {
      background-color: #2980b9;
    }
    .btn-delete {
      background-color: var(--danger-color);
    }
    .btn-delete:hover {
      background-color: #c0392b;
    }
    .btn-edit i, .btn-delete i {
      font-size: 16px;
    }
    .no-record {
      text-align: center;
      color: #888;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Customer Details</h1>
      </div>

      <div class="customer-details">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('../includes/connect.php');

            $query = "SELECT * FROM Users WHERE role = 'user' ORDER BY created_at DESC";
            $result = $con->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='no-record'>No users found</td></tr>";
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
