<html>
<head>
	<title>NACL Inventory System Installer</title>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="en-us">
	<meta name="author" content="NACL">
	<meta name="Copyright" content="Copyright (c) 2008 NACL">
	<meta name="document-type" content="Public">
	<meta name="document-rating" content="Safe for Kids">
	<meta name="document-distribution" content="Global">
	
	<style>
	body { margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; }
	* {font-family:arial;}
	.copyright {font-size:8pt; color:#999999;}
	.systemtitle {color:#FFF; font-weight:bold; font-size:16pt; text-align:right;}
	.functiontitle {color:#ED0505; font-weight:bold; font-size:16pt;padding: 10px;}
	.maintable {border-left:1px solid #CCC; border-right:1px solid #CCC; border-bottom:1px solid #CCC;}
	.content td {font-size:10pt;}
	.content p {font-size:10pt;}
	.error {color:red; font-weight:bold;}
	.ok {color:green; font-weight:bold;}
	</style>
</head>
<body>
<table border=0 cellpadding="5" cellspacing="0" width="780" align="center" class="maintable">
<form name="installform" method="post">
<input type="hidden" name="step" value="<?=$nextStep?>">
<input type="hidden" name="back" value="<?=$prevStep?>">
	<tr>
		<td bgcolor="#333333" colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%" align="center">
			<tr>
				<td><image src="../images/logo.gif" border="0" align="absmiddle"></td>
				<td class="systemtitle">NACL Inventory System</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="8"><img src="images/spacer.gif" width="1" height="450"></td>
		<td width="778" valign="top">
			<table border=0 cellpadding="5" cellspacing="0" width="100%" align="center">
			<tr height="40">
				<td width="770" class="functiontitle">Installer</td>
				<td width="8"></td>
			</tr>
			<tr>
		    <td valign="top">
		    	<h4 style="margin-top: 10px; margin-bottom: 5px; padding: 10px;"><?=$title?></h4>
		    <div style="padding: 10px;" class="content"><? if ($includefile) include($includefile); else echo $content?></div></td>
				<td><img src="images/spacer.gif" width="1" height="350"></td>
			</tr>
			<tr height="40">
		    <td valign="middle">
<? if (!$completed) { ?>
		    	<table width="100%">
		    	<tr>
		    		<td width="33%">
		<? if ($step > 0) { ?>		    
		    			<input type="submit" name="submit" value="&lt;&lt; Back">
		<? } ?>
						</td>
						<td width="33%" align="center">
		<? if (!$thisStepOK) { ?>    
		    		<input type="submit" name="submit" value="Retry">
		<? } ?>
						</td>
						<td width="33%" align="right">
		<? if ($thisStepOK) { ?>    
		    			<input type="submit" name="submit" value="Next &gt;&gt;">
		<? } ?>
		    		</td>
		    	</tr>
		    	</table>
		    </td>
				<td></td>
			</tr>
			</table>
<? } ?>
		</td>
		<td width="1"><img src="images/spacer.gif" width="1" height="450"></td>
	</tr>
	<tr><td colspan="3"><div align="right" class="copyright"><a href="http://www.nacl.hk">&copy;2008 NACL All rights reserved.</a></div></td></tr>
</form>
</table>
</body>
</html>
