 </div>
  </div>
<!--footer-->
<div class="navbar-inverse navbar-fixed-bottom" style="background-color:rgba(15,15,15,0.9);" role="navigation">
	<div class="container" style="margin:0px;" >
		<a href="http://www.encoding-ideas.com" style="color:white; font-size:2vmin ;">
			<img src="<?php echo base_url()?>static/img/mini_logo.png" style="height:30px;" alt="">
			Desarrollado por <b>Encoding Ideas</b>
		</a>
	</div>
</div>

<?php  
	if ( ! empty($js))
	{
		foreach ($js as $key => $value) 
		{
			echo "<script src='$value' type='text/javascript'></script>";
		}
	}
	
	echo @$funcion;
?>
</body>
</html>