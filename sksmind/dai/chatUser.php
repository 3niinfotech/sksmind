<?php 
session_start();

include("../database.php");
include("../variable.php");
if(!isset($_SESSION['username'])):
	header('Location: ' .$mainUrl);
endif; 	

include_once('Helper.php');

$helper  = new Helper($cn);
$user = $helper->getAllUser();
$totalNewMsg = 0;
?>	
<ul class="user-list">
			
			<?php foreach($user as $k=>$val): 
			if($_SESSION['userid'] == $k)
				continue;
			
			$image = ($val['profile_image'] != '' ) ? $val['profile_image'] :'default.png' ;
			$newcnt = $helper->getNewMessageCount($k);
			$totalNewMsg  += $newcnt;
			?>
			<li id="user-<?php echo $k ?>" onClick="loadChatWindow(<?php echo $k ?>,0)">
			
				<div class="profile-image"><img src="<?php echo $imgUrl.$image ?>" ></div>
				<div class="user-name"><h5><?php echo $val['first_name']?> <?php echo $val['last_name']?></h5><span><?php echo $val['user_email']?></span> </div>
				<div class="online-status <?php if($val['online']):?> bg-green <?php endif; ?>"> </div>
				<?php if($newcnt > 0): ?><span class="new-count"><?php echo $newcnt;?></span><?php endif;?>
			</li>
			<?php endforeach; ?>
			
		</ul>
<script>
<?php if($totalNewMsg > 0):?>
jQuery('#totalnewmsg').html('<span title="New Messages" class="badge bg-red1"><?php echo $totalNewMsg?></span>')
<?php else: ?>
jQuery('#totalnewmsg').html('');
<?php endif;?>
</script>