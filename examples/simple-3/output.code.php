<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Example: simple-2</title>
</head>
<body>
	<?php 
 if (isset($data[1])): 
 ?><div class="my-div">
		<span>List</span>
		<ul>
<!-- notice structure of first LI and two following LIs --><?php 
 foreach($data as $row): 
 ?><li>
				<span class="field1"><?php 
 print $row["field1"] 
 ?></span>,
				<span class="field2"><?php 
 print $row["field2"] 
 ?></span>,
				<span class="field3"><?php 
 print $row["field3"] 
 ?></span>
			</li>
<?php 
 endforeach; 
 ?>
			
			
		</ul>
</div>
<?php 
 endif; 
 ?>
</body>
</html>
