<html>
    <head>
        <title>Health Express</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/register.css') ?>" >
    </head>
    <body>
        <div class="bg">
            <div class="form-r">
                <!--register from -->
                <?php echo form_open('Register/register'); ?>
                    <h1>Register</h1>
                    <div class = "center">
                        <label for="username">Username</label>
                        <?php echo form_error('username'); ?>
                        <input type="text" placeholder="Enter Name" name="username" autocomplete="username" value="<?php echo set_value('username'); ?>" />

                        <label for="email">Email</label>
                        <?php echo form_error('email'); ?>
                        <input type="text" placeholder="Enter Email" name="email" value="<?php echo set_value('email'); ?>" >

                        <label for="psw">Password</label>
                        <?php echo form_error('password'); ?>
                        <input type="password" placeholder="At list 8 characters" name="password" autocomplete="new-password" value="<?php echo set_value('password'); ?>" >

                        <label for="psw-repeat">Repeat Password</label>
                        <?php echo form_error('password_repeat'); ?>
                        <input type="password" placeholder="Repeat Password" name="password_repeat" autocomplete="new-password" value="<?php echo set_value('password_repeat'); ?>" >
                        <h4 style="color: green"><?php if(isset($msg)) {echo $msg ;} ?></h4>
                    </div>
                    <hr>
                    <button type="submit" class="register">Register</button>
                    <!--Go to login page if the account exists -->
                    <p>Already have an account? <a class = "login" href="login">Login</a>.</p>
                </form>
            </div>
        </div>
    </body>
</html>
