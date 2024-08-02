<?php 
session_start();

include("../database.php");
include("../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 	

include_once('Helper.php');


$helper  = new Helper($cn);
$userid = $_SESSION['userid'];
$toId = $_POST['id'];
$current_user = $helper->getUserDetail($_SESSION['userid']);
$to_user = $helper->getUserDetail($toId);

$history = $helper->getChatHistory($userid,$toId);

$flag = $_POST['flag'];

$cimage = ($current_user['profile_image'] != '' ) ? $current_user['profile_image'] :'default.png' ;
$timage = ($to_user['profile_image'] != '' ) ? $to_user['profile_image'] :'default.png' ;
?>	

	
<?php if($flag == 0): ?>
<div class="direct-chat-success" userid="<?php echo $toId?>" >

	<div class="direct-chat-messages">	

	<?php if(!empty($history)): 
		$f_flag = 0;
		$t_flag = 0;
		foreach($history as $his):
		 if($his['sender'] == $userid ): 
		$t_flag = 0;
		?>
		
			<?php if($f_flag) : ?>
			
				<div class="direct-chat-msg right">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			
			<?php else: $f_flag = 1; ?>
				<div class="direct-chat-msg right new-chat-msg">
				  <div class="direct-chat-info clearfix">
					<span class="direct-chat-name pull-right"><?php echo $current_user['first_name']; ?></span>
					<span class="direct-chat-timestamp pull-left"><?php echo $helper->getDateDifferenece($his['date']); ?></span>
				  </div>	 
				  <img class="direct-chat-img" src="<?php echo $imgUrl.$cimage; ?>" alt="">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			<?php endif; ?>
			
			
		<?php else: 
		$f_flag = 0;
		?>
			<?php if($t_flag) : ?>
			
				<div class="direct-chat-msg ">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			
			<?php else: $t_flag = 1; ?>
				<div class="direct-chat-msg new-chat-msg">
					  <div class="direct-chat-info clearfix">
						<span class="direct-chat-name pull-left"><?php echo $to_user['first_name']; ?></span>
						<span class="direct-chat-timestamp pull-right"><?php echo $helper->getDateDifferenece($his['date']); ?></span>
					  </div>
					
					  <img class="direct-chat-img" src="<?php echo $imgUrl.$timage; ?>" alt="">
					  <div class="direct-chat-text">
						<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
					  </div>	 
				</div>
			<?php endif; ?>
		
		<?php endif; ?>
	<?php endforeach; ?>
		<div id="forScroll"></div>
	<?php else: ?>
		<div class="no-conversation" > There is no conversation found between You !!!</div>
	<?php endif; 
	
	?>
	
	</div>
 
	<div class="box-footer" style="position:relative;">
	  <form id="chat-form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="sender" value="<?php echo $userid?>">
		<input type="hidden" name="receiver" value="<?php echo $toId?>">
		<input type="hidden" name="fn" value="chat">
		<input type="hidden" name="url" value="<?php echo $imgDir;?>">
		<input type="hidden" name="urlpath" value="<?php echo $imgUrl;?>">
		<div class="input-group">
			<input name="message" id="message" placeholder="Type Message ..." class="form-control" type="text" style="width:205px !important;padding-right:35px;">
			  <span class="input-group-btn">
				<button type="submit" class="btn btn-success btn-flat">Send</button>
			  </span>
		</div>
		<div class="attachement-block">
			<input id="attachement" type="file" name="attachement" onChange="changeFile(this)">
			<i class="fa fa-paperclip"></i>
		</div>
		
	  </form>
	</div>
</div>

<?php else: ?>


	<?php if(!empty($history)): 
		$f_flag = 0;
		$t_flag = 0;
		foreach($history as $his):
		 if($his['sender'] == $userid ): 
		$t_flag = 0;
		?>
		
			<?php if($f_flag) : ?>
			
				<div class="direct-chat-msg right">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			
			<?php else: $f_flag = 1; ?>
				<div class="direct-chat-msg right new-chat-msg">
				  <div class="direct-chat-info clearfix">
					<span class="direct-chat-name pull-right"><?php echo $current_user['first_name']; ?></span>
					<span class="direct-chat-timestamp pull-left"><?php echo $helper->getDateDifferenece($his['date']); ?></span>
				  </div>	 
				  <img class="direct-chat-img" src="<?php echo $imgUrl.$cimage; ?>" alt="">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			<?php endif; ?>
			
			
		<?php else: 
		$f_flag = 0;
		?>
			<?php if($t_flag) : ?>
			
				<div class="direct-chat-msg ">
				  <div class="direct-chat-text">
					<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
				  </div>	  
				</div>
			
			<?php else: $t_flag = 1; ?>
				<div class="direct-chat-msg new-chat-msg">
					  <div class="direct-chat-info clearfix">
						<span class="direct-chat-name pull-left"><?php echo $to_user['first_name']; ?></span>
						<span class="direct-chat-timestamp pull-right"><?php echo $helper->getDateDifferenece($his['date']); ?></span>
					  </div>
					
					  <img class="direct-chat-img" src="<?php echo $imgUrl.$timage; ?>" alt="">
					  <div class="direct-chat-text">
						<?php if($his['attachement'] != ''): ?>
						<a href="<?php echo $imgUrl.'attachement/'.$his['attachement'] ?>" target="_blank" ><i class="fa fa-download"></i>	 <?php echo $his['attachement']; ?></a>
					<?php else: ?>
					<?php echo $his['message']; ?>
					<?php endif; ?>
					  </div>	 
				</div>
			<?php endif; ?>
		
		<?php endif; ?>
	<?php endforeach; ?>
		<div id="forScroll"></div>
	<?php else: ?>
		<div class="no-conversation" > There is no conversation found between You !!!</div>
	<?php endif; ?>

<?php endif; ?>