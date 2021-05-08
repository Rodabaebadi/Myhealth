<?php
include("includes/header.php");
?>

<div class="user_details column">
	<a href="#"><img src="<?php echo $user['profile_pic']; ?>"> </a>

	<div class="user_details_left_right">
		<a href="#">
		<?php
		echo $user['first_name'] . " " . $user['last_name'];
		?>
		</a>
	</div>
		


</div>

</div>

</body>
</html>



