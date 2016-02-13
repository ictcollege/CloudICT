<div class="col-md-3 pull-right" id="main_<?php echo $Reciever; ?>">
	<div class="panel panel-primary main-panel"  id="min-chat">
		<div class="panel-heading">
			<div class="pull-left">
				<h4 ><i class="fa fa-user fa-fw"></i> <span class='small_chat_user'> <?php echo $Reciever; ?> </span> </h4>
			</div>
			<div class="pull-right">
				<a href="#" class="pull_left close_chat" id= "<?php echo $Reciever; ?>"  style="color:white"><h4 ><i class="fa fa-times  fa-fw"></i></h4></a>
			</div>
		</div>
		<div class="panel-body small_chat small_chat<?php echo $Reciever; ?>" >
			<?php foreach($Messages as $Message){?>
				<div class="media">
					<a class="pull-left" href="#"></a>
					<div class="media-body" >
						<?php echo $Message['Message']; ?>
						<br />
						<small class="text-muted"> <?php echo $Message['Sender']; ?> |  <?php echo date('H:i d M Y',$Message['Time']);?></small>
						<hr />
					</div>
				</div>
			 <?php    } ?>
		</div>
		<div class="panel-footer" id="send_footer" >
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Enter Message" id="TextMessageSmall<?php echo $Reciever; ?>" name="TextMessageSmall"/>
				<?php if(isset($IdReceiver)){ ?>
					<input type="hidden" id="receiver_small_chat<?php echo $Reciever; ?>" value="<?php echo $IdReceiver; ?>"/>
				<?php } 
				else{ ?>
					<input type="hidden" id="receiver_small_chat<?php echo $Reciever; ?>" value=""/>
				<?php } ?>
				<span class="input-group-btn">
					<button class="btn btn-info" type="button" id="submit_small_msg_<?php echo $Reciever; ?>">SEND</button>
				</span>
			</div>
		</div>
	</div>
	<script type="text/javascript">

	function scrollSmallChat(chat){
		var $cont = $(chat);
		$cont[0].scrollTop = $cont[0].scrollHeight;
	}

	scrollSmallChat(".small_chat<?php echo $Reciever; ?>");
	/**
	 *  close pop up chat
	 */
		$('.close_chat').click(function(e){
			e.preventDefault();
			var user=$(this).attr('id');
			$("#main_"+user).remove();

		});

		/*
	 submit new message with ajax
	 */
	$("#submit_small_msg_<?php echo $Reciever; ?>").click(function () {
		var text        = $('#TextMessageSmall<?php echo $Reciever; ?>').val();
		var IdUser    = $('#receiver_small_chat<?php echo $Reciever; ?>').val();

		if(IdUser == ""){
			$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/GroupMessage');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('.small_chat<?php echo $Reciever; ?>').html(response);
						$('#TextMessageSmall<?php echo $Reciever; ?>').val("");
						scrollSmallChat(".small_chat<?php echo $Reciever; ?>");
					}
				});
		}
		else{
			$.ajax({ // create an AJAX call...
				method: "POST",
				url: "<?php echo base_url('/chat/message');?>", // the file to call
				data: { text: text, IdUser:IdUser },
				success: function (response) { // on success..
					$('.small_chat<?php echo $Reciever; ?>').html(response);
					$('#TextMessageSmall<?php echo $Reciever; ?>').val("");
					scrollSmallChat(".small_chat<?php echo $Reciever; ?>");
				}
			});
		}
	});

	/*
	 submit new message with ajax with Enter keypress
	 */
	$('#TextMessageSmall<?php echo $Reciever; ?>').keypress(function(e) {
		 if( e.which == 13 ) {
			var text        = $('#TextMessageSmall<?php echo $Reciever; ?>').val();
			var IdUser    = $('#receiver_small_chat<?php echo $Reciever; ?>').val();

			if(IdUser == ""){
				
				$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/GroupMessage');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('.small_chat<?php echo $Reciever; ?>').html(response);
						$('#TextMessageSmall<?php echo $Reciever; ?>').val("");
						scrollSmallChat(".small_chat<?php echo $Reciever; ?>");
					}
				});
			}
			else{
				$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/message');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('.small_chat<?php echo $Reciever; ?>').html(response);
						$('#TextMessageSmall<?php echo $Reciever; ?>').val("");
						scrollSmallChat(".small_chat<?php echo $Reciever; ?>");
					}
				});
			}
		 }
	} );

	/**
	 * 
	 */
	function showSmallMesages<?php echo $Reciever; ?>() {
		var IdUser    = $('#receiver_small_chat<?php echo $Reciever; ?>').val();
		if(IdUser!=''){
			$.ajax({ 
				method: "POST",
					url: "<?php echo base_url('/chat/showMessage');?>", 
					data: { IdUser:IdUser },
					success: function (response) {
						$('.small_chat<?php echo $Reciever; ?>').html(response);
					}
			});
		}
		else{
			$.ajax({ 
				method: "POST",
				url: "<?php echo base_url('/chat/showGroupMessages');?>", 
				success: function (response) {
					$('.small_chat<?php echo $Reciever; ?>').html(response);
				}
			});
		}
	}

	// setInterval(showSmallMesages<?php echo $Reciever; ?>,2000);
	</script>
</div>