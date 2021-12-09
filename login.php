<?php
require_once "pdo.php";


if (isset($_POST['login'])) {
    //Check if the username or password filled or no
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $message = '<label>Email and password are required</label>';
    } else if (isset($_POST['email']) && isset($_POST['password'])) { //Email verification
        if (!preg_match("/@/", $_POST['email'])) {
            $message = '<label>Email must have an at-sign (@)</label>';
        } else {
            $sql = "SELECT name FROM users WHERE email = :email AND password = :password";

            $stmt = $pdo->prepare($sql);

            $stmt->execute(array(
                ':email' => $_POST['email'],
                ':password' => $_POST['password']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($row === false) {
                error_log("Login fail ".$_POST['email']." $row");
                $message = '<label>Incorrect password</label>';
            } else {
                header("Location: autos.php?name=".urlencode($_POST['email']));
            }
        }
    }
}

//Redirection to index.php
if (isset($_POST['cancel'])) {
    header('Location:index.php');
}

?>
<html>
<head>
    <title>Lashen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (isset($message)) {
    echo '<label style="color: red">' . $message . '</label>';
}
?>
<h3>Please Log In</h3>
<form method="post">
    <p>User Name : <input type="text" name="email" size="40"></p>
    <p>Password : <input type="password" name="password"></p>
    <input type="submit" name="login" value="Login">
    <input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>

