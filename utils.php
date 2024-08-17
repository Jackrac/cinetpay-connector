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
?>



