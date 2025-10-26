<?php
// Prevent any accidental whitespace outside PHP tags
header("Content-Type: application/json");

// Gmail IMAP credentials
$email_user = "temphum21@gmail.com";
$email_pass = "gcmv rvsm hvxh ieuu"; // Gmail App Password

// Initialize response
$response = ["status" => "pending"];

// Try to connect to Gmail IMAP and check for new emails
try {
    // Connect to Gmail IMAP
    $mail = @imap_open("{imap.gmail.com:993/imap/ssl}INBOX", $email_user, $email_pass);

    if (!$mail) {
        // Connection failed
        $response["status"] = "error";
        $response["message"] = imap_last_error();
        echo json_encode($response);  // Return JSON error response
        exit;  // Exit the script after sending the response
    }

    // Search for unseen emails with subject containing "High Temperature"
    $emails = imap_search($mail, 'UNSEEN SUBJECT "High Temperature"');

    if ($emails) {
        // Loop through unseen emails and check for the "yes" reply
        foreach ($emails as $email_number) {
            $msg = imap_fetchbody($mail, $email_number, 1);
            if (stripos($msg, "yes") !== false) {
                $response["status"] = "yes";  // User replied with "yes"
                break;  // Exit the loop once we find the "yes" reply
            } elseif (stripos($msg, "no") !== false) {
                $response["status"] = "no";  // User replied with "yes"
                break;  // Exit the loop once we find the "yes" reply
            }
        }
    }

    // Close the IMAP connection
    imap_close($mail);

} catch (Exception $e) {
    // Handle any exceptions and return the error message
    $response["status"] = "error";
    $response["message"] = $e->getMessage();
}

// Output JSON response (status and message)
echo json_encode($response);
exit;
?>
