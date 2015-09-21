<div id="page-wrapper">
	<div class="row row-padding-top">
		<div class="col-md-8">
			<div class="panel panel-default main-panel">
				<div class="panel-heading">
					<div class="pull-left">
						<h4 ><i class="fa fa-user fa-fw"></i> <span class='chat_user'>  All Users</span> </h4>
					</div>
					<div class="pull-right">
						<a href="#" id="pop_chat" style='float:right'  ><h4 ><i class="fa fa-commenting  fa-fw"></i></h4></a>
						<a href="#" id="export_chat" style='float:right'  ><h4 ><i class="fa fa-file-text fa-fw"></i></h4></a>
					</div>
				</div>
				<div class="panel-body" id="chat">
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
						<li class="media">
							<div class="media-body">
								<div class="media">
									<div class="media-body" >
										<h5><a href="#" class="groupchat">All Users</a> </h5>
									</div>
								</div>

							</div>
						</li>
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


		<div class="col-md-10" id="chat_box">
			
		</div>



	</div>
</div>

	  

<script type="text/javascript">

	/*
	 function to show messages with ajax
	 */
	function showMesages() {
		var IdUser    = $('#receiver').val();
		if(IdUser!=''){
			$.ajax({ 
				method: "POST",
					url: "<?php echo base_url('/chat/showMessage');?>", 
					data: { IdUser:IdUser },
					success: function (response) {
						$('#chat').html(response);
					}
			});
		}
		else{
			$.ajax({ 
				method: "POST",
				url: "<?php echo base_url('/chat/showGroupMessages');?>", 
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
		$('#TextMessage').val("");
		var username = $(this).text();
		$('.chat_user').text(username);
		var IdUser=$("#"+username).val();
		$('#receiver').val(IdUser);

		showMesages();

	});

	/*
	 show messages when you click on desired group
	 */
	$('.groupchat').click(function(e){
		e.preventDefault();
		$('#receiver').val("");
		$('.chat_user').text('All Users');
		$('#TextMessage').val("");
		showMesages();

	});

	/**
	 * Export chat to .txt file 
	 */
	$('#export_chat').click(function(e){
		e.preventDefault();
		var IdUser = $('#receiver').val();
		if(IdUser == ""){
			$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/exportGroupChat');?>", // the file to call
					data: {  IdGroup:1 },
					success: function (response) { // on success..
						 window.open("<?php echo base_url('/chat/exportGroupChat');?>");
					}
				});
		}
		else{
			$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/exportChat');?>", // the file to call
					data: { IdUser:IdUser },
					success: function (response) { // on success..
						 window.open("<?php echo base_url('/chat/exportChat');?>"+"/"+IdUser);
					}
				});
		}
		
		

	});


	$('#pop_chat').click(function(e){
		e.preventDefault();
		var IdUser    = $('#receiver').val();
		if(IdUser!=''){
			$.ajax({ 
				method: "POST",
				url: "<?php echo base_url('/chat/showSmallMessage');?>", 
				data: { IdUser:IdUser },
				success: function (response) {
					$('#chat_box').append(response);
				}
			});
		}
		else{
			$.ajax({ 
				method: "POST",
				url: "<?php echo base_url('/chat/showSmallGroupMessages');?>", 
				success: function (response) {
					$('#chat_box').append(response);
				}
			});
		}
	});

	


	/*
	 submit new message with ajax with Enter keypress
	 */
	$('#TextMessage').keypress(function(e) {
		 if( e.which == 13 ) {
			var text        = $('#TextMessage').val();
			var IdUser    = $('#receiver').val();

			if(IdUser == ""){
				
				$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/GroupMessage');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('#chat').html(response);
						$('#TextMessage').val("");
					}
				});
			}
			else{
				$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/message');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('#chat').html(response);
						$('#TextMessage').val("");
					}
				});
			}
		 }
	} );

	/*
	 submit new message with ajax
	 */
	$("#submit_msg").click(function () {
		var text        = $('#TextMessage').val();
		var IdUser    = $('#receiver').val();

		if(IdUser == ""){
			$.ajax({ // create an AJAX call...
					method: "POST",
					url: "<?php echo base_url('/chat/GroupMessage');?>", // the file to call
					data: { text: text, IdUser:IdUser },
					success: function (response) { // on success..
						$('#chat').html(response);
						$('#TextMessage').val("");
					}
				});
		}
		else{
			$.ajax({ // create an AJAX call...
				method: "POST",
				url: "<?php echo base_url('/chat/message');?>", // the file to call
				data: { text: text, IdUser:IdUser },
				success: function (response) { // on success..
					$('#chat').html(response);
					$('#TextMessage').val("");
				}
			});
		}
	});


	setInterval(showMesages,10000);


</script>
</body>
</html>
