<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Cliente ZapSlim</title>
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap-theme.min.css" />
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            .zap-header {
                padding-bottom: 20px;
            }
            .zap-title {
                margin-top: 5px;
                margin-bottom: 0;
                font-size: 60px;
                font-weight: normal;
            }
            .zap-description {
                font-size: 20px;
                color: #999;
            }

            fieldset {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="zap-header">
                <h1 class="zap-title">Zap Zap Slim</h1>
                <p class="lead zap-description">Bate papo em broadcast com o Slim Framework.</p>
                <small>Meu IP: <label id="address-local"><?= $_SERVER["SERVER_ADDR"]; ?></label></small>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <fieldset>
                        <legend>Server</legend>
                        <div class="form-inline">
                            <div class="form-group">
                                <label for="input-meu-ip">Meu IP</label>
                                <input type="text" value="<?= $_SERVER['SERVER_ADDR']; ?>" class="form-control" id="input-meu-ip" placeholder="Ex: 10.16.0.1">                            
                            </div>
                            <div class="form-group">
                                <label for="input-ip-server">IP Server</label>
                                <input type="text" class="form-control" id="input-ip-server" placeholder="Ex: 10.16.0.1">                            
                            </div>
                            <button type="button" id="button-conectar-server" class="btn btn-primary">Conectar</button>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Enviar Mensagem</legend>
                        <form>
                            <div class="form-group">
                                <label for="input-nome">Seu nome:</label>
                                <input type="text" class="form-control" id="input-nome" placeholder="Digite aqui sua primavera...">
                            </div>
                            <div class="form-group">
                                <label for="textarea-mensagem">Mensagem de Texto</label>
                                <textarea id="textarea-mensagem" class="form-control" rows="6" placeholder="Digite aqui sua mensagem..."></textarea>
                            </div>
                            <button type="button" id="button-enviar-mensagem" class="btn btn-primary">Enviar</button>
                        </form>
                    </fieldset>
                </div>
                <div class="col-lg-6" style="height: 458px; overflow-y: auto;">
                    <fieldset>
                        <legend>Mensagens</legend>
                        <dl class="dl-horizontal" id="history-message">
                        </dl>
                    </fieldset>
                </div>
            </div>
        </div>

        <input type="hidden" id="input-hidden-uri-client" value="<?= str_replace("index.php", "RESTService.php", $_SERVER['PHP_SELF']); ?>" />
        <input type="hidden" id="input-hidden-uri-server" value="<?= str_replace("index.php", "RESTService.php", str_replace("cliente", "server", $_SERVER['PHP_SELF'])); ?>" />

        <script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <script>
            $('document').ready(function () {
                $('#button-conectar-server').click(function () {
                    $.post("http://" + $("#input-ip-server").val() + $("#input-hidden-uri-server").val() + "/client/connect",
                            {ip: $("#input-meu-ip").val(), address: "http://" + $("#input-meu-ip").val() + $("#input-hidden-uri-client").val()})
                    .done(function (data) {
                        alert(data.return);
                    })
                    .fail(function () {
                        alert("error");
                    });
                });
                
                $('#button-enviar-mensagem').click(function () {
                    $.post("http://" + $("#input-ip-server").val() + $("#input-hidden-uri-server").val() + "/server/sendmessage",
                            {name: $("#input-nome").val(), message: $("#textarea-mensagem").val()})
                    .done(function (data) {
                        alert(data.return);
                    })
                    .fail(function () {
                        alert("error");
                    });
                });
                
                setInterval(function() {
                    $.get("http://" + $("#input-meu-ip").val() + $("#input-hidden-uri-client").val() + "/client/checkmessage")
                    .done(function (data) {
                        var html = "";
                        if (!data.err) {
                            $.each($.parseJSON(data.return), function(idx, obj) {
                                html += "<dt>" + obj.name + "</dt>";
                                html += "<dd>" + obj.message + "</dd>";
                                $("#history-message").append(html);
                            });
                        }
                    })
                    .fail(function () {
                        console.log("error");
                    });
                }, 500);
            });
        </script>
    </body>    
</html>
