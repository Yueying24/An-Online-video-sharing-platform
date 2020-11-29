<html lang="en">
    <head>
        <title> Health Express </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/register.css') ?>" >
    </head>
    <body>
        <div id="reset_password">
            <?php echo form_open('Home/reset_password'); ?>
            <h1>Reset password</h1>
            <div class = "center">

                <label for="email">Email</label>
                <?php echo form_error('email'); ?>
                <input type="text" placeholder="Enter Email" name="email" value="<?php echo set_value('email'); ?>" >
                <?php if(isset($Msg)){ echo $Msg; };  ?>
            </div>
            <br><br>
            <button type="submit" class="register">Reset password</button>
            
        </form>


        </div>

    </body>
</html>
