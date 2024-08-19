<?php
    require 'utils.php';
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo "Pong!";
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $url = 'https://api-checkout.cinetpay.com/v2/payment/check';
        $data = [
            "apikey" => $apikey,
            "site_id" => $site_id,
            "transaction_id" => $_POST['transaction_id']
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
            $entryid = $response['response']['data']['metadata'];

            $formEntryStatus = getFormEntryStatus($entryid, $errorIfEntryPaid = false);

            if(!$formEntryStatus['isOpen'])
            {
                echo $formEntryStatus['error'];
                header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
            }
            else if($transationStatus === "ACCEPTED")
            {
                setFormEntryStatus($entryid, "Paye");
            }
            else if($transationStatus === "REFUSED")
            {
                setFormEntryStatus($entryid, "Refuse");
            }
        }
    }
    else
    {
        echo "Verbe HTTP non supporté";
        header('HTTP/1.1 400 Bad Request', true, 400); exit();
    }
?>

<?php if($transationStatus === "ACCEPTED"): ?>
    <p style="text-align:center">Félicitations !</p>
    <p style="text-align:center">Votre paiement a ete éffectué avec succès</p>
    <p style="text-align:center"><a href="https://eaa.ci/">Retour a la page d'accueil</a></p>
<?php else: ?>
    <p style="text-align:center">Il semble que votre paiement ait malheureusement échoué</p>
    <p style="text-align:center">Veuillez refaire un nouvelle demande</p>
    <p style="text-align:center"><a href="https://eaa.ci/">Retour a la page d'accueil</a></p>
<?php endif; ?>