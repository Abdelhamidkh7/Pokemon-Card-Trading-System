<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="signup.css">
   
</head>
<body>
<?php
$errors ;
include("Storage.php");
$jsonIOusers = new JsonIO('users.json');
$users = $jsonIOusers -> load();


$errors = [];
$name = $_POST["name"]?? '';
$email = $_POST["email"]?? '';
$password = $_POST["password"]?? '';
$cpassword = $_POST["confirmpassword"]?? '';
$success =false;

//for name
if(empty($name)){
    $errors['name']= "Name cant be empty!!";

}
elseif(preg_match('/\s/',$name)){
    $errors['name']= "Username cant have emptyspaces";


}
//checking if username unique
foreach ($users as $user) {
    if ($user['name'] == $name) {
        $errors['name'] = "Username already exists!";
        break;
    }
}

///for email
if(empty($email)){
    $errors['email']= "Email cant be empty";
}
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email']= "Wrong email format!!";
}
if(empty($password)){
    $errors['password']="password cant be empty!!";
}
if(empty($cpassword)){
    $errors['confirmpassword']="password cant be empty!!";
}
if($password!==$cpassword){
    $errors['confirmpassword']="passwords dont match!!";
}

if(empty($errors)){
    $success=true;

    $newUser = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'isAdmin' => false,
        'money' => 500,
        'cards' => []
        
    ];
     //Adding a custom key for the new user
     $userKey = 'user' . (count($users) + 1);
     $users[$userKey] = $newUser;

    
    $jsonIOusers->save($users);

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




<div class="container">
<div class="registration form">
      <header>Signup</header>
      <form action="#" method = "post">
      <span class="error">  <?= isset($_POST['name']) ? ($errors['name'] ?? '') : '';?> </span>
      <input type="text" placeholder="Enter a username" name="name" id="name" value="<?= $name ?? ''?>">
      <span class="error"> <?= isset($_POST['email']) ? ($errors['email'] ?? '') : '';?> </span>
        <input type="email" placeholder="Enter your email" name="email" id="email" value="<?= $email ?? ''?>">
        <span class="error"> <?= isset($_POST['password']) ? ($errors['password'] ?? '') : '';?> </span>
        <input type="password" placeholder="Create a password"  name="password" id="password" value="<?= $password ?? ''?>">
        <span class="error"> <?= isset($_POST['confirmpassword']) ? ($errors['confirmpassword'] ?? '') : '';?> </span>
        <input type="password" placeholder="Confirm your password" name="confirmpassword" id="cpassword" value="<?= $confirmpassword ?? ''?>">
        <input type="submit" class="button" value="Signup">
      </form>
    
      <div class="signup">
      <span class="signup2">Signup and get <span class="bonus">500</span> coins for free!</span><br>
        <span class="signup">Already have an account?
         <label id="check"><a href="login.php"  >Login</a></label>
        </span>
      </div>
    </div>
</div>
    
</body>
</html>