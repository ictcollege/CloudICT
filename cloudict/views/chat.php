<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>BOOTSTRAP CHAT EXAMPLE</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="<?php echo base_url();?>public/css/bootstrap.css" rel="stylesheet" />

</head>
<body style="font-family:Verdana">
<div class="container">
    <div class="row " style="padding-top:40px;">
        <h3 class="text-center" >BOOTSTRAP CHAT EXAMPLE </h3>
        <br /><br />
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    RECENT CHAT HISTORY
                </div>
                <div class="panel-body" id="chat">

                </div>
                <div class="panel-footer" id="send_footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter Message" id="TextMessage" name="TextMessage"/>
                        <input type="hidden" id="receiver" value=""/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-info" type="button" id="submit_msg">SEND</button>
                                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    ONLINE USERS
                </div>
                <div class="panel-body">
                    <ul class="media-list">

                        <?php foreach($Users as $User){ ?>

                        <li class="media">

                            <div class="media-body">

                                <div class="media">
                                    <a class="pull-left" href="#">
                                        <img class="media-object img-circle" style="max-height:40px;" src="<?php echo base_url();?>public/img/user.png" />
                                    </a>
                                    <div class="media-body" >
                                        <h5><a href="#" class="userchat"><?php echo $User->User; ?></a> </h5>
                                    </div>
                                </div>

                            </div>
                        </li>

                        <?php } ?>

                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<script>

    /*
     function to show messages with ajax
     */
    function showMssages() {
        var username    = $('#receiver').val();
        if(username!=''){
        $.ajax({ // create an AJAX call...
            method: "POST",
            url: "<?php echo base_url('/chat/getMessages');?>", // the file to call
            data: { username:username },
            success: function (response) { // on success..
                $('#chat').html(response);// update the DIV
            }
        });
        }
    }



    /*
     show messages when you click on desired user
     */
    $('.userchat').click(function(e){
        e.preventDefault();
        $('#send_footer').show();

        var username = $(this).text();
        $('#receiver').val(username);

        showMssages(username);

    });

    /*
     submit new message with ajax
     */
    $("#submit_msg").click(function () {;
        var text        = $('#TextMessage').val();
        var username    = $('#receiver').val();

        $.ajax({ // create an AJAX call...
            method: "POST",
            url: "<?php echo base_url('/chat/message');?>", // the file to call
            data: { text: text, username:username },
            success: function (response) { // on success..
                $('#chat').html(response);
            }
        });
    });


    setInterval(showMssages,10000);





</script>
</body>
</html>
