<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Example: intermediate-1</title>
</head>
<body>
	<div class="my-div">
		<ul>
<?php 
 foreach($data as $row): 
 ?><li>
				<strong>LI</strong>
				<dl>
<dt><strong>Model-1-field-1</strong></dt>
					<dd class="field-1"><?php 
 print is_object($row["Model-1"])
	? $row["Model-1"]->field-1
	: $row["Model-1"]['field-1'] 
 ?>
</dd>
				</dl>
<dl>
<dt><strong>Model-1-field-2</strong></dt>
					<dd class="field-2"><?php 
 print is_object($row["Model-1"])
	? $row["Model-1"]->field-2
	: $row["Model-1"]['field-2'] 
 ?>
</dd>
				</dl>
<dl>
<dt><strong>Model-1-field-3</strong></dt>
					<dd class="field-3">Lorem ipsum</dd>
				</dl>
<dl>
<dt><strong>Model-2-field-1</strong></dt>
					<dd class="Model-2-field-1"><?php 
 print is_object($row["Model-2"])
	? $row["Model-2"]->field-1
	: $row["Model-2"]['field-1'] 
 ?>
</dd>
				</dl>
<dl>
<dt><strong>Model-2-field-2</strong></dt>
					<dd class="Model-2-field-2"><?php 
 print is_object($row["Model-2"])
	? $row["Model-2"]->field-2
	: $row["Model-2"]['field-2'] 
 ?>
</dd>
				</dl>
<dl>
<dt><strong>Model-2-field-3</strong></dt>
					<dd class="Model-2-field-3"><?php 
 print is_object($row["Model-2"])
	? $row["Model-2"]->field-3
	: $row["Model-2"]['field-3'] 
 ?>
</dd>
				</dl>
</li>
<?php 
 endforeach; 
 ?>
			
			
		</ul>
</div>
</body>
</html>
