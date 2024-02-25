<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("Storage.php");
$jsonIOusers = new JsonIO('users.json');
    $users = $jsonIOusers -> load();
$username = $_GET['username'] ?? null;


$loggedIn = false;
foreach ($users as $user) {
    if ($user['name'] === $username) {
        $loggedIn = true;
        $name = $user['name'];
        $email = $user['email'];
        $isadmin = $user['isAdmin'];
        $yourcards = $user['cards'];
        $money = $user['money'];
        break;
    }
}
if (!$loggedIn) {
    header('location: index.php');
    exit;
};

$jsonIOcards = new JsonIO('cards.json');
 $cards = $jsonIOcards -> load();
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

 

if (isset($_POST['card_id'], $_POST['sell_price'], $_POST['username'])) {
    $card_id = $_POST['card_id'];
    $sell_price = $_POST['sell_price'];
    $username = $_POST['username'];

    foreach ($users as &$user) {
        if ($user['name'] === $username) {
            // Removing the card from the user's cards
            $index = array_search($card_id, $user['cards']);
            if ($index !== false) {
                array_splice($user['cards'], $index, 1);
            }

            
            // Adding the sell price to the user's money
            $user['money'] += $sell_price;
        }

       
    

    // Adding the sold card to the admin's cards
    if ($user['isAdmin'] === true) {
        $user['cards'][] = $card_id;
    }
}
unset($user);
 
    $jsonIOusers->save($users);
    header("location: user.php?username=$username");
    exit;
}






?>
<h1>Profile</h1>
<button class="exit-button" onclick="goBack()">&times;</button>
<h3 >Name: <?= $name ?></h3>
<h3>Email: <?= $email ?></h3>
<div id="networth">
    <img id="cashimg" src="icons/money.png" alt="">
    <?php if ($isadmin): ?>
        <h2 id="h3text">You are admin</h2>
    <?php else: ?>
<h2 id="h3text">Net Worth: <?=$money?> </h2>
<?php endif; ?>
    </div>
<h3>Your cards</h3>
<div id="cards" >
<?php foreach ($yourcards as $card): ?>
 <?php  $crd = $cards[$card] ?>
    <div id="card" ><div id="first" style="background-color:<?=$colorlist[$crd['type']] ?>">
    <a href="description.php?cardname=<?= $crd['name'] ?>"><img id="pokimage" src="<?= $crd['image'] ?>"></a>
    <!-- <style>#pokimage{
    //    background-color : ;
    //} </style> -->
</div>
<div id = "mid">
<div id="name"> <?= $crd['name']?></div>
<div id="power"><img  id="iconimg" src="icons/tag.png"> <?= $crd['type']?></div>

<div id="strength">
    <div id="hp"><img id="iconimg"  src="icons/heart1.png"  > <?= $crd['hp']?></div>
    <div id="attack"><img id="iconimg"  src="icons/swords.png"  ><?= $crd['attack']?></div>
    <div id="defence"><img id="iconimg"  src="icons/shield.png"  > <?= $crd['defense']?></div>
</div>
</div>
<div id="price" style="background-color:green"><img id="iconimg"  src="icons/money-bags.png"   > <?= $crd['price']?>
<?php if (!$isadmin): ?>
<form action="" method="post" onsubmit="return confirm('Are you sure you want to sell this card?');">
        <input type="hidden" name="card_id" value="<?= $card?>">
        <input type="hidden" name="sell_price" value="<?= $crd['price'] * 0.9 ?>">
        <input type="hidden" name="username" value="<?= $username ?>">
        <input type="submit" value="Sell">
    </form>
<?php endif; ?>
</div>

 </div>
 
 

<?php endforeach; ?>
    </div>
<a id="logout" href="login.php">Log Out</a>

    
<script>
    // function to exit the page
function goBack() {
    $userId1 = null;
  <?php  foreach ($users as $userId =>$user) {
        if ($user['name'] === $name) {
            $loggedIn = true;
            $userId1 = $userId;
            break;
        }
    } ?>
    window.location.href = 'index.php?userId=<?= $userId1 ?>';
}
</script>
</body>
</html>

