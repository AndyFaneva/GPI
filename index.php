<?php
    session_start();
    include("php/connexion.php");
    if($_SERVER['REQUEST_METHOD']=="POST"){
        if(isset($_POST['inscrirei'])){
            if(!empty($_POST['nomi']) && !empty($_POST['prenomi']) && !empty($_POST['adressei']) && !empty($_POST['numéroi'])
     && !empty($_POST['mdpi']) && !empty($_POST['mdpi2'])){
                $a=htmlspecialchars($_POST['nomi']);
                $b =htmlspecialchars($_POST['prenomi']);
                $c =htmlspecialchars($_POST['adressei']);
                $d =htmlspecialchars($_POST['numéroi']);
                if(empty($_POST['type_compte'])){
                    $e ="Administrateur";
                }else{
                    $e =htmlspecialchars($_POST['type_compte']);
                }
                $f =sha1($_POST['mdpi']);
                $g=sha1($_POST['mdpi2']);
                if($f==$g){
                    $req=$con->prepare("INSERT INTO utilisateur (nom, prenom, mail, numero, type_compte, mot_pass) VALUES(?,?,?,?,?,?)");
                    $req->execute(array($a,$b,$c,$d,$e,$f));
                    $ok="Nouveau utilisateur enregistrer";
                }else{
                    $mdin="Votre mot de passe n'est pas identique";
                }
            }else{
                $message="Veuillez remplir tous les champs";
            }
        }
        if(isset($_POST['logi'])){
            if(!empty($_POST['nom']) && !empty($_POST['mdp'])){
            $n =htmlspecialchars($_POST['nom']);
            $mdp=sha1($_POST['mdp']);
            $rr=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
            $rr->execute(array($n,$mdp));
            if($rr->fetch()>0){
                $req=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
                $req->execute(array($n,$mdp));
                $_SESSION['Prn']=$req->fetch()['prenom'];
                $_SESSION['Typedecompte']=$req->fetch()['type_compte'];
                $req1=$con->prepare("SELECT * FROM utilisateur WHERE type_compte=?");
                $req1->execute(array( $_SESSION['Typedecompte']));
                if( $_SESSION['Typedecompte']=="Utilisateur assigné"){
                    $ver=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
                    $ver->execute(array($n,$mdp));
                    $fetch=$ver->fetch();
                    if(empty($fetch['service'])){
                        $m="Compte non approuver par l'administrateur";
                    }else{
                        header("location:php/index.php");
                    }
                }else{
                    header("location:php/index.php");
                }
            }else{
                $mdin="Votre adresse mail ou mot de passe est incorecte";
            }
        }else{
            $message="Veuillez remplir tous les champs";
        }
    }
    if(isset($_POST['se_connecter'])){
        $ma=htmlspecialchars( $_SESSION['d']);
        $mdpf=sha1($_POST['mdpass']);
        $rrr=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
        $rrr->execute(array($ma,$mdpf));
        if($rrr->fetch()>0){
            $req=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
            $req->execute(array($ma,$mdpf));
            $_SESSION['Typedecompte']=$req->fetch()['type_compte'];
            $req1=$con->prepare("SELECT * FROM utilisateur WHERE type_compte=?");
            $req1->execute(array( $_SESSION['Typedecompte']));
            if( $_SESSION['Typedecompte']=="Utilisateur assigné"){
                $ver=$con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mot_pass=?");
                $ver->execute(array($ma,$mdpf));
                $fetch=$ver->fetch();
                if(empty($fetch['service'])){
                    $m="Compte non approuver par l'administrateur";
                }else{
                    header("location:php/index.php");
                }
            }else{
                header("location:php/index.php");
            }
        }else{
            $mdin="Votre adresse mail ou mot de passe est incorecte";
        }
    }
    }
