<?php

require_once '../../vendor/autoload.php';

define("TMP_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR . "tmp");

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->group('/client', function() use($app) {
    $app->post('/receive', function() use($app) {
        $name = $app->request->post("name");
        $message = $app->request->post("message");
                
        $bErro = FALSE;

        if (($name !== NULL) && ($message !== NULL)) {
            $arrayJson = array();
            if (file_exists(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json")) {
                $arrayJson = json_decode(file_get_contents(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json"), TRUE);
            }
            $arrayJson[] = array(
                "name" => $name,
                "message" => $message
            );
            $retorno = file_put_contents(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json", json_encode($arrayJson));
            file_put_contents(TMP_PATH . DIRECTORY_SEPARATOR . "log_retorno.txt", "Recebendo Mensagem e gravando: " . $retorno, FILE_APPEND);
            $msgReturn = "Mensagem gravada com sucesso";            
        } else {
            $msgReturn = "Mensagem mal formatada";
            $bErro = TRUE;
        }

        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode(array("return" => $msgReturn, "err" => $bErro)));
    });
    
    $app->get('/checkmessage', function() use($app) {
        $bErro = FALSE;
        
        if (file_exists(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json")) {
            $msgReturn = file_get_contents(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json");
            unlink(TMP_PATH . DIRECTORY_SEPARATOR . "messages.json");
        } else {
            $msgReturn = "Sem mensagens";
            $bErro = TRUE;
        }
        
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode(array("return" => $msgReturn, "err" => $bErro)));
    });
});

$app->get('/hello', function() {
    echo "Hello";
});

$app->run();
