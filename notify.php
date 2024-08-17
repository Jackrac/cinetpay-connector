<?php
    $apikey = "YOUR_APIKEY";
    $site_id = "YOUR_SITEID";
?>

<?php
    include 'utils.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo "Pong!";
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if($_POST['cpm_site_id'] != $site_id)
        {
            echo "Mauvaise reference de site";
            header('HTTP/1.1 400 Bad Request', true, 400); exit();
        }

        // TODO : Checker le statut du formulaire en base s'il est fermé CinetPay pour valider le status de la transaction
        /* TODO : Appeler CinetPay pour valider le status de la transaction ou faire la verification hash_hmac
           (https://docs.cinetpay.com/api/1.0-fr/checkout/hmac) */

        $url = 'https://api-checkout.cinetpay.com/v2/payment/check';
        $data = [
            "apikey" => "YOUR_APIKEY",
            "site_id" => "YOUR_SITEID",
            "transaction_id" => $_POST['cpm_trans_id']
        ];
    
        $options = [
            'http' => [
                'header' => 'Content-Type: application/json',
                'method' => 'POST',
                'content' => json_encode( $data ),
            ],
        ];
           
        $response = getJson($url, $data);
        if($response['error'] != '')
        {
            echo $response['error'];
            header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
        }
        else
        {
            $transationStatus = $response['response']['data']['status'];

            // Changer le statut du formulaire en base
            if($transationStatus == "ACCEPTED")
            {

            }
            else if($transationStatus == "REFUSED")
            {
            
            }
        }
    }
    else
    {
        echo "Verbe HTTP non supporté";
        header('HTTP/1.1 400 Bad Request', true, 400); exit();
    }
?>



