<?php
session_start();
require_once '../../Model/Donation.php';
require_once '../../Model/Donor.php';
require_once '../../Model/Event.php';
require_once '../../Model/Database.php';

// Get the last donation details
$donation = new Donation();
$donor = new Donor();
$donor->getUser($_SESSION["Did"]);

// Get the last donation for this donor
$donations = $donation->getDonorDonations($_SESSION["Did"]);
$lastDonation = $donations->fetch_assoc();

// Get event details from the database
$conn = Database::getInstance()->getConnection();
$eventSql = "SELECT * FROM event WHERE id = ?";
$stmt = $conn->prepare($eventSql);
$stmt->bind_param("i", $lastDonation['event_id']);
$stmt->execute();
$eventResult = $stmt->get_result();
$eventData = $eventResult->fetch_assoc();

// Create event object with the correct data
$event = new Event($eventData['name'], $eventData['date'], $eventData['location']);

// Set the donation details
$donation->setDonor($donor);
$donation->setEvent($event);
$donation->setAmount($lastDonation['amount']);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Donation Successful</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
			.receipt-preview {
				background-color: #f8f9fa;
				padding: 20px;
				margin: 20px 0;
				border-radius: 5px;
				white-space: pre-wrap;
				font-family: monospace;
			}
		</style>
	</head>
	<body class="is-preload">
		<div id="wrapper">
			<div id="main">
				<div class="inner">
					<header class="major">
						<h1>Thank You for Your Donation!</h1>
					</header>

					<section>
						<div class="content">
							<p>Your donation has been successfully processed. We greatly appreciate your generosity and support.</p>
							<p>A confirmation email has been sent to your registered email address.</p>
							
							<div class="receipt-preview" id="receiptPreview" style="display: none;"></div>
							
							<ul class="actions">
								<li><button onclick="generateReceipt()" class="button primary">Generate Receipt</button></li>
								<li><a href="index.php" class="button">Return to Home</a></li>
								<li><a href="donation.php" class="button">Make Another Donation</a></li>
							</ul>
						</div>
					</section>
				</div>
			</div>
		</div>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
		<script>
			function generateReceipt() {
				// Show loading state
				document.querySelector('button').textContent = 'Generating Receipt...';
				document.querySelector('button').disabled = true;

				// Make AJAX call to generate receipt
				fetch('../../Controller/DonorC/generate_receipt.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						donation_id: <?php echo $lastDonation['id']; ?>
					})
				})
				.then(response => response.text())
				.then(receipt => {
					// Display receipt
					document.getElementById('receiptPreview').textContent = receipt;
					document.getElementById('receiptPreview').style.display = 'block';
					
					// Reset button state
					document.querySelector('button').textContent = 'Generate Receipt';
					document.querySelector('button').disabled = false;
				})
				.catch(error => {
					console.error('Error:', error);
					alert('Error generating receipt. Please try again.');
					
					// Reset button state
					document.querySelector('button').textContent = 'Generate Receipt';
					document.querySelector('button').disabled = false;
				});
			}
		</script>
	</body>
</html> 