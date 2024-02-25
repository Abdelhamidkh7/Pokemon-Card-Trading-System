<!-- NOTE: -->
<!-- My Logic here is that each card is unique so I do not allow the admin to create 2 cards with the same name or the same image -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel= "stylesheet" href="newCard.css">
</head>
<body>
<?php
$userId = $_GET['admin'] ?? null;
include("Storage.php");
$jsonIOcards = new JsonIO('cards.json');
    $Cards = $jsonIOcards -> load();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $name = ucfirst(strtolower($_POST["name"]))?? '';
    $type = strtolower($_POST["type"])?? '';
    $HP = $_POST["HP"]?? '';
    $attack = $_POST["attack"]?? '';
    $defence = $_POST["defence"]?? '';
    $price = $_POST["price"]?? '';
    $description = $_POST["description"]?? '';
    $image = $_POST["image"]?? '';
    
    $success =false;
    


    $typelist =  ["normal", "fighting", "poison", "ground",
     "flying", "bug", "rock", "ghost", "fire", "water", "grass",
      "electric", "ice", "psychic", "dragon", "dark", "steel", "fairy",
       "stellar", "shadow"];
if(empty($name)){
    $errors['name']= "Name cant be empty!!";

}
elseif(preg_match('/\s/',$name)){
    $errors['name']= "Card name cant have emptyspaces";
}
if(empty($type)){
    $errors['type']= "Type cant be empty!!";

}
elseif(!in_array($type,$typelist)){
    $errors['type']= "Wrong type!! Allowed types are as follows [Normal, Fighting, Poison, Ground, Flying, Bug, Rock, Ghost, Fire, Water, Grass, Electric, Ice, Psychic, Dragon, Dark, Steel, Fairy, Stellar, Shadow]";
}
if(empty($HP)){
    $errors['HP']= "HP cant be empty!!";

}
if(empty($attack)){
    $errors['attack']= "Attack cant be empty!!";

}
if(empty($defence)){
    $errors['defence']= "Defence cant be empty!!";

}
if(empty($price)){
    $errors['price']= "Price cant be empty!!";

}
if(empty($image)){
    $errors['image']= "Image cant be empty!!";

}else{$pattern = "/^https:\/\/assets\.pokemon\.com\/assets\/cms2\/img\/pokedex\/full\/\d{3}\.png$/";
if (!preg_match($pattern, $image)) {
    $errors['image'] = "Image URL is not in the correct format!!";
}}


if(empty($description)){
    $errors['description']= "Description cant be empty!!";

}
foreach ($Cards as $card) {
    if (strtolower($card['name']) === strtolower($name)) {
        $errors['name'] = "A card with this name already exists!!";
        break;
    }
    if ($card['image'] === $image) {
        $errors['image'] = "A card with this image already exists!!";
        break;
    }
}




if(empty($errors)){
    $success=true;

    $newCard = [
        'name' => $name,
        'type' => $type,
        'hp' => $HP,
        'attack' => $attack,
        'defense' => $defence,
        'price' => $price,
        'description' => $description,
        'image' => $image
    ];
     // Adding a custom key for the new user
     $cardKey = 'card' . (count($Cards) );
     $Cards[$cardKey] = $newCard;

    $jsonIOcards -> save($Cards);


    // Load the admin data
    $jsonIOusers = new JsonIO('users.json');
    $users = $jsonIOusers -> load();

    // Adding the new card to the admin's cards
    $users[$userId]['cards'][] = $cardKey;

    
    $jsonIOusers -> save($users);
}
    }













?>
<!-- <div class="container"> -->
<header><h2>Create Card</h2></header>
<button class="exit-button" onclick="goBack()">&times;</button>

      
<form action="" method = "post">
<div class="cardform">   
    <div class="form-group">
      <span class="error">  <?= isset($_POST['name']) ? ($errors['name'] ?? '') : '';?> </span>
      <input type="text" placeholder="Enter card name" name="name" id="name" value="<?= $name ?? ''?>">
      <span class="error"> <?= isset($_POST['defence']) ? ($errors['defence'] ?? '') : '';?> </span>
        <input type="number" placeholder="Add defence" name="defence" id="defence" value="<?= $defence ?? ''?>">
      
        <span class="error"> <?= isset($_POST['HP']) ? ($errors['HP'] ?? '') : '';?> </span>
        <input type="number" placeholder="Add HP"  name="HP" id="HP" value="<?= $HP ?? ''?>">
        <span class="error"> <?= isset($_POST['attack']) ? ($errors['attack'] ?? '') : '';?> </span>
        <input type="number" placeholder="Add attack" name="attack" id="attack" value="<?= $attack ?? ''?>">
</div>

<div class="form-group">
<span class="error"> <?= isset($_POST['type']) ? ($errors['type'] ?? '') : '';?> </span>
        <input type="text" placeholder="Enter card's type" name="type" id="type" value="<?= $type ?? ''?>">
        <span class="error"> <?= isset($_POST['price']) ? ($errors['price'] ?? '') : '';?> </span>
        <input type="number" placeholder="Add Price" name="price" id="price" value="<?= $price?? ''?>">
        <span class="error"> <?= isset($_POST['description']) ? ($errors['description'] ?? '') : '';?> </span>
        <input type="text" placeholder="Description" name="description" id="description" value="<?= $description ?? ''?>">

        <label>Add image</label>
        <span class="error"> <?= isset($_POST['image']) ? ($errors['image'] ?? '') : '';?> </span>
        <input type="text" name="image" id="imageins" value="<?= $image ?? ''?>">
        <b id="note"> go to pokemon.com and copy image address here</b>
</div>
</div>
    <input type="submit" class="button" value="Add Card">

      </form>

<!-- <div class="signup">
      <span class="signup2">Signup and get <span class="bonus">500</span> coins for free!</span><br>
        <span class="signup">Already have an account?
         <label id="check"><a href="login.php"  >Login</a></label>
        </span>
      </div> -->
    </div>
<!-- </div> -->


<script>
function goBack() {
    window.location.href = 'index.php?userId=<?= $userId ?>';
}
</script>
</body>
</html>