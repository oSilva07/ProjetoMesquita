<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        

        body {
            font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            display: flex;
            width: 100%;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        @media screen and (max-width: 768px){
            body{
                width: 100%;
                flex-direction: column;
            }               
        }

        .container{
            display: flex;
            flex-direction: column;  
            justify-content: center; 
            align-items: center;     
            width: 100%;
            height: 100vh;           
            padding: 0 20px;         
        }        

        .Logo{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .Logo img {
            max-width: 100%;
            height: 400px;
            transform: translateY(-70px); /* Sobe a imagem levemente */
            margin-bottom: -50px;
        }


        .login-container {

            display: center;
            background:rgb(255, 255, 255);
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transform: translateY(-110px);
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #9d4edd;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color:rgb(197, 153, 233);
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }
       

        
    </style>
</head>

<body>

<div class="container">
    
        <div class="Logo">
        <img src="../Imagens/LogoP" alt="LogoP">
        </div>

    <div class="login-container">

        <h1>Login</h1>
        <form action="../backend/process_login.php" method="POST">

            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
            </div>
        
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="login-btn">Entrar</button>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message" id="error-message" style="display: block;">
                <?php if ($_GET['error'] === 'invalid_login'): ?>
                    Usuário não encontrado.
                <?php elseif ($_GET['error'] === 'invalid_password'): ?>
                    Senha incorreta.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>


</div>
</body>
</html>