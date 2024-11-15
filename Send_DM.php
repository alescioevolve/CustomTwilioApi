<?php
// Load environment variables from .env file
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Twilio\Rest\Client;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve Twilio credentials from environment variables
$sid = $_ENV['TWILIO_SID'];
$token = $_ENV['TWILIO_TOKEN'];
$twilioPhoneNumber = $_ENV['TWILIO_PHONE_NUMBER'];

$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the Twilio client is created only when needed
    $client = new Client($sid, $token);

    // Get the input values from the form
    $to = $_POST['to'];
    $whatsappTo = 'whatsapp:' . $to;
    $messageBody = $_POST['message'];

    try {
        // Send the SMS
        $client->messages->create(
            $whatsappTo,
            [
                'from' => $twilioPhoneNumber,
                'body' => $messageBody,
            ]
        );
        $status = 'Message sent successfully!';
    } catch (Exception $e) {
        $status = 'Failed to send message: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send WhatsApp DM</title>

    <!-- CSS -->
    <link href="script/custom.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>

    <div class="row mx-auto p-auto py-5 ml-5">
        <div class="col-lg-6 justify-between">
            <h3>WhatsApp Twilio Messaging</h3>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="to">Recipient Phone Number:</label>
                    <input type="text" id="to" name="to" required class="form-control" placeholder="Recipient Number">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea class="form-control" id="message" name="message" rows="4" cols="50" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">Send</button>
            </form>

            <?php if ($status): ?>
                <p style="color:green"><?php echo htmlspecialchars($status); ?></p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>