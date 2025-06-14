<?php

require_once '../../Controller/DonationController.php';

$controller = new DonationController();

if (isset($_GET['action']) === 'show' && isset($_GET['id'])) {
    $controller->show($_GET['id']);
} elseif (isset($_GET['action']) === 'donate' && isset($_GET['id']) && isset($_POST['amount'])) {
    $controller->donate($_GET['id'], $_POST['amount']);
} else {
    $controller->index();
}


?>

<?php
if (!isset($donations)) {
    echo "<p style='color: red;'>\$donations is NOT defined!</p>";
} else {
    echo "<p style='color: green;'>\$donations loaded successfully.</p>";
}
?>

<h2>All Donations</h2>

<?php if ($donations && $donations->num_rows > 0): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Target</th>
            <th>Current</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $donations->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['target_amount'] ?></td>
                <td><?= $row['current_amount'] ?></td>
                <td><a href="index.php?action=show&id=<?= $row['id'] ?>">View</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No donations found or failed to load donations.</p>
<?php endif; ?>
