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
$message = '';
 include("Storage.php");
 $jsonIO = new JsonIO('cards.json');
 //$stor = new Storage($jsonIO);
 $data = $jsonIO -> load();
 $username = $_GET['userId'] ?? null;
 $jsonIO1 = new JsonIO('users.json');

 //$storage2 = new Storage($jsonIO1);
 $users = $jsonIO1 -> load();

 $loggedIn = false;
 $userId1 = null;
foreach ($users as $userId =>$user) {
    if ($userId === $username) {
        $loggedIn = true;
        $userId1 = $userId;
        break;
    }
}

$adminCards = [];
foreach ($users as $userId => $user) {
    if ($users[$userId]['isAdmin']) {
        $adminId = $userId;
        $adminCards = $users[$userId]['cards'];
        break;
    }
}

//// buying logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cardId'])) {
    $cardId = $_POST['cardId'];

    // Checking if the user can afford the card
    if ($users[$userId1]['money'] >= $data[$cardId]['price']) {
        if (count($users[$userId1]['cards']) >= 5) {
            $message = "You can't have more than 5 cards!";
            $messageColor = 'red';
        } else {
        // Reducing the price of the card from the user's money
        $users[$userId1]['money'] -= $data[$cardId]['price'];

        // Adding the card to the user's cards
        $users[$userId1]['cards'][] = $cardId;

        // Removing the card from the admin's cards
    $key = array_search($cardId, /*$adminCards*/$users[$adminId]['cards']);
        if ($key !== false) {
            unset($users[$adminId]['cards'][$key]);
            // unset($adminCards[$key]);
            // $users[$adminId]['cards'] = $adminCards;
        }
       $jsonIO1->save($users);
       $cardName = $data[$cardId]['name'];
       $message = "You successfully bought the card: $cardName!";
       $messageColor = 'green';
    }
    } else {
        $cardName = $data[$cardId]['name'];
        $message = "You don't have enough money to buy the card: $cardName !";
        $messageColor = 'red';
    }

}



    








 /////
 
//  $stor = new Storage(new JsonIO('cards.json'));
//  $data = $stor -> findAll();
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

 
 
//checking admincards



?>

<!--<ul id="list">   -->

<!-- menu bar -->
<header class="header">
<?php if ($loggedIn): ?>
    <!-- <div id="profile-container"> -->
    <a id="profile-container" href="user.php?username=<?= $users[$userId1]['name'] ?>"><img id="proimage"  src="icons/profile-user.png"  > <h3 id="profile-name"><?= $users[$userId1]['name'] ?></h3></a>
    
    <!-- </div> -->
    <div id="usermoney"><img id="iconimgmenu"  src="icons/money.png"  >
    <?php if ($users[$userId1]['isAdmin']): ?>
        <b>Admin</b>
    <?php else: ?>
        <b><?= $users[$userId1]['money']?></b>
    <?php endif; ?>    
    </div>
<?php endif; ?>
      <!-- <a href="#">
        <img class="logo" alt="Game  logo" src="img/Game_Logo.jpg" />
      </a> -->

      <nav class="main-nav">
        <ul class="main-nav-list">
            
        <?php if (!$loggedIn): ?>
          <li><a class="main-nav-link nav-cta" href="signup.php" >Sign up</a></li>
          <li>
            <a class="main-nav-link" href="logIn.php">login</a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
</header>
<!-- filtering cards -->
<div id="filter">
    <div id="fitericon" > <img  id="filterimg" src="icons/selective.png"> </div>
    <select id="filterCards" onchange="filterCards(this.value)">
        <option value="">Show all</option>
        <option value="water">Water</option>
        <option value="bug">Bug</option>
        <option value="poison">Poison</option>
        <option value="normal">Normal</option>
        <option value="fire">Fire</option>
        <option value="electric">Electric</option>
        <option value="grass">Grass</option>
    </select>
</div>
<?php if($loggedIn && $userId1!=='admin'): ?>
<!-- explaining when you can buy a card and when you can't buy it -->
<div id ="explain-buy">
    <div id="green-price"> <div id="green-rect"></div> <div id="green-text">Card is available and you can buy it.</div></div>
    <div id="red-price"> <div id="red-rect"></div> <div id="red-text">Card is available but you don't afford it.</div></div>
    <div id="grey-price"><div id="grey-rect"></div> <div id= "grey-text">Card is not available</div></div>

    </div>
    <?php endif ?>