?>
<style>
 .formindex input[type=text], input[type=email], select , input[type=date]{
    width: 100%;
    padding: 5px 10px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 10px;
  }
    .formindex{
    background-size: cover;
    color:white;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    display:flex;
    flex-direction: column;
    width: auto;
    margin:1% 5% 1% 5%;
    border:solid 5px black;
    align-items: center;
    text-align: center;
    background-color: #092534;
    padding:2% 5% 2% 5%;
    border-radius: 40px;
}
.formindex label{
    float:left;
}
</style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc Informatique</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="headertop">
            <div class="logo">
                <img src="image/logo.png" alt="Logo">
            </div>
            <div class="titre">
                <h1>Gestion du parc informatique du SIP
            </h1>
            </div>
        </div>
        <div class="headerbot">
            <div class="labele"> </div><br>
            <div class="labele"> </div><br>
            <div class="labele"> </div>
        </div>
        </header>
    <section class="section">
        <div class="contenu" style="width:100%">
              <?php
                    if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['connect']) || isset($_POST['inscrirei'])){
                ?>
                <script></script>
            <div class="formindex" id="inscrire"> 
                <form action="index.php" method="post">
                <h1>S'inscrire</h1>
                <div class="formi1">
                <div class="form1">
                <label for="nomi">Nom</label>
                <input type="text" name="nomi" placeholder="Nom">
                <label for="prenomi">Prénom</label>
                <input type="text" name="prenomi" placeholder="Prénom">
                </div>
                <div class="form2">
                    <label for="adressei">Adresse mail</label>
                    <input type="email" name="adressei" placeholder="Adresse mail">
                    <label for="numéroi">Numéro téléphone</label>
                    <input type="text" name="numéroi" placeholder="Numéro de téléphone">
                </div>
                <div class="form3">
                    <?php
                    $f=$con->prepare("SELECT * FROM utilisateur");
                    $f->execute();
                        if($f->fetch()<=0){
                            ?>
                            <option value="Administrateur" name="type_compte">Vous êtes Administrateur</option>
                            <?php
                        }else{
                    ?>
                    <label for="type_compte">Type de compte</label><br>
                    <select name="type_compte" id="">
                        <option value="Utilisateur assigné" name="type_compte">Utilisateur assigné</option>
                        <option value="Utilisateur standard" name="type_compte">Utilisateur standard</option>
                    </select><br>
                    <?php
                        }
                        ?>
                </div>
            </div>
            <div class="formi2">
                <label for="mdpi">Mot de passe</label>
                <input type="password" name="mdpi" placeholder="Entrez un mot de passe" style="width: 100%;
                padding: 5px 10px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 10px;">
                <input type="password" name="mdpi2" placeholder="Entrez a nouveau le mot de passe" style="width: 100%;
                padding: 5px 10px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 10px;">
                <input type="submit" Value="S'inscrire" name="inscrirei" style="margin:0 -5%"><br>
                <form action="" method="post">
                    <input type="submit" name="cc" value="Se connecter" style="border:none;background-color:#092534;text-decoration:underline;margin-top:-0.01%;margin-bottom:-3%;">
                </form>
                    <?php
                        if(isset($message)){
                            echo "<i style='color:red'>".$message."</i>";
                        }else if(isset($mdin)){
                            echo "<i style='color:red'>".$mdin."</i>";
                        }elseif(isset($ok)){
                            echo "<i style='color:green'>".$ok."</i>";
                        }
                    ?>
    </form>
    </div>
    <?php
                    }else{
    ?>
        </div>
                        <?php
                    $compte=$con->prepare("SELECT * FROM utilisateur");
                    $compte->execute();
                    ?>
                     <?php
                     if($fe=$compte->fetch()<=0){
                        "Aucun compte a afficher";
                        $e=3;
                        $inc=3;
                        $case=0;
                     }elseif($fe=$compte->fetch()>=0){
                        $e=1;
                        $stmt1 = $con->prepare("SELECT COUNT(*) FROM utilisateur");
                        $stmt1->execute(array());
                        $case = $stmt1->fetchColumn();
                        echo " Il y a ".$case." utilisateurs";
                     }
                     switch($case){
                        case  1: $inc=1; break;
                        case  2: $inc=2; break;
                        case  3: $inc=3; break;
                        case  5: $inc=3; break;
                        default : $inc = 3;
                     }
                  while( $e<$inc){
                    $compte1=$con->prepare("SELECT * FROM utilisateur WHERE id_utilisateur=?");
                     $compte1->execute(array($e));
                     $e=$e+1;
                     $fef=$compte1->fetch();
                       ?>  
                        <table style="background-color:grey;width:auto;margin:10% 0.5%;border-collapse: collapse;">
                   
                        <tr display="block">
                        <td style="background-color:white"><img src="image/icon/profil.png" alt="Profil" width="50"></td>
                        <td style="background-color:black;color:white;border:solid 1px white"><?php echo "(".$fef['type_compte'].")" ?>
                            <?php echo $fef['mail'] ?>
                        <form method="post" action="index.php">
<input type="hidden" name="se_con" value="<?php echo $fef['id_utilisateur'] ?>">
<input style="background-color:blue;width:100%;color:white"type="submit" value="Se connecter">
</form>   </td>
                    </tr>
                       <?php
                    }
                    ?>
                    </table>
                    <?php
                      if($_SERVER['REQUEST_METHOD']=="POST"){
                        if (isset($_POST['se_con'])) {
                            $_SESSION['connexion']=$_POST['se_con'];
                        }
                    if (isset($_SESSION['connexion'])) {
                        echo '<div class="modal">';
                        echo '<div class="modal-content">';
                        echo '<span class="close"  onclick="window.location.href=\'index.php\'">&times;</span>';
                       
                        $ree=$con->prepare("SELECT * FROM utilisateur WHERE id_utilisateur=?");
                        $ree->execute(array($_SESSION['connexion']));
                        $fet=$ree->fetch();
                        ?>
                         <form action="index.php" class="form" method="post" style="width:auto">
                         <img src="image/icon/profil.png" alt="Profil" width="100">
                            <h1><?php echo $fet['nom']." ".$fet['prenom'] ?></h1>
                            <div class="formi1">
                            <div class="form3">
                            <input type="password" placeholder="Saisir votre mot de pass" name="mdpass" autocomplete="off"><br>
                            </div>
                            </div>
                            <div class="formi2">
                            <input type="submit" value="Se connecter" name="se_connecter"><br>
                            </div>
                        </form>
                        <?php
                        echo '</div>';
                        echo '</div>';
                        $_SESSION['d']=$fet['mail'];
                    unset($_SESSION['connexion']);
                }
             
                }
                        ?>
            <div class="formindex" id="connecter">
                <form action="index.php" method="post">
                <h1>Login</h1><br>
                <div class="form1">
                <label for="nom">Adresse mail</label>
                <input type="text" name="nom" placeholder="Adresse mail">
                <label for="mdp">Mot de passe</label>
                <input type="password" name="mdp" placeholder="Votre mot de passe"  autocomplete="off" style="width: 100%;
                padding: 5px 10px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 10px;">
                 </div>
            <div class="formi2">
                <input type="submit" name="logi"Value="Se connecter" style="margin:0 -5%">
                <?php
                echo "<br>";
                        if(isset($message)){
                            echo "<i style='color:red'>".$message."</i>";
                        }else if(isset($mdin)){
                            echo "<i style='color:red'>".$mdin."</i>";
                        }elseif(isset($ok)){
                            echo "<i style='color:green'>".$ok."</i>";
                        }
                        elseif(isset($m)){
                            echo "<i style='color:blue'>".$m."</i>";
                        }
                        echo "<br>";
                    ?>
                <form action="" method="post">
                <input type="submit"  name="connect" value="S'inscrire" style="border:none;background-color:#092534;text-decoration:underline">
    </form>
    
    </form>
    </div>
    <?php
                    }
    ?>
    </section>
    <footer class="footer"><br><br>
        <i>&copy;By Faneva_ANDRIANAINA</i>
        <i><img src="image/Icon/tel.png" alt="telephon" width="20px">032 98 038 10 - 038 62 135 34</i>
        <i><img src="image/Icon/mail.png" alt="mail" width="20px">fanevahasintsoa@gmail.com</i>
    </footer>
</body>
</html>