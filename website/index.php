<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["logged_in"])) {
    header('Location: login.php');
}
?>
<html>
<head>
    <title>Osaka University Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="title">
        <h1>Osaka University Dashboard</h1>
    </div>

    <div class="sidebar">
        <button>Download data</button>
        <button>Logout</button>
    </div>

</body>
</html>