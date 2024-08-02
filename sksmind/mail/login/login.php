<?php 
session_start();

include("../database.php");
	
	$loginid = $_POST['loginid'];
	$pass = $_POST['pass'];

	$myusername=addslashes($_POST['loginid']);
	$mypassword=addslashes($_POST['pass']);
	 
	$sql="SELECT * FROM user WHERE user_name='$myusername'";

	$result=mysql_query($sql);
	$login_result=mysql_fetch_array($result);
    $current_id = $login_result['user_id'];
	
	if( md5($mypassword) == $login_result['pass'] )
	{
		$_SESSION['login_user']=$current_id;
		$_SESSION['type']= $login_result['type'];
		
		
		
		
		$myFile = "../cuteeditor_files/Configuration/Shared/Common_".$current_id.".config";
		$message = "new file";
		if (!file_exists($myFile)) {
		  $fh = fopen($myFile, 'w');
		  fwrite($fh, $message."\n");
		  fclose($fh);
		}
		
		$f = @fopen("../cuteeditor_files/Configuration/Shared/Common_".$current_id.".config", "r+");
			if ($f !== false) {
				ftruncate($f, 0);
				fclose($f);
			}

	$current_id = $_SESSION['login_user'];
	 
	$sql="SELECT * FROM user WHERE user_id= ".$current_id;

	$result=mysql_query($sql);
	$login_result=mysql_fetch_array($result);
    $user_name = $login_result['user_name'];

	$html = '<?xml version="1.0" encoding="utf-8" ?>
	<configuration>
	  <dropdowns>
		<CssClass>
		  <item text="[[NotSet]]" value="null"></item>
		  <item text="Red Text" value="RedColor">
			<html><![CDATA[<span style=\'color:red\'>RedColor</span>]]></html>
		  </item>
		  <item text="textbold" value="textbold">
			<html><![CDATA[<span class=\'textbold\'>textbold</span>]]></html>
		  </item>
		  <item text="Highlight" value="Highlight">
			<html><![CDATA[<span style=\'background-color: yellow\'>Highlight</span>]]></html>
		  </item>
		</CssClass>
		<CssStyle>
		  <item text="[[NotSet]]" value="null"></item>
		  <item text="font-size:18pt" value="font-size:18pt"></item>
		  <item text="color:red" value="color:red"></item>
		</CssStyle>
		<Codes>
		  <item text="Email signature">
			<value><![CDATA[<font face="Courier New, Courier, mono" size="-1">E-mail: <a href="mailto:fake-company@server.com">fake-company@server.com</a>]]></value>
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/signature.gif\' /> Email signature]]></html>
		  </item>
		  <item text="Contact us">
			<value>
			  <![CDATA[<table> <tr> <td valign="top" style="background-color:#ffffff;border-top:0;border-bottom:0;padding-top:9px;padding-bottom:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="top" align="center" style="padding:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td align="center" style="padding-left:9px;padding-right:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="top" align="center" style="padding-top:9px;padding-right:9px;padding-left:9px"> <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="top"> <table cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse"> <tbody> <tr> <td valign="top" style="padding-right:10px;padding-bottom:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="middle" align="left" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"> <table width="" cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse"> <tbody> <tr> <td width="24" valign="middle" align="center"> <a data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.facebook.com/ShreeInternational-727641680616318/?ref%3Daymt_homepage_panel&amp;source=gmail&amp;ust=1467118975938000&amp;usg=AFQjCNHVM6e38P2d4yxIUpiZk41QY3TptQ" target="_blank" href="https://www.facebook.com/ShreeInternational-727641680616318/?ref=aymt_homepage_panel"><img width="24" height="24" style="display:block;border:0;min-height:auto;outline:none;text-decoration:none" src="https://ci4.googleusercontent.com/proxy/X9MqCnSCvb5f1PshSVntsSqqm9dNg_ie7HbGsGn_ezsyhoBi1KL0re94Q0I4KPY2mGVpcW3dKRZwm_0bekmhL_IFCF7C82_1xXG2ZkrezDWf6kPh_gik805bm8zRcbMSMw=s0-d-e1-ft#http://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" class="CToWUd"></a> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> <table cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse"> <tbody> <tr> <td valign="top" style="padding-right:0;padding-bottom:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse"> <tbody> <tr> <td valign="middle" align="left" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"> <table width="" cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse"> <tbody> <tr> <td width="24" valign="middle" align="center"> <a data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.shreeintl.com/app/home&amp;source=gmail&amp;ust=1467118975938000&amp;usg=AFQjCNGd_nL5xaU3tr22yLschaMINWU7ow" target="_blank" href="http://www.shreeintl.com/app/home"><img width="24" height="24" style="display:block;border:0;min-height:auto;outline:none;text-decoration:none" src="https://ci3.googleusercontent.com/proxy/_uFbA8j5252fdnk4T1_dJcIe3YicShHtrxPXTNzgU81-5pFJl2KE13IBBm4-vmLNjsIyoC7sNVtQSpq--CaF3PHuhb6igzEEPH4WYLOOxYKIPOBarWCqgaZCobMK=s0-d-e1-ft#http://cdn-images.mailchimp.com/icons/social-block-v2/color-link-48.png" class="CToWUd"></a> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="min-width:100%;border-collapse:collapse;table-layout:fixed!important"> <tbody> <tr> <td style="min-width:100%;padding:10px 18px 25px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse"> <tbody> <tr> <td> <span></span> </td></tr></tbody> </table> </td></tr></tbody> </table> <table width="100%" cellspacing="0" cellpadding="0" border="0" style="min-width:100%;border-collapse:collapse"> <tbody> <tr> <td valign="top" style="padding-top:9px"> <table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:100%;min-width:100%;border-collapse:collapse"> <tbody> <tr> <td valign="top" style="padding:0px 18px 9px;color:#85f0e3;text-align:center;word-break:break-word;font-family:Helvetica;font-size:12px;line-height:150%"> <div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong><em><font size="2">BEST REGARDS</font><font size="4" style="color:#351c75">,</font></em></strong></span></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong><font size="2">'.$user_name.'</font></strong></span></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial">&nbsp;</div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong>Rapnet :91552</strong></span></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><strong><span style="color:#000000">Skype &nbsp;:<a data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://shreeintl.hk&amp;source=gmail&amp;ust=1467118975938000&amp;usg=AFQjCNFbv5ysAxSEdRRGQIvpAWlPsxPakg" target="_blank" href="http://shreeintl.hk">shreeintl.hk</a>&nbsp;</span></strong></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong>QQ &nbsp; &nbsp; &nbsp; :1368677354</strong></span></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><strong><font size="4"><span style="color:#000000"><span style="font-size:15px">Web &nbsp; &nbsp;&nbsp;</span>:</span><a data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.shreeintl.com/app/home&amp;source=gmail&amp;ust=1467118975938000&amp;usg=AFQjCNGd_nL5xaU3tr22yLschaMINWU7ow" target="_blank" style="color:#656565;font-weight:normal;text-decoration:underline" href="http://www.shreeintl.com/app/home"><span style="color:#000000">www.shreeintl.com</span></a></font></strong></div><div style="color:#222222;font-family:arial,sans-serif;font-size:small;line-height:normal"> <div>&nbsp;</div><span style="color:#000000"><strong></strong><strong style="font-size:12.8px"><font size="2" face="arial">Unit-1808,18/F,&nbsp;Multifield Plaza,</font></strong></span> </div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"> <div><span style="color:#000000"><strong>3-7A Prat Avenue ,Tsim Sha Tsui,</strong></span></div><div><span style="color:#000000"><strong></strong><strong>Kowloon, Hong Kong.<br>Mobile &nbsp;: <a target="_blank" value="+85260404708" href="tel:%2B852%20%C2%A060404708">+852 &nbsp;60404708</a></strong></span> </div></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong>Tele. &nbsp; &nbsp; :&nbsp; <a target="_blank" value="+85223666047" href="tel:%2B852%2023666047">+852 23666047</a></strong></span></div><div style="color:#222222;font-size:small;line-height:normal;font-family:arial"><span style="color:#000000"><strong>Fax &nbsp; &nbsp; &nbsp; : &nbsp;<a target="_blank" value="+85223666941" href="tel:%2B852%2023666941">+852 23666941</a></strong></span></div></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></table>]]>
			</value>
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/contact.gif\' /> Contact us]]></html>
		  </item>
		</Codes>
		<Links>
		  <item text="CuteSoft" value="http://phphtmledit.com/">
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/signature.gif\' /> CuteSoft]]></html>
		  </item>
		  <item text="Mail to us" value="mailto:support@CuteSoft.Net">
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/email.gif\' /> Mail to us]]></html>
		  </item>
		  <item text="Yahoo.com" value="http://www.yahoo.com/">
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/yahoo.gif\' /> Yahoo.com]]></html>
		  </item>
		  <item text="Google.com" value="http://www.google.com">
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/Google.gif\' /> Google.com]]></html>
		  </item>
		  <item text="MSDN" value="http://msdn.com/">
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/msdn16.png\' /> MSDN]]></html>
		  </item>
		</Links>
		<Images>
		  <item text="Logo">
			<value><![CDATA[<img border="0" src="http://www.cutesoft.net/images/logo.gif" />]]></value>
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/logo_s.gif\' /> Company logo]]></html>
		  </item>
		  <item text="Flower">
			<value><![CDATA[<img border="0" src="http://phphtmledit.com/data/j0313820.jpg" />]]></value>
			<html><![CDATA[<img border=0 align=\'absMiddle\' src=\'http://phphtmledit.com/data/flower_s.gif\' /> Flower]]></html>
		  </item>
		</Images>
		<DropForeColor>
		  <item text="[[NotSet]]" value="null"></item>
		  <item text="Black" color="Black" value="Black"></item>
		  <item text="Red" color="Red" value="Red"></item>
		</DropForeColor>
		<DropBackColor>
		  <item text="[[NotSet]]" value="null"></item>
		  <item text="White" bgColor="White" value="White"></item>
		  <item text="Red" bgColor="Red" value="Red"></item>
		</DropBackColor>
		<Zoom>
		  <item text="400%" value="400"></item>
		  <item text="300%" value="300"></item>
		  <item text="200%" value="200"></item>
		  <item text="100%" value="100"></item>
		  <item text="80%" value="80"></item>
		  <item text="75%" value="75"></item>
		  <item text="66%" value="66"></item>
		  <item text="50%" value="50"></item>
		  <item text="33%" value="33"></item>
		  <item text="25%" value="25"></item>
		</Zoom>
		<FormatBlock>
		  <item text="[[Normal]]" value="&lt;P&gt;">
			<html><![CDATA[[[Normal]]]]></html>
		  </item>
		  <item text="[[Heading 1]]" value="&lt;H1&gt;">
			<html><![CDATA[<b style=\'font-size:24pt\'>[[Heading 1]]</b>]]></html>
		  </item>
		  <item text="[[Heading 2]]" value="&lt;H2&gt;">
			<html><![CDATA[<b style=\'font-size:18pt\'>[[Heading 2]]</b>]]></html>
		  </item>
		  <item text="[[Heading 3]]" value="&lt;H3&gt;">
			<html><![CDATA[<b style=\'font-size:15pt\'>[[Heading 3]]</b>]]></html>
		  </item>
		  <item text="[[Heading 4]]" value="&lt;H4&gt;">
			<html><![CDATA[<b style=\'font-size:12pt\'>[[Heading 4]]</b>]]></html>
		  </item>
		  <item text="[[Heading 5]]" value="&lt;H5&gt;">
			<html><![CDATA[<b style=\'font-size:9pt\'>[[Heading 5]]</b>]]></html>
		  </item>
		  <item text="[[Heading 6]]" value="&lt;H6&gt;">
			<html><![CDATA[<b style=\'font-size:7pt\'>[[Heading 6]]</b>]]></html>
		  </item>
		  <item text="[[Address]]" value="&lt;Address&gt;">
			<html><![CDATA[[[Address]]]]></html>
		  </item>
		  <item text="[[MenuList]]" value="&lt;MENU&gt;">
			<html><![CDATA[[[MenuList]]]]></html>
		  </item>
		  <item text="[[Formatted]]" value="&lt;PRE&gt;">
			<html><![CDATA[[[Formatted]]]]></html>
		  </item>
		  <item text="[[Definition Term]]" value="&lt;DT&gt;">
			<html><![CDATA[[[Definition Term]]]]></html>
		  </item>
		</FormatBlock>
		<FontName>
		  <item text="Arial" html="&lt;font size=3 face=\'Arial\'&gt;Arial&lt;/font&gt;">Arial</item>
		  <item text="Verdana" html="&lt;font size=3 face=\'Verdana\'&gt;Verdana&lt;/font&gt;">Verdana</item>
		  <item text="Comic Sans MS" html="&lt;font size=3 face=\'Comic Sans MS\'&gt;Comic Sans MS&lt;/font&gt;">Comic Sans MS</item>
		  <item text="Courier" html="&lt;font size=3 face=\'Courier\'&gt;Courier&lt;/font&gt;">Courier</item>
		  <item text="Georgia" html="&lt;font size=3 face=\'Georgia\'&gt;Georgia&lt;/font&gt;">Georgia</item>
		  <item text="Impact" html="&lt;font size=3 face=\'Arial\'&gt;Impact&lt;/font&gt;">Impact</item>
		  <item text="Lucida Console" html="&lt;font size=3 face=\'Lucida Console\'&gt;Lucida Console&lt;/font&gt;">Lucida Console</item>
		  <item text="Tahoma" html="&lt;font size=3 face=\'Tahoma\'&gt;Tahoma&lt;/font&gt;">Tahoma</item>
		  <item text="Times New Roman" html="&lt;font size=3 face=\'Times New Roman\'&gt;Times New Roman&lt;/font&gt;">Times New Roman</item>
		  <item text="Wingdings" html="&lt;font size=3 face=\'Wingdings\'&gt;Wingdings&lt;/font&gt;">Wingdings</item>
		</FontName>
		<FontSize>
		  <item text="[[NotSet]]" value="null">
			<html><![CDATA[[[NotSet]]]]></html>
		  </item>
		  <!-- <item text="9pt" html="&lt;span style=\'font-size:9pt\'&gt;9pt&lt;/span&gt;">9pt</item> -->
		  <!-- <item text="12px" html="&lt;span style=\'font-size:12px\'&gt;12px&lt;/span&gt;">12px</item>-->
		  <item text="1 (8pt)" value="1">
			<html><![CDATA[<font size=\'1\'>Size 1 </font>(8pt)]]></html>
		  </item>
		  <item text="2 (10pt)" value="2">
			<html><![CDATA[<font size=\'2\'>Size 2 </font>(10pt)]]></html>
		  </item>
		  <item text="3 (12pt)" value="3">
			<html><![CDATA[<font size=\'3\'>Size 3 </font>(12pt)]]></html>
		  </item>
		  <item text="4 (14pt)" value="4">
			<html><![CDATA[<font size=\'4\'>Size 4 </font>(14pt)]]></html>
		  </item>
		  <item text="5 (18pt)" value="5">
			<html><![CDATA[<font size=\'5\'>Size 5 </font>(18pt)]]></html>
		  </item>
		  <item text="6 (24pt)" value="6">
			<html><![CDATA[<font size=\'6\'>Size 6 </font>(24pt)]]></html>
		  </item>
		  <item text="7 (36pt)" value="7">
			<html><![CDATA[<font size=\'7\'>Size 7 </font>(36pt)]]></html>
		  </item>
		</FontSize>
	  </dropdowns>

	  <contextmenus>
	  </contextmenus>
	</configuration>';


	file_put_contents("../cuteeditor_files/Configuration/Shared/Common_".$current_id.".config",$html);

	echo "success";
	}
	else
	{
		echo "fail";
	}
