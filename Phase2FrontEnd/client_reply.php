<?php

header("Content-Type: application/json");


$email_user = "temphum21@gmail.com";
$email_pass = "gcmv rvsm hvxh ieuu";


$response = ["status" => "pending"];

try {
    
    $mail = @imap_open("{imap.gmail.com:993/imap/ssl}INBOX", $email_user, $email_pass);

    if (!$mail) {
       
        $response["status"] = "error";
        $response["message"] = imap_last_error();
        echo json_encode($response); 
        exit;  
    }

    
    $emails = imap_search($mail, 'UNSEEN SUBJECT "High Temperature"');

    if ($emails) {
        
        foreach ($emails as $email_number) {
            $msg = imap_fetchbody($mail, $email_number, 1);
            if (stripos($msg, "yes") !== false) {
                $response["status"] = "yes"; 
                break;  
            } elseif (stripos($msg, "no") !== false) {
                $response["status"] = "no";  
                break;  
            }
        }
    }

    
    imap_close($mail);

} catch (Exception $e) {
   
    $response["status"] = "error";
    $response["message"] = $e->getMessage();
}


echo json_encode($response);
exit;
?>