<!-- message for buying a card added timer so that it doesnt stay forever-->
<?php if (!empty($message)): ?>
    <div id="message" style="color: <?php echo $messageColor ?>; font-weight: bold;">
        <?php echo $message ?>
        <script type="text/javascript">
        setTimeout(function() {
            document.getElementById('message').style.display = 'none';
        }, 5000);
    </script>
    </div>
 <?php endif?>
<!-- <div id="menubar">
    <div id="loginmenu">LogIn</div>
</div> -->
<div id="cards-container">
<div id="cards"> 
<?php foreach($data as $cardId =>$ws): ?>

    <div id="card" ><div id="first" style="background-color:<?=$colorlist[$ws['type']] ?>">
    <a href="description.php?cardname=<?= $ws['name'] ?>"><img id="pokimage" src="<?= $ws['image'] ?>"></a>
    <!-- <style>#pokimage{
    //    background-color : ;
    //} </style> -->
</div>
<div id = "mid">
<a  style="text-decoration:none"  href="description.php?cardname=<?= $ws['name'] ?>"><div id="name"> <?= $ws['name']?></div></a>
<div id="power"><img  id="iconimg" src="icons/tag.png"> <?= $ws['type']?></div>

<div id="strength">
    <div id="hp"><img id="iconimg"  src="icons/heart1.png"  > <?= $ws['hp']?></div>
    <div id="attack"><img id="iconimg"  src="icons/swords.png"  ><?= $ws['attack']?></div>
    <div id="defence"><img id="iconimg"  src="icons/shield.png"  > <?= $ws['defense']?></div>
</div>
</div>
<?php if($loggedIn&& in_array($cardId, $adminCards) && !in_array($cardId, $users[$userId1]['cards'])):?>
    <form id="purchaseForm<?= $cardId ?>" action="" method="post">
                <input type="hidden" name="cardId" value="<?= $cardId ?>">
            </form>
    <a onclick="confirmPurchase('<?= $ws['name'] ?>','<?= $cardId ?>')"><?php endif ?><div id="price" 
    <?php 
    if($loggedIn  && in_array($cardId, $adminCards) && !in_array($cardId, $users[$userId1]['cards'])) {
        if($users[$userId1]['money'] >= $ws['price']) {
            echo 'style="background-color:green"';
        } else {
            echo 'style="background-color:red"';
        }
    }
?>><img id="iconimg"  src="icons/money-bags.png"  > <?= $ws['price']?></div>  <?php if($loggedIn):?></a> <?php endif ?>

 </div>
<?php endforeach ?>
<?php if($loggedIn && $users[$userId1]['isAdmin']): ?>
    <div id="card">
        <a href="newCard.php?admin=<?= $userId1 ?>">
            <div id="firstadd">
                <img id="pokimage" src="icons/add.png">
            </div>
            <div id="midadd">
                <div id="addtext">Create New Card</div>
            </div>
        </a>
    </div>
<?php endif; ?>
</div>
</div>





<script>
    const card = document.querySelector("#list")
    const newcard = document.createElement('li');
    const newcarddet = document.createElement('div');
    const image = document.createElement('img') ;
    const name = document.createElement('h2');
    const power = document.createElement('div');

    const price =document.createElement('div');

    newcard.appendChild

    function confirmPurchase(name,cardId) {
        <?php if($loggedIn): ?>
    var r = confirm("Are you sure you want to purchase " + name + "?");
    if (r == true) {
        document.getElementById('purchaseForm' + cardId).submit();
    }
    <?php else: ?>
        var r = confirm('You must be logged in to purchase cards! Would you like to log in now?');
    if (r == true) {
        window.location.href = 'login.php';
    }
        <?php endif ?>
}


//filtercards
function filterCards(type) {
    // Get all card elements
    var cards = document.querySelectorAll('#card');

    // Loop through all card elements
    for (var i = 0; i < cards.length; i++) {
        // Get the type of the current card
        var cardType = cards[i].querySelector('#power').textContent.trim();

        // If the type of the current card matches the selected type, or if the selected type is an empty string, we show the card
        if (cardType === type || type === '') {
            cards[i].style.display = 'block';
        } else {
            // Otherwise, hide the card
            cards[i].style.display = 'none';
        }
    }
}
</script>
</body>
</html>
