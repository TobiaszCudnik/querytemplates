<ul>
<?php  if (isset($data) && (is_array($data) || is_object($data))) { foreach($data as $row):  ?><li><?php  if (isset($row)) print $row;  ?>
</li>
<?php  endforeach; }  ?>
		
		
	</ul>