<html lang="en">
    <head>
        <title> Health Express </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/register.css') ?>" >
    </head>
    <body>
        <div id="reset_password">
            <?php echo form_open('Home/update_password'); ?>
            <h1>Reset password</h1>
            <div class = "center">

                <label for="email">Email</label>
                <?php echo form_error('email'); ?> 
                <input type="text" placeholder="Enter Email" name="email" value="<?php echo set_value('email'); ?>" >
                <?php if(isset($Msg)){ echo $Msg; }; ?>

                <label for="psw">New password </label>
                <?php echo form_error('password'); ?>
                <input type="password" placeholder="At list 8 characters" name="password" >

                <label for="psw-repeat">Repeat new Password</label>
                <?php echo form_error('password_repeat'); ?>
                <input type="password" placeholder="Repeat Password" name="password_repeat" >
                <h4 style="color: green"><?php if(isset($msg)) {echo $msg ;} ?></h4>
            </div>
            <br><br>
            <button type="submit" class="register">Submit</button>
        </form>


        </div>

    </body>
</html>