
<html lang="en">
    <head>
        <title> Health Express </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/project.css') ?>" >
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Abel|Fira+Sans+Condensed|Hammersmith+One|
                Patua+One|Permanent+Marker|Quicksand|Signika|Ubuntu|Yeseva+One|ZCOOL+XiaoWei&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                    </ol>
                    <!-- Display the sports video entrance by using carousel -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="<?php echo base_url('images') ?>../../images/workout.jpg">
                            <div class="carousel-caption">
                                <div id="home-title">
                                    <span>Sport</span>
                                </div>
                                <div id="home-explore">
                                    <a href="<?php echo base_url('Home').'/workout'?>">
                                        <span>Get start!</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Display the diets entrance by using carousel -->
                        <div class="item">
                            <img src="<?php echo base_url('images') ?>../../images/background_diet.jpeg">
                            <div class="carousel-caption">
                                <div id="home-title1">
                                    <span>Diet</span>
                                </div>
                                <div id="home-explore">
                                    <a href="<?php echo base_url('Diet') ?>">
                                        <span>Explore!</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>


            </div>
        </div>
    </body>
</html>




