
<html>
    <head>
        <meta charset="utf-8">
        <title>Health Express</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet"  href="<?php echo base_url('css/login.css') ?>" >
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <div class = "bg">
        <div class="form">
            <!-- Login form -->
            <?php echo form_open('Login/verify'); ?>
                <h1>Login</h1>

                <label for="username">Username</label>
                <!-- show error if the username is incorrect -->
                <div class="error"><?php if(isset($title)){echo $title;}?></div>
                <input type="text"  name="username" value="<?php if(get_cookie('username')) { echo get_cookie('username'); } ?>" required>

                <label for="password">Password<br></label>
                <!-- show error if the password is incorrect -->
                <div class="error"><?php if(isset($error)){echo $error;}?></div>
                <input type="password"  name="password" value="<?php if(get_cookie('password')) { echo get_cookie('password'); } ?>" required>

                <p id="captImg"><?php if(isset($captchaImg)) {echo $captchaImg;} ?></p>
                <p>Can't read the image? click <a href="javascript:void(0);" class="refreshCaptcha">here</a> to refresh.</p><br>
                Enter the code : <input type="text" name="captcha" />
                <div class="error"><?php if(isset($captcha)){echo $captcha;}?></div>
        
                <!-- Use checkbox to remember user's login info -->
                <label><input type="checkbox" name="remember" <?php if(get_cookie('username')) { ?> checked="checked" <?php } ?>> Remember me</label><br>
                <a href='<?php base_url() ?>Home/reset_password_form'>Forget password?</a><br>

                <input type = "submit", class= "submit" value = "login" name="submit"><br>

                Don't have an account?<a href="<?php base_url() ?>Register">Register</a>
            </form>
        </div>
    </div>
</html>

<!-- captcha refresh code -->
<script>
$(document).ready(function(){
    $('.refreshCaptcha').on('click', function(){
        $.get('<?php echo base_url().'Login/refresh'; ?>', function(data){
            $('#captImg').html(data);
        });
    });
});
</script>



