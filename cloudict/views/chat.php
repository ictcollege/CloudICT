<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="col-md-8">
                <div class="panel panel-default main-panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h4 ><i class="fa fa-user fa-fw"></i> <span class='chat_user'></span>   </h4>
                        </div>

                        <div class="pull-right">
                        </div>
                    </div>
                    <div class="panel-body" id="chat">

                </div>
                <div class="panel-footer" id="send_footer" style="display:none;">
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
                        <?php  if($CurrentUserId !=$User['IdUser']){ ?>
                        <li class="media">
                            <div class="media-body">
                                <div class="media">
                                    <div class="media-body" >
                                            <input type="hidden" id='<?php echo $User['UserName']; ?>' value="<?php echo  $User['IdUser']; ?>">
                                        <h5><a href="#" class="userchat"><?php echo $User['UserName']; ?></a> </h5>
                                    </div>
                                </div>

                            </div>
                        </li>

                        <?php }} ?>

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
    function showMesages() {
        var IdUser    = $('#receiver').val();
        if(IdUser!=''){
        $.ajax({ 
            method: "POST",
            url: "<?php echo base_url('/chat/getMessages');?>", 
            data: { IdUser:IdUser },
            success: function (response) {
                $('#chat').html(response);
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
        $('.chat_user').text(username);
        var IdUser=$("#"+username).val();
        $('#receiver').val(IdUser);

        showMesages();

    });

    /*
     submit new message with ajax
     */
    $("#submit_msg").click(function () {;
        var text        = $('#TextMessage').val();
        var IdUser    = $('#receiver').val();

        $.ajax({ // create an AJAX call...
            method: "POST",
            url: "<?php echo base_url('/chat/message');?>", // the file to call
            data: { text: text, IdUser:IdUser },
            success: function (response) { // on success..
                $('#chat').html(response);
            }
        });
    });


    setInterval(showMssages,10000);


</script>
</body>
</html>
