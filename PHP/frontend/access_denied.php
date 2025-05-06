<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso negado</title>
</head>

<style>
    body{
        font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            display: flex;
            width: 100%;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
            margin: 0;
    }

    .container{
            display: flex;
            flex-direction: column;  /* Alinha os elementos na vertical */
            justify-content: center; /* Centraliza o conteúdo verticalmente */
            align-items: center;     /* Centraliza o conteúdo horizontalmente */
            width: 100%;
            height: 100vh;           /* Ocupa toda a altura da tela */
            padding: 0 20px;         /* Adiciona um pouco de padding nas laterais */
        } 

    .Logo{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .Logo img {
            max-width: 100%;
            height: 400px;
            transform: translateY(-160px); /* Sobe a imagem levemente */
            margin-bottom: -50px;
        }


    .negado{
            text-align: center;
            display: center;
            background:rgb(255, 255, 255);
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transform: translateY(-190px);

    }
    

</style>


<body>
    <div class="container">
        <div class="Logo">
        <img src="../Imagens/LogoP" alt="LogoP">
        </div>

        <div class="negado">

            <h1>Acesso Negado</h1>
        <Label>
            <a href="menu.php">Voltar para o menu</a>
        </Label>
        </div>  
    </div>
</body>
</html>