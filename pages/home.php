<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>BotHeat</title>
    <script>const URL = '<?php echo config()['url']; ?>';</script>
  </head>
  <body>
    <style>
        .full-load {
            position:fixed;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 99999999;
        }
    </style>
    <div id="full-load" class="full-load d-none">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <img src="<?php img('loader.gif'); ?>" alt="">
                    <p>Aguarde...</p>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="<?php img('logo.png'); ?>" style="width: 50px;" alt="">
        </a>
        Gerenciamento de Perfis
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><!-- space --></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Novo Perfil <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="btn-div" class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center mt-5">
                <button id="new-profile" class="btn btn-primary btn-lg">Gerar Perfil</button>
            </div>
        </div>
    </div>

    <div id="profile-sel" class="container-fluid"></div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="<?php js("request"); ?>"></script>
    <script src="<?php js("api_comunicate"); ?>"></script>
  </body>
</html>