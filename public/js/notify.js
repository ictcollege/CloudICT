   $(document).ready(function () {
			
			
			waitForNotification(); /* Start the inital request */
			
			var toggleStatus=true;
			
			
			$('#toggle-notification').click(function(){
				if(toggleStatus) {
					toggleStatus=false;
					$('#ntf_counter').text("0");
				$.ajax({
                type:'POST',
                url:'Notifications/updateExpire',
                dataType:'json',
                data:{'type':'updateExpire'},
                success:function(func){ 
          }
            });
					
					}
				else toggleStatus=true;
				
			});
        });
		function addNotification(type, data){
        var json=jQuery.parseJSON(data);
		var count=Object.keys(json).length;
		if(type=="new"){
		for(var i=0; i<count; i++){
			var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			var date = new Date((parseInt(json[i].UserNotificationCreated))*1000);
			var hour = date.getHours();
			var minute = date.getMinutes();
			var day = date.getDate();
			var month = months[date.getMonth()];
			
			
            $("#menu1").prepend("<li><a href='"+base_url+json[i].AppLink+"HandleNotification/"+json[i].IdEvent+"'><span class='image' style='margin-right:10px'><i class='"+json[i].NotificationTypeIcon+"'></i></span><span><span>"+json[i].NotificationTypeName+"</span><span class='time'>"+day+"-"+month+" "+hour+":"+minute+"</span></span><br/><span>from "+json[i].UserFullname+"</span><span class='message'>"+json[i].UserNotificationDescription+"</span></a></li>");
			
			$('#ntf_counter').text(parseInt($('#ntf_counter').text())+1);
			}
		}
		
		
		
		else alert("ne radi");
       
    }
	
	function waitForNotification(){
        $.ajax({
            type: "GET",
            url:"Notifications/updateNotifications",
            async: true,
            cache: false,
            timeout:50000, 

            success: function(data){
                addNotification("new", data);
                setTimeout(
                    waitForNotification, 
                    5000 
                );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                addNotification("error", textStatus + " (" + errorThrown + ")");
                setTimeout(
                    waitForNotification,
                    15000);
            }
        });
    };