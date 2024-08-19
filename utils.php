<?php
    // Chargement de l'environnement Wordpress
    require __DIR__ . '/../wp-blog-header.php';

    // Constantes
    $apikey = "YOUR_APIKEY";
    $site_id = "YOUR_SITEID";
?>

<?php
    function getJson($url, $data)
    {
        $options = [
            'http' => [
                'header' => 'Content-Type: application/json',
                'method' => 'POST',
                'content' => json_encode( $data ),
            ],
        ];

        $response = [];
        $error = '';

        $context = stream_context_create($options);
        $responseString = file_get_contents($url, false, $context);

        if ($responseString === false) {
            $error = "Une erreur s'est produite pendant l'execution de la requete dans le serveur de transaction";
        }
        else
        {
            if(!json_validate($responseString))
            {
                $error = "La reponse du serveur de transaction n'est pas valide";
                // TODO : dumper la reponse dans un fichier de Log sur le serveur
            }
            else
            {
                $response = json_decode($responseString, true);
            }
        }

        return [
            'response' => $response,
            'error' => $error
        ];
    }

    function getFormEntryStatus($entryid, $errorIfEntryPaid = true)
    {
        /* 1. On recherche l'entree de formulaire dont l'ID est passe en parametre */
        $form_status = wpFluent()
        ->table('fluentform_submissions')
        ->where('id', $entryid)
        ->select(['fluentform_submissions.status', 'fluentform_submissions.form_id'])
        ->first();

        $error = '';
        $isOpen = false;
        $formId = '';

        /* 2. Si l'entree de formulaire n'existe pas, on retourne un message d'erreur */
        if($form_status == null)
        {
            $error = "Formulaire introuvable";
        }
        /* 3. Si l'entree de formulaire est deja fermee (c-a-d payee), on retourne un message d'erreur */
        else if($form_status->status === "Paye" && $errorIfEntryPaid)
        {
            $error = "Formulaire deja complete";
            $formId = strval($form_status->form_id);
        }
        /* 4. Sinon (c-a-d l'entree de formulaire existe bien et n'est pas encore fermee), on cree une transaction et on affiche la page CinetPay */
        else
        {
            $isOpen = true;
            $formId = strval($form_status->form_id);
        }

        return [
            'isOpen' => $isOpen,
            'formId' => $formId,
            'error' => $error
        ];
    }

    function setFormEntryStatus($entryid, $status)
    {
        wpFluent()
        ->table('fluentform_submissions')
        ->where('id', $entryid)
        ->update(['status' => $status]);
    }
?>



