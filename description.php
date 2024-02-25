<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body >
<?php
 include_once("Storage.php");
//include("index.php"); 
 $stora = new Storage(new JsonIO('cards.json'));
 $data = $stora -> findAll();
 $colorlist =  [
    "water"=> "blue",
    "bug"=> "burlywood",
    "poison"=> "purple",
    "normal"=> "grey",
    "fire"=> "orange",
    "electric"=> "yellow",
    "grass"=> "lightgreen",
    "fighting" => "Red",
    "ground" => "Brown",
    "flying" => "SkyBlue",
    "rock" => "Chocolate",
    "ghost" => "Plum",
    "ice" => "Aqua",
    "psychic" => "Fuchsia",
    "dragon" => "Green",
    "dark" => "Black",
    "steel" => "Silver",
    "fairy" => "Pink",
    "stellar" => "Navy",
    "shadow" => "MidnightBlue"


 ];
foreach($data as $cardkey =>$card){
        if($card['name']==$_GET['cardname']){
            $cardname = $card['name'];
            $cardtype = $card['type'];
            $cardimage = $card['image'];
            $cardhp = $card['hp'];
            $cardattack = $card['attack'];
            $carddefense = $card['defense'];
           
            $cardprice = $card['price'];
            $carddescription = $card['description'];

        }
 };
//  $username = $_GET['username'] ?? null;
//  $storage2 = new Storage(new JsonIO('users.json'));
//  $users = $storage2 -> findAll();
//  $loggedIn = false;
// foreach ($users as $user) {
//     if ($user['name'] === $username) {
//         $loggedIn = true;
//         break;
//     }
// }

?>
<!-- <header class="header">
      <a href="#">
        <img class="logo" alt="Game  logo" src="img/Game_Logo.jpg" />
      </a>

      <nav class="main-nav">
        <ul class="main-nav-list">
         // 
          <li><a class="main-nav-link nav-cta" href="signup.html" >Sign up</a></li>
          <li>
            <a class="main-nav-link" href="logIn.php">login</a>
          </li>
 
        </ul>
      </nav>
</header> -->

<div id="carddes" style="background-color:<?=$colorlist[$cardtype] ?? ''?>" >
<button class="exit-button" onclick="goBack()">&times;</button>
    <img class="card-image" src="<?= $cardimage ?>" alt="">
    <h2 class="card-name">Name: <?= $cardname ?></h2>
    <p class="card-type"><b>Type:</b> <?=$cardtype ?></p>
    <div class="card-stats">
    <p class="card-hp"><b>HP:</b> <?=$cardhp ?></p>
    <p class="card-type"><b>Attack:</b> <?=$cardattack ?></p>
    <p class="card-type"><b>Defence:</b> <?=$carddefense ?></p>
</div>
    <p class="card-price"><b>Price:</b> <?= $cardprice ?></p>
    <p class="card-description"><b>About Card:</b> <?= $carddescription ?></p>
</div>

    
</body>
<script>
function goBack() {
    window.history.back();
}
</script>
</html>