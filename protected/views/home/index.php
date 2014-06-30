<?php
$this->breadcrumbs=array(
);
$image_url = $user->poto ? $user->poto : 'blank_avatar_240x240.gif';
?>

<div class="subcolumns">
	<div class="c15l">
		<div class="avatar">
			<img src="<?php echo Yii::app()->baseUrl.'/media/user/'.$image_url?>" width="150px" height="150px" style="border: 2px solid black"/>
		</div>
	</div>
	<div class="c85r">
		<p>Welcome, <?php echo ucfirst($user->username) ?></p>
		<p>Today is <?php echo date('l, d F Y',  time()) ?></p>
	</div>
</div>
<br />
<div class="subcolumns">
	<h3>News Update</h3>
	<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p><p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam;</p>
</div>