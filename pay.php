<?php

    // TODO : juste pour les tests en local - a enlever avant mise en PROD
    if (!isset($_SERVER["HTTP_HOST"])) {
        parse_str('formid=10&amount=100', $_POST);
    }

    $error = '';

    // TODO : Checker si la form est encore ouverte et si le montant match sinon retourner une erreur (Variable $error)

    $url = 'https://api-checkout.cinetpay.com/v2/payment';
    $data = [
        "apikey" => "YOUR_APIKEY",
        "site_id" => "YOUR_SITEID",
        "transaction_id" => strval(floor(((float)rand()/(float)getrandmax()) * 100000000)), //
        "amount" => floatval($_POST['amount']),
        "currency" => "XOF",
        "description" =>  " TEST INTEGRATION ", // PRODUCTION ( Apres avant la mise en PROD)
        "customer_id" =>  $_POST['formid'],
        "notify_url" =>  "https://eaa.ci/paiement/notify.php",
        "return_url" =>  "https://eaa.ci/paiement/feedback.php?formid=" . $_POST['formid'],
        "channels" =>  "ALL",
        "lang" =>  "FR"
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
        $error = "La création de transaction pour le paiement a échoué";
    }
    else
    {
        if(!json_validate($responseString))
        {
            $error = "La reponse du serveur de transaction n'est pas valide";
        }
        else
        {
            $responseJson = json_decode($responseString, true);
            $paiementUrl = $responseJson['data']['payment_url'];
        }
    }
?>

<?php if($error == ''): ?>
    <iframe src="<?= $paiementUrl ?>" title="CinetPay Page"></iframe>
<?php else: ?>
    <span><?= $error ?></span>
<?php endif; ?>



