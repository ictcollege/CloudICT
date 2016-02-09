<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/css/responsive.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url();?>public/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
     <!-- jQuery -->
    <script src="<?php echo base_url();?>public/js/jquery.min.js"></script>
    
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
<title><?php echo $title;?></title>
<style type="text/css" media="screen">
    #editor { 
        position: absolute;
        top: 5em;
        right: 0;
        bottom: 0;
        left: 0;
    }
    #btnSave{
        margin: 5px auto;
    }
</style>
</head>
<body>
<div id="editor">
    
    <?php
    if(!isset($error)){
        echo $content;
    }
    else{
        echo $error;
    }
    ?>
</div>
    <?php if(!isset($error)){?><button id="btnSave" class="btn btn-primary">Save</button><?php } ?> 
<script src="<?php echo base_url();?>public/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url();?>public/js/ace/ext-modelist.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var editor = ace.edit("editor");
editor.setTheme("ace/theme/github");
var modelist = ace.require("ace/ext/modelist");
var filePath = "<?php echo $filePath;?>";
var mode = modelist.getModeForPath(filePath).mode;;
editor.getSession().setMode(mode); // mode now contains "ace/mode/javascript".

$(document).ready(function (){
    var IdFile = <?php echo $IdFile;?>;
    $("#btnSave").click(function (e){
        var NewContent = {};
        NewContent.IdFile = IdFile;
        NewContent.content = editor.getValue();
        var json = JSON.stringify(NewContent);
            $.ajax({
                    url: "<?php echo base_url();?>ApiFiles/saveFile",
                    type: "POST",
                    dataType: "json",
                    data:{json:json},
                    beforeSend: function (xhr) {
                       
                    },
                    success: function(data) {
                       alert("File saved, you can continue work...");
                    }


                });

    });

});
</script>

</body>
</html>