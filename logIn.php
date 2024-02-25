<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="log.css">
</head>
<body>
    <?php
    include("Storage.php");
    $jsonIOusers = new JsonIO('users.json');
    $users = $jsonIOusers -> load();


    $errors = [];
    $name = $_POST["name"]?? '';
    $email = $_POST["email"]?? '';
    $password = $_POST["password"]?? '';
    $success =false;

    $authenticated = false;

foreach ($users as $user) {
    if ($user['name'] == $name && $user['password'] == $password) {
        $authenticated = true;
        break;
    }
}
    if(empty($name)){
        $errors['name']= "Name cant be empty!!";

    }
    elseif(preg_match('/\s/',$name)){
        $errors['name']= "Username cant have emptyspaces";


    }
    // foreach ($users as $user) {
    //     if ($user['name'] == $name) {
    //         $errors['name'] = "Username already exists!";
    //         break;
    //     }
    // }

    ///for email
    // if(empty($email)){
    //     $errors['email']= "Email cant be empty";
    // }
    // elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    //     $errors['email']= "Wrong email format!!";
    // }
    if(empty($password)){
        $errors['password']="password cant be empty!!";
    }
    if (empty($errors)&&!$authenticated) {
        $errors['name'] = "Invalid username or password!";
    }
    
    if(empty($errors)&& $authenticated){
        $success=true;
        $userId1 = null;
    foreach ($users as $userId =>$user) {
        if ($user['name'] === $name) {
            $loggedIn = true;
            $userId1 = $userId;
            break;
        }
    }
        header("location: index.php?userId=$userId1");
       // header('location:devis.php?id='.$id.'&date='.$date);
    }
    ?>



    
    <div class="login-page">
    <div class="form">
    
    <form class="login-form" method = "post">
    <header>LogIn</header>
    <?= isset($_POST['name']) ? ($errors['name'] ?? '') : '';?>
      <input type="text" placeholder="username" type="text" name="name" id="name" value="<?= $name?>">
      <!-- 
      <input type="email" placeholder="email" name="email" id="email" value="<?= $email?>"> -->
      <?= isset($_POST['password']) ? ($errors['password'] ?? '') : '';?>  
      <input type="password" placeholder="password" name="password" id="password" value="<?= $password?>">
        
        <button type="submit" value="Log In"> Log In</button>
      <p class="message">Not registered? <a href="signup.php">Create an account</a></p>
    </form>
  </div>
</div>
    
</body>
</html>