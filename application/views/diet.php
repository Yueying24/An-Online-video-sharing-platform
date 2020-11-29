
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url('css/diet.css') ?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="<?php echo base_url('js/diet.js') ?>"></script>
    </head>
    <body data-spy="scroll" data-target="#myScrollspy" data-offset="15">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="main">
                        <div class="content">
                            <h2 id="form_head">Share your diet with us</h2>
                            <div id="form_input" >
                                <!-- Open form to choose image to manipulate  -->
                                <?php  echo form_open_multipart('Diet/diet_upload');  ?>
                                <div class="form-group">
                                    <!--diet upload and manipulation form -->
                                    <?php if(isset($error)) {echo $error;} ?><br>
                                    <label for="title">Title: </label>
                                    <input type="text" name="title" placeholder="title" width="30px"><br><br>
                                    <input type="file" name="diets" ><br>
                                    <!--Radio Button "rotate" -->
                                    <input type="radio" onclick="javascript:manipulation_box()"; name="mode" value='rotate' id = 'rotate_button' >
                                    <?php echo form_label('Rotate'); ?><br><br>
                                    <!--Radio Button "resize" -->
                                    <input type="radio" onclick="javascript:manipulation_box()" name="mode" value='resize' id = 'resize_button' >
                                    <?php echo form_label('Resize'); ?><br><br>
                                    <!--Radio Button "crop" -->
                                    <input type="radio" onclick="javascript:manipulation_box()" name="mode" value='crop' id = 'crop_button' >
                                    <?php echo form_label('Crop'); ?><br><br>
                                    <!--Radio Button "watermark" -->
                                    <input type="radio" onclick="javascript:manipulation_box()" name="mode" value='watermark' id = 'watermark_button' >
                                    <?php echo form_label('Water Mark'); ?><br><br>
                                </div>
                            </div>

                            <!-- Input fields for resize option. -->
                            <div id='resize' style='display: none'>
                                <div id='content_result'>

                                    <h4 id='result_id'>Enter width & height for resize image</h4>
                                    <div id='result_show'>
                                        <label class='label_output'>Width :</label>
                                        <input placeholder="200" name='width' id='width' class = 'input_box' ><br><br>

                                        <label class='label_output'>Height :</label>
                                        <input placeholder="200" name='height' id='height' class = 'input_box' >
                                    </div>
                                </div>
                            </div>

                            <!-- Input fields for watermark option. -->
                            <div id='water_mark' style='display: none'>
                                <div id='water_result'>
                                    <h4 id='result_id'>Enter text for watermark image</h4>
                                    <div id='result_show'>
                                        <label class='label_output'>Text :</label><br>

                                        <?php $data_text = array(
                                            'name' => 'text',
                                            'class' => 'input_box',
                                            'value' => '@'.$this->session->userdata('username'),
                                            'id' => 'watermark_text'
                                        );
                                        echo form_input($data_text); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Input fields for crop option. -->
                            <div id='crop' style='display: none'>
                                <div id='crop_result'>
                                    <h4 id='result_id'>Enter Cordinates</h4>
                                    <div id='result_show'>
                                        <label class='label_output'>X-axis (left): </label>
                                        <input value="100" name='x1' id='x1' class = 'input_box' ><br>

                                        <label class='label_output'>Y-axis (top): </label>
                                        <input value="100" name='y1' id='y1' class = 'input_box' ><br>

                                        <label class='label_output'>Width (right): </label>
                                        <input value="400" name='width_cor' id='width_cor' class = 'input_box' ><br>

                                        <label class='label_output'>Height (bottom): </label>
                                        <input value="350" name='height_cor' id='height_cor' class = 'input_box' ><br>
                                    </div>
                                </div>
                            </div>

                            <!-- Input fields for rotate option. -->
                            <div id='rotate' style='display: none' >
                                <div id='rotate_result'>
                                    <h4 id='result_id'>Enter Angle For Rotate Image</h4>
                                    <div id='result_show'>
                                        <input type="radio" name="degree" value="90" id = 'degree_90' >
                                        <?php echo form_label('90&deg;'); ?><br>

                                        <input type="radio" name="degree" value="180" id = 'degree_180' >
                                        <?php  echo form_label('180&deg;'); ?><br>

                                        <input type="radio" name="degree" value="270" id = 'degree_270' >
                                        <?php echo form_label('270&deg;'); ?><br>

                                        <input type="radio" name="degree" value="360" id = 'degree_360' >
                                        <?php echo form_label('360&deg;'); ?><br>
                                    </div>
                                </div>
                            </div><br>
                            <p style="color: red"><?php if (isset($Msg)){ echo $Msg; } ?></p>
                            <input type="submit" value="upload" class='submit' >
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

                <!--Show uploaded and resized images in this area -->
                <div class="col-sm-9">
                    <div id="diets">
                        <div style="margin-left:20px">
                            <h1>Diets club</h1>
                        </div><br>
                        <div style="margin: 10px" id="diet_club">
                            <?php if(isset($diets)){ foreach($diets as $diet){ ?>
                                <div class="items">
                                    <h4><?php echo $diet->title ?></h4>
                                    <img src="<?php echo base_url('diet/').$diet->name ?>" alt="r1" style="height:200px; width:200px"><br>
                                </div>
                            <?php } } ?>
                        </div>
                    </div>
                    <div class="load-more" style="display: none" lastID="<?php if(isset($count)){echo $count ; } ?>">
                        <i class="fa fa-spinner fa-spin"></i> Loading more diets...
                    </div><br>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
$(document).ready(function(){
    var lastID = $('.load-more').attr('lastID');
    var page = 1;
    $(window).scroll(function(){
        /* var lastID = $('.load-more').attr('lastID');  */
        if( $(window).scrollTop() >= $(document).height() - $(window).height() ){
            page++;
            loadMoreData(page, lastID);
        }
    });
        function loadMoreData(page, lastID){
            $.ajax(
                {
                    type:'GET',
                    url:'<?php echo base_url('Diet'); ?>?page=' + page,
                    
                    beforeSend:function(){
                        $('.load-more').show();
                    }
                })
                .done(function(more_diet){

                    if(page >= lastID){
                        $('.load-more').html("No more diets found");
	                    return;
                    }
                    else{
                    
                        $('.load-more').hide();
                        $('#diet_club').append(more_diet);
                    }
                    
                })
                .fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('server not responding...');
                });
            }
        });

</script>