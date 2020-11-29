<html>
    <head>
        <title>Health Express</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/project.css') ?>" >
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="all">
            <?php if(!empty($video_info)): ?>
            <!--  Display a video chose by users, and add favourite video-->
            <div id="videos">
                <div>
                    <h2><?php echo $video_info['title'] ?></h2>
                    <video width="600" height="400" controls>
                        <source src="<?php echo base_url('videos/'.$video_info['name']) ?>" type="video/mp4">
                    </video>
                </div><br>
                <?php echo form_open('Favourite/voting/'.$video_info['sports_id'].'/'.$video_info['name']) ?>
                    <input type="submit" name ="liked" value='&#128077; Like' <?php if(isset($voting) && $voting['liked'] == 1) { ?> style="background-color:red" <?php } ?>>
                    <span><?php if(isset($like_count))echo $like_count;?></span>
                    <input type="submit" name ="disliked" value='&#128078;Dislike' <?php if(isset($voting) && $voting['disliked'] == 1) { ?> style="background-color:red; margin:0 10px" <?php } ?>>
                    <span><?php if(isset($dis_count))echo $dis_count;?></span>

                    <button class="playlist btn btn-primary btn-mini" id="<?php echo $video_info['sports_id'] ?>" name="<?php echo $video_info['name']?>" value="<?php echo $video_info['title']?>" style="float:right;" >Save</button>
                    <span><?php if(isset($msg))echo $msg;?></span>
                <?php echo form_close(); ?>
            </div>

            <!-- Write new comments for this video -->
            <div id = "comments">
                <h3>Comments</h3>
                <div class="com_form">
                    <?php echo form_open('Comments/add_comments/'.$video_info['name'].'/'.$video_info['sports_id']); ?>
                        <?php echo form_error('comments'); ?>
                        <input type="text" placeholder="Write something..." name="comments" style="width:600px; height:50px;"><br><br>
                        <label for="name_set" >Show my name as:&nbsp;</label>
                        <select name="name_set" id="name_set" style="width:465px;" >
                            <option <?php if (isset($name_set) && $name_set == "0") {?> selected="selected" <?php } ?> value="0"><?php echo $this->session->userdata('username') ?></option>
                            <option <?php if (isset($name_set) && $name_set == "1") {?> selected="selected" <?php } ?>  value="1">Anonymous to all users</option>
                        </select><br>
                        <input type="submit" name="submit" value="submit">
                    <?php echo form_close(); ?>
                </div>
                <?php endif ?>

                <div class="row">
                    <?php echo form_open('Home/fetch'); ?>
                    <!-- Display all comments for this video -->
                    <div class="row">
                        <div class="col-sm-10" style="margin:10px;" id="#result">
                            <?php if (isset($comments)) :foreach ($comments as $comment) { ?><hr>
                            <h4 id="set_name"> User: <?php echo "Anonymous ".$comment['username'] ?></h4>
                            <small><?php echo $comment['date'] ?></small>
                            <p><?php echo $comment['content'] ; ?></p>
                            <br>
                            <?php } endif ?>
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </body>
</html>

<script>

    $(document).ready(function(){
        $('.playlist').click(function(){
            var id = $(this).attr("id");
            var title = $(this).attr("value");
            var name = $(this).attr("name");
            var xmlhttp;
            
            if(confirm("Do you want add it to your playlist?")){
                window.location = "<?php echo base_url() ?>Favourite/add_to_list/" + id + "/" + title + "/" + name; 
            } 
            else{
                return false;
            }
        })
    })

</script>