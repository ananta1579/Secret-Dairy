<?php
    session_start();
    $error = "";

    if(array_key_exists("logout", $_GET)) {
        session_unset();
        setcookie("id", "", time() + 60*60);
        $_COOKIE["id"] = "";
    } else if(array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
        header("Location: loggedinpage.php");
    }

    if(array_key_exists("submit", $_POST)) {

        include("connection.php");
        
        if(!$_POST['email']) {
            $error .= "An email is required.<br>";
        }
        if(!$_POST['password']) {
            $error .= "A password is required.<br>";
        }
        if($error != "") {
            $error .= "<p>There were error(s) in your form!</p>";
        } else {
            
            $emailAddress = mysqli_real_escape_string($link, $_POST['email']);
            $password = mysqli_real_escape_string($link, $_POST['password']);
            $password = password_hash($password, PASSWORD_DEFAULT);

            if($_POST['signup'] == 1 ) {
                //signup
                $query = "SELECT id FROM secretdairy WHERE email = '" .$emailAddress. "' LIMIT 1";

                $result = mysqli_query($link, $query);

                if(mysqli_num_rows($result) > 0) {
                    $error = "That email address has already taken";
                } else {

                    $query = "INSERT INTO secretdairy(email,password) VALUES('".$emailAddress."', '".$password."')";
                    if(!mysqli_query($link, $query)) {
                        $error .= "<p>Could not sign you up - please try again later</p>"; 
                        $error .= "<p>" .mysqli_error($link). "</p>";
                    } else {

                        $id = mysqli_insert_id($link);

                        $_SESSION['id'] = $id;

                        if(isset($_POST['stayLoggedIn'])) {
                            setcookie("id", $id, time() + 60*60*24*365);
                        }

                        header('Location: loggedinpage.php');
                    }
                }
            } else {
                //logging in
                $query = "SELECT * FROM secretdairy where email='".$emailAddress."' ";
                $result = mysqli_query($link, $query);

                $row = mysqli_fetch_array($result);

                $password = mysqli_real_escape_string($link, $_POST['password']);

                if(isset($row) AND array_key_exists("password", $row)) {
                    $passwordMatch = password_verify($password, $row['password']);
                    
                    if($passwordMatch) {
                        $_SESSION['id'] = $row['id'];

                        if(isset($_POST['stayLoggedIn'])) {
                            setcookie("id", $_row['id'], time() + 60*60*24*365);
                        }

                        header("Location: loggedinpage.php");
                    } else {
                        $error = "email id or password incorrect";
                    }
                }else {
                    $error = "email id or password incorrect";
                }
            }
            
        }
        
    } 
?>

<?php include("header.php") ?>
        <div class="container" id="homePageContainer">
            <h1>Secret Diary</h1>
            <p>Store your thoughts permanently and securely</p>
            <div id="error">
                <?php
                if($error != ""){
                    echo '<div class="alert alert-danger" role"alert">'.$error.'</div>';
                }
                    
                ?>
            </div>

        <!-- signup form -->
        <form method="post" id="signupform">
            <p>Interested sign up now!</p>
            <fieldset class="form-group">
            <input type="email" name="email" placeholder="Enter you email" class="form-control">
            </fieldset>

            <fieldset class="form-group">
            <input type="password" name="password" placeholder="Enter you password" class="form-control">
            </fieldset>
            
            <fieldset class="checkbox">
                <label for="" class="text-muted">Stay : Logged In</label>
            
            <input type="checkbox" name="stayLoggedIn" value="1">
            </fieldset>
            
            
            <fieldset class="form-group">
            <input type="hidden" name="signup" value="1">
            <input type="submit" name="submit" value="Sign up!" class="btn btn-success">
            </fieldset>

            <p><a class="toggleForms">Log In</a></p>
            
        </form>

        <!-- login form -->
        <form method="post" id="loginform">
            <p>Log in using your username or password</p>
            <fieldset class="form-group">
            <input type="email" name="email" placeholder="Enter you email" class="form-control">
            </fieldset>

            <fieldset class="form-group">
            <input type="password" name="password" placeholder="Enter you password" class="form-control">
            </fieldset>
            
            <fieldset class="checkbox">
            <label for="" class="text-muted">Stay : Logged In</label>
            <input type="checkbox" name="stayLoggedIn" value="1">
            </fieldset>
            
            
            <fieldset class="form-group">
            <input type="hidden" name="signup" value="0">
            <input type="submit" name="submit" value="Log in" class="btn btn-success">
            </fieldset>
            
            <p><a class="toggleForms">Sign up</a></p>
            </form>
        </div>

<?php include("footer.php") ?>