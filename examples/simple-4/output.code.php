<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Example: simple-3</title>
</head>
<body>
	<?php  if (isset($data[1])) {  ?>
		<span>List</span>
		<ul>
<!-- notice structure of first LI and two following LIs --><?php  if (isset($data) && (is_array($data) || is_object($data))) { foreach($data as $row):  ?><li>
				<span class="field1"><?php  if (isset($row['field1'])) print $row['field1'];
else if (isset($row->{'field1'})) print $row->{'field1'};  ?></span>,
				<span class="field2"><?php  if (isset($row['field2'])) print $row['field2'];
else if (isset($row->{'field2'})) print $row->{'field2'};  ?></span>,
				<span class="field3"><?php  if (isset($row['field3'])) print htmlspecialchars($row['field3']);
else if (isset($row->{'field3'})) print htmlspecialchars($row->{'field3'});  ?></span>
			</li>
<?php  endforeach; }  ?>
			
			
		</ul>
<?php  }  ?>
</body>
</html>
