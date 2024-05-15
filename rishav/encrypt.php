<?php
include  "connection.php";

if(isset($_POST['submit'])) {
    // Retrieve form data
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
    $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $consumerName = isset($_POST['consumerName']) ? $_POST['consumerName'] : '';
    $returnUrl = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '';
    $webhookUrl = isset($_POST['webhookUrl']) ? $_POST['webhookUrl'] : '';

    // Insert data into database
    $sql = "INSERT INTO payloads (amount, mobile, orderId, email, consumerName, returnUrl, webhookUrl)
            VALUES ('$amount', '$mobile', '$orderId', '$email', '$consumerName', '$returnUrl', '$webhookUrl')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Assuming your JSON payload is stored in a variable called $payload_string
    $payload_string = "{\"amount\": $amount, \"mobile\": \"$mobile\", \"orderId\": \"$orderId\", \"email\": \"$email\", \"consumerName\": \"$consumerName\", \"returnUrl\": \"$returnUrl\", \"webhookUrl\": \"$webhookUrl\"}";

    // Encode the JSON payload
    $payload = json_encode($payload_string);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-out.shop-se.in/merchant/secure/api/v1/encryptAndDecrypt',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode(array(
        "secretKey" => "h48Ivw+fmWyDU1qL299rXlRVb5XULVvdi35IUGgLGn8=",
        "ivKey" => "dOjsjPo+lGRQ7aUVcgYj1g==",
        "payload": "{\"amount\": 1.00, \"mobile\": \"8861650714\", \"orderId\": \"SCULPORD-000024\", \"email\": \"test@gmail.com\"}"
        "type" => "encrypt"
      )),
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic MDE1NzI6ZTkxZWFmN2YtYTkwNC00NDlkLWEzMmItOTA4ZWYyMjJmYTgx',
        'x-api-key: MY2uUdJrdyLLcpvKmdjaj0MYFFnRvhgMmYDAbFY6',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
   echo $response;
}
?>

