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
    }
?>

<?php if($error === ''): ?>
    <iframe src="<?= $payment_url ?>" title="CinetPay Page" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"></iframe>
<?php else: ?>
    <span><?= $error ?></span>
<?php endif; ?>
