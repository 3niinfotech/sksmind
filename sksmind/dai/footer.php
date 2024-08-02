
<div class="footer">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120">
				<span class="blue bolder">DaiSoft</span>
				Application &copy; 2017. Powered By :<a href="http://3niinfotech.com/" target="_blank"> 3ni infotech</a>
			</span>

			
		</div>
	</div>
</div>
<?php /*
<div class="direct-chat">
	<div class="chat-header">
	  <h4 class="chat-title"> &nbsp;&nbsp;<i class="fa fa-comments"></i> &nbsp;Chat </h4>

	  <div class="box-tools">
		<span id="totalnewmsg"></span> 
		<button type="button" class="btn btn-box-tool" onClick="minimize()"><i class="fa fa-minus"></i>	</button>
		<button type="button" class="btn btn-box-tool" onClick="loadUsers()"><i class="fa fa-comments"></i>	</button>	
		<button type="button" class="btn btn-box-tool" onClick="clostChat()" ><i class="fa fa-times"></i></button>
	  </div>
	</div>	
	<div class="chat-body" style="display:none;">		
	</div>
	
	<div id="chat-loading" style="display:none">
		<img src="<?php echo  $daiUrl;?>assets/images/chat2.gif" />
	</div>
</div>
*/ ?>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
<!--[if !IE]> -->
<script>
 /* var   al  = window.setInterval(function(){
 autoLoad();
}, 3000);  

var alA  = window.setInterval(function(){
 autoLoadAllData();
}, 10000);


 //clearInterval(al);
function loadChatWindow(id,flag)
{
	var title = '&nbsp;&nbsp;<i class="fa fa-comments"></i> &nbsp;';
	if(flag == 0)
	{	
		jQuery('#chat-loading').show(); }

		var data = {'id':id,'flag':flag};
		jQuery.ajax({
			url: '<?php echo $daiUrl.'/chatDetail.php' ?>', 
			type: 'POST',
			data: data,		
			success: function(result)
				{		
					if(result != "")
					{
						if(flag == 0)
						{	jQuery('#chat-loading').hide(); }
					
						if(flag == 1)
						{
							$('.direct-chat-messages').html(result);									
							var d = $('.direct-chat-messages');
							d.scrollTop(d.prop("scrollHeight"));							
						}
						else
						{
							var name = title+jQuery('#user-'+id+' .user-name h5').html();
							$('.chat-body').html(result);									
							var d = $('.direct-chat-messages');
							d.scrollTop(d.prop("scrollHeight"));
							
							
							jQuery('.chat-title').html(name);
							jQuery('#chat-form').submit(function(e)
							{
								e.preventDefault();
								saveChat(this);
								
							});
						}
						
						
					}
				}
		});
			
}

function loadChatHistory(id)
{
	//jQuery('#please-wait').show();
		var data = {'id':id};
		jQuery.ajax({
			url: '<?php echo $daiUrl.'/chatHistory.php' ?>', 
			type: 'POST',
			data: data,		
			success: function(result)
				{		
					if(result != "")
					{
						var res = result;
						res = res.trim();
						if(res != 'false')
						{	
							$('.direct-chat-messages').append(result);									
							var d = $('.direct-chat-messages');
							d.scrollTop(d.prop("scrollHeight"));
						}	
					}
				}
		});
			
}

function loadUsers()
{
	//jQuery('#please-wait').show();
	var title = '&nbsp;&nbsp;<i class="fa fa-comments"></i> &nbsp; Chat';
	jQuery('.chat-title').html(title);
		var data = {'id':0};
		jQuery.ajax({
			url: '<?php echo $daiUrl.'/chatUser.php' ?>', 
			type: 'GET',
			data: data,		
			success: function(result)
				{		
					if(result != "")
					{
						$('.chat-body').html(result);									
						//jQuery('#please-wait').hide();
						
					}
				}
		});
			
}

function saveChat(fm)
{
	//var data = jQuery('#chat-form').serialize();
	//console.log(data);
	var formData = new FormData(fm);	
	var file_data = jQuery('#attachement').prop('files')[0];
	formData
	console.log(formData);
    formData.append('attachement', file_data);		
	jQuery('#chat-loading').show();	
	jQuery.ajax({
		url: '<?php echo $moduleUrl.'outward/outwardController.php' ?>', 
		type: 'POST',
		data: formData,
		processData: false, 
		contentType: false,	
		success: function(result)
			{		
				if(result != "")
				{
					jQuery('#chat-loading').hide();
					var obj = jQuery.parseJSON(result);
					
					if(obj.status)
					{
//var res = obj.message;
						//var message = res.replace("jeet", img);
						jQuery('.no-conversation').remove();
						jQuery('.direct-chat-messages').append(obj.message);
						var d = $('.direct-chat-messages');
						d.scrollTop(d.prop("scrollHeight"));
						jQuery('#message').val('');
						jQuery('#attachement').val('');
					}
				}
			}
	});
		
}

function minimize()
{
	jQuery('.chat-body').slideToggle();
}

function autoLoad()
{
	
	if(jQuery('.direct-chat-success').is(':visible'))
	{
		var tid = jQuery('.direct-chat-success').attr('userid');
		loadChatHistory(tid);
	}
	else
	{
		loadUsers();
	}
}

function autoLoadAllData()
{
	if(jQuery('.direct-chat-success').is(':visible'))
	{
		var tid = jQuery('.direct-chat-success').attr('userid');
		loadChatWindow(tid,1);
	}
}

function clostChat()
{
	jQuery('.direct-chat').remove();
	clearInterval(al);
	clearInterval(alA);
}
function changeFile(file)
{
	var fileName = file.files[0]. name;	
	jQuery('#message').val(fileName);
} */
</script>
<style>

</style>