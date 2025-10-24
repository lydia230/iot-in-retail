<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content=
"width=device-width, initial-scale=1.0">
    <title>Phase 1</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<center><h1>Enter Client Credentials</h1></center>
    <div class="form-container">
        <form method="POST" action="index.php">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" required>

	    <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required>

            <input type="submit" value="Submit" name="insert">
        </form>
    </div>

<?php
	shell_exec("/usr/local/bin/gpio -g mode 27 out");
	shell_exec("/usr/local/bin/gpio -g mode 23 out");
	shell_exec("/usr/local/bin/gpio -g mode 12 out");


	$servername = "localhost";
	$username = "admin";
	$password = "123";
	$dbname = "rfid";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error)
	{
		die("Connection Failed");
	}

	if (isset($_POST['insert']))
	{
		$fName = $conn->real_escape_string($_POST['fname']);
		$lName = $conn->real_escape_string($_POST['lname']);
		$email = $conn->real_escape_string($_POST['email']);

		$phone = preg_replace('/\D/', '', $_POST['phone']);

		if (strlen($phone) < 10 || strlen($phone) > 20) {
			echo "Invalid phone number! Must contain 10 to 20 numbers!";
                        shell_exec("/usr/local/bin/gpio -g write 23 1");
                        shell_exec("/usr/local/bin/gpio -g write 12 1");
                        shell_exec("/usr/local/bin/gpio -g write 27 0");
                        sleep(2);
                        shell_exec("/usr/local/bin/gpio -g write 23 0");
                        shell_exec("/usr/local/bin/gpio -g write 12 0");
			exit;
		}

		$creationDate = date("Y-m-d H:i:s");

		$sql = "INSERT INTO client (first_name, last_name, email, phone_number, creation_date) VALUES ('$fName','$lName','$email', '$phone', '$creationDate')";

		if ($conn->query($sql) === TRUE)
		{
			echo "New record created!";
			shell_exec("/usr/local/bin/gpio -g write 27 1");
			shell_exec("/usr/local/bin/gpio -g write 23 0");
                        shell_exec("/usr/local/bin/gpio -g write 12 0");
			sleep(2);
  			shell_exec("/usr/local/bin/gpio -g write 27 0");
		}
		else
		{
			echo "ERROR: " . $conn->error;
			shell_exec("/usr/local/bin/gpio -g write 23 1");
			shell_exec("/usr/local/bin/gpio -g write 12 1");
			shell_exec("/usr/local/bin/gpio -g write 27 0");
			sleep(2);
                        shell_exec("/usr/local/bin/gpio -g write 23 0");
			shell_exec("/usr/local/bin/gpio -g write 12 0");

		}

		$conn->close();

}

?>

</body>

</html>
