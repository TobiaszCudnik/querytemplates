<form>
		<legend>Form example</legend>
		<fieldset><dl>
<dt><label>input[type=text]</label></dt>
				<dd>
					<input type="text" name="text-example" value="<?php  if (isset($data['text-example'])) print $data['text-example'];
else if (isset($data->{'text-example'})) print $data->{'text-example'};  ?>">
</dd>
				<dt><label>input[type=checkbox]</label></dt>
				<dd>
					<?php  if ((isset($data['checkbox-example']) && $data['checkbox-example'] == foo) 
	|| (isset($data->{'checkbox-example'}) && $data->{'checkbox-example'} == foo)) {  ?><input type="checkbox" name="checkbox-example" value="foo" checked><?php  }    else {  ?><input type="checkbox" name="checkbox-example" value="foo"><?php  }  ?>
</dd>
				<dt>input[type=radio]</dt>
				<dd>
					<label>[value=first]
						<?php  if ((isset($data['radio-example']) && $data['radio-example'] == first) 
	|| (isset($data->{'radio-example'}) && $data->{'radio-example'} == first)) {  ?><input type="radio" name="radio-example" value="first" checked><?php  }    else {  ?><input type="radio" name="radio-example" value="first"><?php  }  ?></label>
				</dd>
				<dd>
					<label>[value=second]
						<?php  if ((isset($data['radio-example']) && $data['radio-example'] == second) 
	|| (isset($data->{'radio-example'}) && $data->{'radio-example'} == second)) {  ?><input type="radio" name="radio-example" value="second" checked><?php  }    else {  ?><input type="radio" name="radio-example" value="second"><?php  }  ?></label>
				</dd>
				<dt><label>select</label></dt>
				<dd>
					<select name="select-example"><?php  if ((isset($data['select-example']) && $data['select-example'] == first) 
	|| (isset($data->{'select-example'}) && $data->{'select-example'} == first)) {  ?><option value="first" selected>First</option>
<?php  }    else {  ?><option value="first">First</option>
<?php  }    if ((isset($data['select-example']) && $data['select-example'] == second) 
	|| (isset($data->{'select-example'}) && $data->{'select-example'} == second)) {  ?><option value="second" selected>Second</option>
<?php  }    else {  ?><option value="second">Second</option>
<?php  }  ?></select>
</dd>
				<dt><label>textarea</label></dt>
				<dd>
					<textarea name="textarea-example"><?php  if (isset($data['textarea-example'])) print $data['textarea-example'];
else if (isset($data->{'textarea-example'})) print $data->{'textarea-example'};  ?></textarea>
</dd>
			</dl></fieldset>
</form>