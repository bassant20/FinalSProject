<?php
require_once '../../Model/Donation.php';
$donation = new Donation();
$events = $donation->getAllEvents();
?>
<?php

session_start();

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Make a Donation</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
			.payment-method {
				margin: 20px 0;
				padding: 20px;
				border: 1px solid #ddd;
				border-radius: 5px;
			}
			.payment-method.active {
				border-color: #4CAF50;
				background-color: #f9fff9;
			}
			.payment-details {
				display: none;
				margin-top: 15px;
			}
			.payment-details.active {
				display: block;
			}
			.event-info {
				margin: 20px 0;
				padding: 15px;
				background-color: #f8f9fa;
				border-radius: 5px;
			}
			.progress-bar {
				width: 100%;
				background-color: #e9ecef;
				border-radius: 5px;
				margin: 10px 0;
			}
			.progress {
				height: 20px;
				background-color: #4CAF50;
				border-radius: 5px;
				text-align: center;
				line-height: 20px;
				color: white;
			}
		</style>
	</head>
	<body class="is-preload">
		<div id="wrapper">
			<div id="main">
				<div class="inner">
					<header class="major">
						<h1>Make a Donation</h1>
					</header>
<?php

                echo $_SESSION["Did"];

?>
					<form action="../../Controller/DonorC/donation_controller.php" method="POST" id="donationForm">
						<div class="row gtr-uniform">
							<div class="col-12">
								<label for="event_id">Select Event to Donate To</label>
								<select name="event_id" id="event_id" required>
									<option value="">Select an event...</option>
									<?php while($event = $events->fetch_assoc()): ?>
										<option value="<?php echo $event['id']; ?>">
											<?php echo $event['name']; ?> - 
											Location: <?php echo $event['location']; ?> - 
											Date: <?php echo $event['date']; ?>
										</option>
									<?php endwhile; ?>
								</select>
							</div>

							<div class="col-12">
								<label for="amount">Donation Amount ($)</label>
								<input type="number" name="amount" id="amount" min="1" step="0.01" required />
							</div>

							<div class="col-12">
								<h3>Select Payment Method</h3>
								
								<!-- Credit Card -->
								<div class="payment-method" id="creditCardMethod">
									<input type="radio" id="creditCard" name="paymentMethod" value="creditCard" required>
									<label for="creditCard">Credit Card</label>
									<div class="payment-details" id="creditCardDetails">
										<div class="row gtr-uniform">
											<div class="col-12">
												<input type="text" name="cardNumber" placeholder="Card Number" pattern="[0-9]{16}" />
											</div>
											<div class="col-6">
												<input type="text" name="cardHolder" placeholder="Card Holder Name" />
											</div>
											<div class="col-3">
												<input type="text" name="cvv" placeholder="CVV" pattern="[0-9]{3,4}" />
											</div>
											<div class="col-3">
												<input type="month" name="expiryDate" />
											</div>
										</div>
									</div>
								</div>

								<!-- PayPal -->
								<div class="payment-method" id="paypalMethod">
									<input type="radio" id="paypal" name="paymentMethod" value="paypal">
									<label for="paypal">PayPal</label>
									<div class="payment-details" id="paypalDetails">
										<div class="row gtr-uniform">
											<div class="col-12">
												<input type="email" name="paypalEmail" placeholder="PayPal Email" />
											</div>
											<div class="col-12">
												<input type="password" name="paypalPassword" placeholder="PayPal Password" />
											</div>
										</div>
									</div>
								</div>

								<!-- Bank Transfer -->
								<div class="payment-method" id="bankTransferMethod">
									<input type="radio" id="bankTransfer" name="paymentMethod" value="bankTransfer">
									<label for="bankTransfer">Bank Transfer</label>
									<div class="payment-details" id="bankTransferDetails">
										<div class="row gtr-uniform">
											<div class="col-12">
												<input type="text" name="accountNumber" placeholder="Account Number" />
											</div>
											<div class="col-6">
												<input type="text" name="bankName" placeholder="Bank Name" />
											</div>
											<div class="col-6">
												<input type="text" name="accountHolderName" placeholder="Account Holder Name" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Make Donation" class="primary" /></li>
									<li><input type="reset" value="Reset" /></li>
								</ul>
							</div>
						</div>
					</form>
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
			document.addEventListener('DOMContentLoaded', function() {
				const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
				const paymentDetails = document.querySelectorAll('.payment-details');
				const paymentMethodDivs = document.querySelectorAll('.payment-method');

				paymentMethods.forEach(method => {
					method.addEventListener('change', function() {
						paymentDetails.forEach(detail => detail.classList.remove('active'));
						paymentMethodDivs.forEach(div => div.classList.remove('active'));

						const selectedMethod = this.value;
						document.getElementById(selectedMethod + 'Details').classList.add('active');
						document.getElementById(selectedMethod + 'Method').classList.add('active');
					});
				});
			});
		</script>
	</body>
</html> 