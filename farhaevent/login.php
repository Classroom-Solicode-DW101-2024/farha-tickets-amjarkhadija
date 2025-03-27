

<?php
require 'db.php'; 

$erreurs=[];
if(isset($_POST["connecter"])){
    $mail =$_POST["mail"];
    $motPasse =$_POST["motPasse"];
    if(empty($mail)){
        $erreurs['email'] = "Veuillez saisir votre adresse mail";
    }
     if(empty($motPasse)){
        $erreurs['motPasse'] = "Veuillez saisir votre mot de passe";
    }
    if(empty($erreurs)){
        $req = $pdo->prepare("SELECT * FROM utilisateur WHERE mailUser = ? AND motPasse = ?");
        $req->execute([$mail, $motPasse]);
        $resultat =$req->fetchAll(PDO::FETCH_ASSOC);
    if(count($resultat)>0){
        header("Location:index.php");
    }else{
       $erreurs['userExiste'] = "Email ou  mot de passe incorrect";
    }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container { 
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px #4a148c;
            text-align: center;
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #fffff;
        }
        .error {
            color: #4a148c;
            margin-bottom: 10px;
        }
        label {
            color: #fffff;
            display: block;
            text-align: left;
            margin-top: 10px;
        }
        input {
            width: 100%;
            margin-left:-6PX;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4a148c;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            box-shadow: 0 0 10px #4a148c;
        }
    </style></style>
</head>
<body>
    <?php if(!empty($erreurs)):
         foreach($erreurs as $erreur=>$ere):?>
           <p><?=$ere?></p>      
     <?php endforeach; 
      endif; ?>
<form method="POST" action="">
        <div class="form-group">
            <label for="mail">Email :</label>
            <input type="email" id="mail" name="mail" value="" >
        </div>
        <div class="form-group">
            <label for="motPasse">Mot de passe :</label>
            <input type="password" id="motPasse" name="motPasse" >
        </div>
        <button type="submit" name="connecter">Se connecter</button>
    </form>
    <div class="register-link">
        <p>Pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
    </div>
    
</body>
</html>