<html>
    <head>
        <title>Health Express</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/profile.css') ?>" >
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>

        <div class="jumbotron" style="background-image:url('<?php echo base_url('images') ?>../../images/bg.png'); height:300px; background-size: cover;">
            <div class="col-sm-12">
                <!-- Show/update the user's avatar -->
                <div style="margin:5px 40px;">
                    <?php if(!empty($image)):?>
                        <img src="<?php echo base_url().'avatar/'.$image ?>" class="img-circle" height="80" width="80" alt="Avatar">
                    <?php endif; ?>

                    <?php if(empty($image)):?>
                        <img src="<?php echo base_url().'avatar/no_photo.png' ?>" class="img-circle" height="80" width="80" alt="Avatar"><br>
                    <?php endif; ?>

                    <h2><?php echo $this->session->userdata('username'); ?></h2><br>

                    <?php if(isset($error)) {echo $error;} ?>
                    <?php  echo form_open_multipart('Upload/avatar_upload'); ?>
                    <input  type="file" name="avatar"><br>
                    <input  type="submit" value="Change avatar"><br>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        
        <!-- Information nav-bar -->
        <div class="container-fluid bg-2 text-center">
            <div class="row">
                <div class="col-sm-3">
                    <div class="well" id="get_info">
                        <label style="text-decoration: none; font-size: 1.5em;">My info</label>
                    </div>
                    <div class="well" id="get_upload">
                        <label style="text-decoration: none; font-size: 1.5em;">My uploads</label> 
                    </div>
                    <div class="well" id="get_playlist">
                        <label style="text-decoration: none; font-size: 1.5em;">My Playlist</label>  
                    </div>
                    <div class="well" id="get_comments">
                        <label style="text-decoration: none; font-size: 1.5em;">Comments</label>  
                    </div>  
                </div>
            
                <!-- Show/update the user's basic info -->
                <div class="col-sm-9">
                    <div class = "bg" id="info" style="background-image: <?php echo base_url("images/bg.jpg") ?>; height:550px; background-size: cover;">
                        <div class="form">
                            <?php echo form_open('Home/update_info'); ?>
                            <h1>My info</h1>
                            <label for="username">Username</label>
                            <div class="error"><?php if(isset($title)){echo $title;}?></div>
                            <input type="text"  name="username" value="<?php echo $this->session->userdata('username') ?>" required>

                            <label for="email">Email<br></label>
                            <div class="error"><?php if(isset($error)){echo $error;}?></div>
                            <input type="text"  name="email" value="<?php if(isset($info)){ echo $info->email;}
                                else{ echo get_cookie('email');} ?>" required><br>
                            <!-- Gender checkbox -->
                            <div>
                                <label for="gender">Gender:</label><br>
                                <label style="margin:0 10px">Male</label>
                                <input type="checkbox" name="male" <?php echo set_checkbox('male', '', FALSE);?>
                                <?php if(isset($info) && $info->male == 1) { ?> checked="checked" <?php } ?>>

                                <label style="margin:0 10px">Female</label>
                                <input type="checkbox" name="female" <?php echo set_checkbox('female', '', FALSE);?>
                                <?php if(isset($info) && $info->female == 1) { ?> checked="checked" <?php } ?>>
                            </div><br>
                            <!-- Date of Birth -->
                            <label for="email">Date of birth </label><br>
                            <input type="date" name="birth" value="<?php if(isset($info)){ echo $info->birth ;} ?>"><br><br>

                            <input type = "submit" class= "submit" value = "save" name="submit"><br>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>

                    <!-- Show sports videos uploaded by the user. -->
                <div class="col-sm-9">
                    <div class="bg">
                        <div class="container" id="videos" style="display:none;">
                            
                            <?php if (isset($videos)) { foreach($videos as $video) { ?>
                            <div class="col-sm-3">
                                <div class="thumbnail">
                                    <a href=<?php echo site_url('Home/videos/'.$video['sports_id']);?>>
                                        <video width="200" height="200" controls style="margin: 10px 0">
                                            <source src="<?php echo base_url('videos/'.$video['name']) ?>" type="video/mp4" >
                                        </video>
                                        <div class="caption">
                                            <h6>Date: <?php echo $video['date'] ?></h6>
                                            <h6>Title: <?php echo $video['title'] ?></h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <!-- Show the playlists for the user. -->
                    <div class="bg">
                        <div class="container" id="playlist" style="display:none;">
                            
                            <?php if (isset($playlist)) { foreach($playlist as $list) { ?>
                            <div class="col-sm-3">
                                <div class="thumbnail" id="fav_items">
                                    <a href=<?php echo site_url('Home/videos/'.$list['sports_id']);?>>
                                        <video width="200" height="200" controls style="margin: 10px 0">
                                            <source src="<?php echo base_url('videos/'.$list['name']) ?>" type="video/mp4" >
                                        </video>
                                        <div class="caption">
                                            <h6><?php echo $list['title'] ;?></h6>
                                        </div>
                                    </a>
                                    <div class="caption">
                                        <button type="submit" class="remove" id="<?php echo $list['id'] ?>">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>

                    <!-- Display the comments made by the user. -->
                <div class="col-sm-9">
                    <div class="bg">
                        <div class="container" id="comments" style="display:none;">
                            
                            <?php if (isset($my_comments)) { foreach($my_comments as $comment) { ?>
                            <div class="col-sm-3">
                                <p>Video : <large><?php echo $comment['name'] ;?></large></p>
                                <p>Content : <?php echo $comment['content'] ;?></p>
                                <p>Date: <?php echo $comment['date'] ?></p>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

<script>
    $(document).ready(function(){
        $('.remove').click(function(){
            var id= $(this).attr("id");
            var xmlhttp;
            if(confirm("Are you sure you want to delete this?")){
                window.location = "<?php echo base_url() ?>Favourite/remove/" + id ;
                
            }
            else{
                return false;
            }
        })
    })


    $(document).ready(function(){
    $("#get_info").click(function(){
      $("#info").show();
      $("#videos").hide();
      $("#playlist").hide();
      $("#comments").hide();
    });

    $("#get_upload").click(function(){
        $("#info").hide();
        $("#videos").show();
        $("#playlist").hide();
        $("#comments").hide();
      });

    $("#get_playlist").click(function(){
        $("#info").hide();
        $("#videos").hide();
        $("#playlist").show();
        $("#comments").hide();
    });

    $("#get_comments").click(function(){
        $("#info").hide();
        $("#videos").hide();
        $("#playlist").hide();
        $("#comments").show();
    });


  });

</script>

