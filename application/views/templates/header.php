<html>
    <head>
        <title> Health Express </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/project.css') ?>" >
        <script src="<?php echo base_url('js/jquery.min.js') ?>"></script>
        <link href="https://fonts.googleapis.com/css?family=Abel|Fira+Sans+Condensed|Hammersmith+One|
                    Patua+One|Permanent+Marker|Quicksand|Signika|Ubuntu|Yeseva+One|ZCOOL+XiaoWei&display=swap" rel="stylesheet">
    </head>

    <body>
        <nav id="nav">
            <a class="nav1" href="<?php echo base_url('Home') ?>">Home</a>

            <a class="nav2" href="<?php echo base_url('Home').'/profile'?>">Me ☰</a>

            <?php if(!$this->session->userdata('logged_in')) : ?>
                <a class="nav3" href="<?php echo base_url();?>Login">Login</a>
            <?php endif; ?>

            <?php if($this->session->userdata('logged_in')) : ?>
                <a class="nav3" href="<?php echo base_url();?>Login/logout"> Logout </a>
            <?php endif; ?>

            <?php if($this->session->userdata('logged_in')) : ?>
                <span class="nav3">Welcome <?php echo $this->session->userdata('username'); ?></span>
            <?php endif; ?>
        </nav>
    </body>

