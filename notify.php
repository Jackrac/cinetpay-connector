<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo "Pong!";
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if($_POST['cpm_site_id'] != "YOUR_SITEID")
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
           
           $context = stream_context_create($options);
           $responseString = file_get_contents($url, false, $context);
           if ($responseString === false) {
               echo "La récupération du statut de la transaction a échoué";
               header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
            }
           else
           {
               if(!json_validate($responseString))
               {
                   echo "La reponse du serveur de transaction n'est pas valide";
                   header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
               }
               else
               {
                   $responseJson = json_decode($responseString, true);
                   $transationStatus = $responseJson['data']['status'];

                   if($transationStatus == "ACCEPTED")
                   {

                   }
                   else if($transationStatus == "REFUSED")
                   {
                    
                   }
               }
           }
    }
    else
    {
        echo "Verbe HTTP non supporté";
        header('HTTP/1.1 400 Bad Request', true, 400); exit();
    }
?>



