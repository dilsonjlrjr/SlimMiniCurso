<?php

require_once '../../vendor/autoload.php';

define("TMP_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR . "tmp");

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

function ipJaGravado(array $arrayIP, $ip) {
    foreach ($arrayIP as $infoIP) {
        if ($infoIP['ip'] === $ip) {
            return TRUE;
        }
    }
    return FALSE;
}

$app->group('/client', function() use($app) {
    $app->post('/connect', function() use($app) {
        $ip = $app->request->post("ip");
        $address = $app->request->post("address");
        $bErr = FALSE;
        if ($ip !== NULL) {
            $arrayJson = array();
            if (file_exists(TMP_PATH . DIRECTORY_SEPARATOR . "clients.json")) {
                $arrayJson = json_decode(file_get_contents(TMP_PATH . DIRECTORY_SEPARATOR . "clients.json"), TRUE);
            }
            
            $msgReturn = "";
            if (ipJaGravado($arrayJson, $ip)) {
                $msgReturn = "IP já cadastrado.";
            } else {
                $arrayJson[] = array("ip" => $ip, "address" => $address);
                file_put_contents(TMP_PATH . DIRECTORY_SEPARATOR . "clients.json", json_encode($arrayJson));
                $msgReturn = "Ip Gravado com sucesso";
            }
        } else {
            $msgReturn = "IP é obrigatório.";
            $bErr = TRUE;
        }

        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode(array("return" => $msgReturn, "err" => $bErr)));
    });
});

$app->group('/server', function() use($app) {
    $app->post('/sendmessage', function() use($app) {
        $name = $app->request->post("name");
        $message = $app->request->post("message");

        if (file_exists(TMP_PATH . DIRECTORY_SEPARATOR . "clients.json")) {
            $arrayClients = json_decode(file_get_contents(TMP_PATH . DIRECTORY_SEPARATOR . "clients.json"), TRUE);

            foreach ($arrayClients as $client) {
                $objetoCurl = curl_init($client['address'] . "/client/receive");
                
                curl_setopt($objetoCurl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($objetoCurl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($objetoCurl, CURLOPT_POSTFIELDS, array('name' => $name, 'message' => $message));
                
                $retorno = curl_exec($objetoCurl);                
                curl_close($objetoCurl);

                file_put_contents(TMP_PATH . DIRECTORY_SEPARATOR . "log_envio.json", "Enviando Mensagem para (" . $client['ip'] . "): " . $retorno, FILE_APPEND);
            }
            
            $msgReturn = "Mensagem enviado com sucesso.";
            $bErr = FALSE;
        } else {
            $msgReturn = "Sem clientes cadastrados no servidor.";
            $bErr = TRUE;
        }

        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode(array("return" => $msgReturn, "err" => $bErr)));
    });
});

$app->get('/hello', function() {
    echo "Hello";
});

$app->run();
