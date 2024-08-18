<?php
    require 'utils.php';
?>

<?php
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

        $formEntryStatus = getFormEntryStatus($_POST['cpm_custom']);
    
        if(!$formEntryStatus['isOpen'])
        {
            echo $formEntryStatus['error'];
            header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
        }
        else
        {
            $url = 'https://api-checkout.cinetpay.com/v2/payment/check';
            $data = [
                "apikey" => "YOUR_APIKEY",
                "site_id" => "YOUR_SITEID",
                "transaction_id" => $_POST['cpm_trans_id']
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

                $formEntryStatus = getFormEntryStatus($entryid);
    
                if(!$formEntryStatus['isOpen'])
                {
                    echo $formEntryStatus['error'];
                    header('HTTP/1.1 500 Internal Server Error', true, 500); exit();
                }
                else if($transationStatus == "ACCEPTED")
                {
                    setFormEntryStatus($entryid, "Paye");
                }
                else if($transationStatus == "REFUSED")
                {
                    setFormEntryStatus($entryid, "Refuse");
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
