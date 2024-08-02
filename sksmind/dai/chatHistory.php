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

$history = $helper->getLiveChatHistory($userid,$toId);
$cimage = ($current_user['profile_image'] != '' ) ? $current_user['profile_image'] :'default.png' ;
$timage = ($to_user['profile_image'] != '' ) ? $to_user['profile_image'] :'default.png' ;

?>	

<?php if(!empty($history)): ?>	
	<?php 
	$f_flag = 0;
	$t_flag = 0;
	foreach($history as $his):?>	
		
		
		<?php if($his['sender'] == $userid ): 
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
				<div class="direct-chat-msg right">
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
				<div class="direct-chat-msg">
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
	<?php 
		$helper->setChatViewed($his['id']);
	endforeach; ?>
	<?php else: ?>
	false
	<?php endif; ?>