<?php
require __DIR__ . '/database.php';

$sql = "SELECT username, email FROM users ORDER BY username ASC";
$stmt = $conn->query($sql);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>List of Users</title>
</head>
<body>
    <h2>Registered Users</h2>
    <?php if (count($users) > 0): ?>
        <ul>
            <?php 
            $counter = 1;
            foreach ($users as $user): ?>
                <li>
                    <?php echo $counter . ". " . htmlspecialchars($user['username']) . " - " . htmlspecialchars($user['email']); ?>
                </li>
                <?php $counter++; ?>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</body>
</html>