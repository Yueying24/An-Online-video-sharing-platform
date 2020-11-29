
<html lang="en">
    <head>
        <title> Health Express </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"  href="<?php echo base_url('css/workout.css') ?>" >
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        
    </head>

    <body>
        <div>
            <!-- Search videos by inputting keyword-->
            <div class="container">
                <?php echo form_open('Home/video_search') ?>
                <div class="form-group">
                    <div class='col-sm-10'>
                        <input type="text" name="search" id="search" placeholder="Search videos" class="form-control"/>
                    </div>
                    <div class='col-sm-2'>
                        <button type="submit" id="submit" class="btn btn-default">Search</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

            <!-- Display autocomplete result-->
            <div class="container" id="videos">
                <div>
                    <div><h6></h6></div>
                </div>
            </div>

            <!-- Display all uploaded and searched videos by using ajax -->
            <div class="main" id="searched_videos">
                <?php if (isset($all_videos)) { foreach($all_videos as $video) { ?>
                <a href=<?php echo site_url('Home/videos/'.$video['sports_id']);?>>
                    <video width="300" height="250" controls style="margin: 0 10px">
                        <source src="<?php echo base_url('videos/'.$video['name']) ?>" type="video/mp4" >
                    </video>
                    <h4><?php echo $video['title'] ?></h4>
                </a>
                <?php } } ?>
            </div>

            <!-- Videos upload form -->
            <div class="file">
                <h2>Share your sports video with us.</h2><br>
                <?php if(isset($error)) {echo $error;} ?>
                <?php if(isset($Msg)){ echo $Msg; }?>

                <?php  echo form_open_multipart('Upload/videos_upload');  ?><br>
                <label for="title" style="font-size: 20px">Video title:<br></label>
                <input type="text" name="title" placeholder="video title"><br>

                <input  type="file" name="videos[]" size="60" style="margin: 0 auto;" multiple><br>

                <input  type="submit" value="upload" style="background: black; color:white; padding:10px; font-size: 15px">
                </form>
            </div><br>
        </div>
            <!-- Move back to the top bar-->
            <div class="container-fluid bg-3 text-center">
                <a href="#" title="To Top">
                    <div class="glyphicon glyphicon-chevron-up"></div>
                </a>
            </div><br>
                   
    </body>

    
    <script>
        /* Search auto completion  */
        $(document).ready(function() {
            $("#search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                    url: "<?=base_url() ?>Home/video_fetch",
                    type: "GET",
                    data: { search: request.term },
                    dataType: "json",
                    
                    success: function(data){
                        response(data);
                    }
                })
            },
            select: function (event, ui) { 
                $('#search').val(ui.item.value);// display the selected text
                }
            });
        });

        // Search a specific video by ajax
        $(document).ready(function(){
            $('#submit').click(function(){
                event.preventDefault();
                var keyword = $('#search').val();
                var xmlhttp;
                if (window.XMLHttpRequest) { // if the brower supports the XMLHttpRequest object
                    xmlhttp = new XMLHttpRequest();
                } else { // IE6, etc.
                    xmlhttp = new ActiveObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // 7. Proper action (like page update) is performed by JavaScript
                    document.getElementById('searched_videos').innerHTML = xmlhttp.responseText;}
                }

                xmlhttp.open('GET', 'video_search?keyword='+keyword, true);
                xmlhttp.send();

            })
        })

    </script>
