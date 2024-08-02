<ul id="dropdown1" class="dropdown-content">
  <li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'dashboard.php' ?>"><span class="medium material-icons">input</span> <span>Dashboard</span></a> </li>
  <li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'profile.php' ?>"><span class="medium material-icons">perm_identity</span> <span>My Profile</span></a> </li>
  <?php if(checkResource($cn,'company')): ?><li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'company.php' ?>"><span class="medium material-icons">work</span> <span>Company</span></a> </li><?php endif;?>
  <?php if(checkResource($cn,'user')): ?><li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'user.php' ?>"><span class="medium material-icons">supervisor_account</span> <span>Users</span></a> </li><?php endif;?>
  <?php if(checkResource($cn,'roll')): ?><li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'roll.php' ?>"><span class="medium material-icons">supervisor_account</span> <span>Roll</span></a> </li><?php endif;?>
  <?php if(checkResource($cn,'setting')): ?><li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'setting.php' ?>"><span class="medium material-icons">settings</span> <span>Setting</span></a> </li><?php endif;?>
  
  <li class="divider"></li>
  <li><a  class="alink" title="Logout" href="<?php echo $mainUrl.'logout.php' ?>"><span class="medium material-icons">power_settings_new</span> <span>Logout</span></a> </li>
</ul>
<nav class="my-account">
<div class="nav-wrapper">
<ul class="right hide-on-med-and-down">
  <li><a class="dropdown-button" href="#!" data-activates="dropdown1">  My Account  <i class="material-icons right">arrow_drop_down</i></a></li>
</ul>
</div>
</nav>