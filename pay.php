<?php
    $apikey = "YOUR_APIKEY";
    $site_id = "YOUR_SITEID";
?>

<?php
    include 'utils.php';

    // TODO : juste pour les tests en local - a enlever avant mise en PROD
    if (!isset($_SERVER["HTTP_HOST"])) {
        parse_str('formid=10&amount=100', $_POST);
    }

    $error = '';

    // TODO : Checker si la form est encore ouverte et si le montant match sinon retourner une erreur (Variable $error)

    $url = 'https://webhook-test.com/ff5379e252fc8aa70059329e536c0172';
    //$url = 'https://api-checkout.cinetpay.com/v2/payment';
    $data = [
        "apikey" => $apikey,
        "site_id" => $site_id,
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

    $response = getJson($url, $data);
?>

<?php if($response['error'] === ''): ?>
    <iframe src="<?= $response['response']['data']['payment_url'] ?>" title="CinetPay Page"></iframe>
<?php else: ?>
    <span><?= $response['error'] ?></span>
<?php endif; ?>



