<?php
require 'db.php';
$erreurs = [];

if (isset($_POST['submit'])) {
    $firstname = ($_POST['firstname']);
    $lastname = ($_POST['lastname']);
    $email = ($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $hpassword = password_hash($password, PASSWORD_DEFAULT);
    $idUser = substr(uniqid(), -4);

    if (empty($firstname)) {
        $erreurs['firstname'] = "Fill in the first name";
    }
    if (empty($lastname)) {
        $erreurs['lastname'] = "Fill in the last name";
    }
    if (empty($email)) {
        $erreurs['email'] = "Fill in the email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "Invalid email format";
    }
    if (empty($password)) {
        $erreurs['password'] = "Fill in the password";
    }
    if ($password !== $confirm_password) {
        $erreurs['confirm_password'] = "Passwords do not match";
    }

    if (empty($erreurs)) {
        $check_email = $pdo->prepare("SELECT * FROM utilisateur WHERE mailUser = :email");
        $check_email->bindParam(':email', $email);
        $check_email->execute();

        if ($check_email->rowCount() > 0) {
            $erreurs['email'] = "This email is already registered";
        } else {
            $insert_client = $pdo->prepare("INSERT INTO utilisateur(idUser, nomUser, prenomUser, mailUser, motPasse) 
             VALUES(:idUser, :nomUser, :prenomUser, :mailUser, :motPasse)");
            $insert_client->bindParam(':idUser', $idUser);
            $insert_client->bindParam(':prenomUser', $firstname);
            $insert_client->bindParam(':nomUser', $lastname);
            $insert_client->bindParam(':mailUser', $email);
            $insert_client->bindParam(':motPasse', $hpassword);
            $insert_client->execute();

            echo "<p style='color: green; text-align: center;'>Registration successful!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
<form action="register.php" method="POST" class="auth-form">
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>">
        <span class="erreur"><?php echo isset($erreurs['firstname']) ? $erreurs['firstname'] : ''; ?></span>
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>">
        <span class="erreur"><?php echo isset($erreurs['lastname']) ? $erreurs['lastname'] : ''; ?></span>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        <span class="erreur"><?php echo isset($erreurs['email']) ? $erreurs['email'] : ''; ?></span>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <span class="erreur"><?php echo isset($erreurs['password']) ? $erreurs['password'] : ''; ?></span>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">
        <span class="erreur"><?php echo isset($erreurs['confirm_password']) ? $erreurs['confirm_password'] : ''; ?></span>
    </div>

    <button type="submit" name="submit">Register</button>
</form>

</body>
</html>