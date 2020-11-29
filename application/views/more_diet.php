<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php if(isset($diets)){ foreach($diets as $diet){ ?>
            <div class="items">
                <h4><?php echo $diet->title ?></h4>
                <img src="<?php echo base_url('diet/').$diet->name ?>" alt="r1" style="height:200px; width:200px"><br>
            </div>
        <?php } } ?>
    </body>