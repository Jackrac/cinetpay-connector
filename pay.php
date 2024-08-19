<?php

    require 'utils.php';
?>

<?php
    $entryid = $_GET['entryid'];

    $error = '';

    $formEntryStatus = getFormEntryStatus($entryid);
    
    if(!$formEntryStatus['isOpen'])
    {
        $error = $formEntryStatus['error'];
    }
    else
    {
        $amonut = 0;
        
        if($formEntryStatus['formId'] === 'XXXXX')
        {
            $amonut = 10000;
        }
        else if($formEntryStatus['formId'] === 'YYYYY')
        {
            $amonut = 52000;
        }
        
        $url = 'https://api-checkout.cinetpay.com/v2/payment';
        $data = [
            "apikey" => $apikey,
            "site_id" => $site_id,
            "transaction_id" => strval(floor(((float)rand()/(float)getrandmax()) * 100000000)),
            "amount" => $amount,
            "currency" => "XOF",
            "description" =>  " EAA PAIEMENT ", // PRODUCTION ( Apres avant la mise en PROD)
            "customer_id" =>  $entryid,
            "notify_url" =>  "https://eaa.ci/paiement/notify.php",
            "return_url" =>  "https://eaa.ci/paiement-en-ligne-ext",
            "channels" =>  "ALL",
            "lang" =>  "FR",
            "metadata" => $entryid
        ];

        $response = getJson($url, $data);
        $error = $response['error'];
        $payment_url = $response['response']['data']['payment_url'];

        if($error === '')
        {
            header('Location: ' . $payment_url, true, 302);
            exit();
        }
    }
?>

<?php if($error === ''): ?>
    <p style="text-align:center">Si vous n'etes pas redirigé automatiquement vers le site de paiement</p>
    <p style="text-align:center"><a href="<?= $payment_url ?>">Cliquez sur ce lien</a></p>
<?php else: ?>
    <p style="text-align:center"><?= $error ?></p>
<?php endif; ?>
