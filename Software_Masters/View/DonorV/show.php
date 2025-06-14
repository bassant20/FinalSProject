<h2>Donation: <?= $donationInfo['title'] ?></h2>
<p><strong>Description:</strong> <?= $donationInfo['description'] ?></p>
<p><strong>Target:</strong> <?= $donationInfo['target_amount'] ?></p>
<p><strong>Current:</strong> <?= $donationInfo['current_amount'] ?></p>

<h3>Add a Donation</h3>
<form method="POST" action="index.php?action=donate&id=<?= $donationInfo['id'] ?>">
    <input type="number" name="amount" min="1" required>
    <button type="submit">Donate</button>
</form>
