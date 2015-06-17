<?php
session_start();
require 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<!--
	Copyright 2014-2015 Zachary Hebert, Patrick Gillespie
	This file is part of Methodocracy.org.

    Methodocracy.org is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

    Methodocracy.org is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with Methodocracy.org.  If not, see <http://www.gnu.org/licenses/>.
	
    Methodocracy TM is a trademark of Methodocracy.org (C)2014-2015, and all rights to that TM are reserved. Any modified versions are required to be marked as changed, so that their problems will not be attributed erroneously to authors of previous versions. And the name Methodocracy TM should be clearly labeled as the source of your work as long as any part of this work remains intact in part or in whole.
-->
<head>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:400italic">
	<!-- The above font is under an open license. www.google.com/fonts/specimen/Ubuntu-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="mainstyle.css">
</head>
<body>
<div id="blackBar">
<div id="buttons">         
    <div class="outer1">
        <a href="index.php"><div id="one" class="button"> Home </div></a>
    </div>
    
    <div class="outer2">
        <a href="about.html"><div id="two" class="button">About</div></a>
    </div>

    <div class="outer1">
        <a href="login.php"><div id="three" class="button">Login</div></a>
    </div>
</div>
</div>
<?php
$user = new User();

if(Session::exists('home')) {
	echo '<p>', Session::flash('home'), '</p>';
}

if($user->isLoggedIn()) {
	?>
	<article>
	<p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>
	
	<ul>
		<li><a href="logout.php">Log out</a></li>
		<li><a href="changepassword.php">Change password</a></li>
		<li><a href="update.php">Update details</a></li>
	</ul>

	<?php

	if($user->hasPermission('admin')) {
	?>
		<p>You're also an administrator!</p>
	<?php
	}
	?>	
		<a href="newargument.php?id=0">New Argument</a><br><br>
		
		<?php
		$db = DB::getInstance();
		$loop = true;
		if(isset($_GET['page'])){
		$page = $_GET['page'];
		} else {
		$page = 1;
		}
		$list = (($page-1)*50)+1;
		$content = array();
		$db->get('arguments', array(
						'argument_id', '=', $list));
		while(improved_var_export($db->results(), true)!='array ()'&&$loop){
		$content = explode("'", improved_var_export($db->results(), true));
		if($content[3] < 2){
		$list++;
		$db->get('arguments', array(
						'argument_id', '=', $list));
		} else {
		?>
		<a href="viewargument.php?id=<?php echo $content[15]; ?>"><?php echo $content[7]; ?></a><br>
		<?php
		$list++;
		$db->get('arguments', array(
						'argument_id', '=', $list));
		}
		if ($list == (($page*50)+1)){
			$loop = false;
		}
		}
		?>
		<p style="text-align: center;">Page <?php echo $page ?><p>
		
		<div style="float:left;">
			<?php 
			if($page!=1){ echo
				'<a href="index.php?page='; echo $page-1; echo'">Previous Page</a>';
			}
			?>
		</div>
		
		<div style="float:right;">
			<?php
			if($list%51==0){ echo
				'<a href="index.php?page='; echo $page+1; echo '">Next Page</a>';
			}
			?>
		</div>
	</article>
	<?php
} else {
	echo '<article>You need to <a href="login.php">log in</a> or <a href="register.php">register</a>!</article>';
}?>
</body>
</html>
