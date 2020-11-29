

function manipulation_box(){
    var resize=document.getElementById("resize");
    var crop=document.getElementById("crop");
    var ro=document.getElementById("rotate");
    var mark=document.getElementById("water_mark");

    if (document.getElementById('resize_button').checked){
        resize.style.display="block";
        crop.style.display="none";
        ro.style.display="none";
        mark.style.display="none";
    }
    if (document.getElementById('crop_button').checked){
        crop.style.display="block";
        resize.style.display="none";
        ro.style.display="none";
        mark.style.display="none";
    }
    if (document.getElementById('rotate_button').checked){
        ro.style.display="block";
        crop.style.display="none";
        resize.style.display="none";
        mark.style.display="none";
    }
    if (document.getElementById('watermark_button').checked){
        mark.style.display="block";
        crop.style.display="none";
        resize.style.display="none";
        ro.style.display="none";
    }

}
