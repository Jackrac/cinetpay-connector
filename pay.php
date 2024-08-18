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
            "description" =>  " TEST INTEGRATION ", // PRODUCTION ( Apres avant la mise en PROD)
            "customer_id" =>  $entryid,
            "notify_url" =>  "https://eaa.ci/paiement/notify.php",
            "return_url" =>  "https://eaa.ci/paiement/feedback.php?entryid=" . $entryid,
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
    <iframe src="<?= $payment_url ?>" title="CinetPay Page"></iframe>
<?php else: ?>
    <span><?= $error ?></span>
<?php endif; ?>



