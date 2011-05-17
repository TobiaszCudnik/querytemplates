<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Example: advanced-2</title>
</head>
<body>
	<ul>
<?php  if (isset($posts) && (is_array($posts) || is_object($posts))) { foreach($posts as $postNum => $r):  ?><li class='<?php  if (! $postNum) print "first"  ?>'>
			<h2 class="title"><?php  if (isset($r["Post"]['title'])) print $r["Post"]['title'];
else if (isset($r["Post"]->{'title'})) print $r["Post"]->{'title'};  ?>
</h2>
			<p class="body"><?php  if (isset($r["Post"]['body'])) print $r["Post"]['body'];
else if (isset($r["Post"]->{'body'})) print $r["Post"]->{'body'};  ?></p>
		
	<?php  if (isset($r["Comment"]) && $r["Comment"]) {  ?><h3>Comments</h3>
	<ul class="comments">
<?php  if (isset($r["Comment"]) && (is_array($r["Comment"]) || is_object($r["Comment"]))) { foreach($r["Comment"] as $comment):  ?><li>
			<strong class="author"><?php  if (isset($comment['author'])) print $comment['author'];
else if (isset($comment->{'author'})) print $comment->{'author'};  ?></strong>: <span class="body"><?php  if (isset($comment['body'])) print $comment['body'];
else if (isset($comment->{'body'})) print $comment->{'body'};  ?></span>
		</li>
<?php  endforeach; }  ?>
	</ul>
<?php  }    if (isset($r["Tag"]) && $r["Tag"]) {  ?><div class="tags">
<?php  $tagCount = count($r["Tag"]);  ?><strong>Tags:</strong> <?php  if (isset($r["Tag"]) && (is_array($r["Tag"]) || is_object($r["Tag"]))) { foreach($r["Tag"] as $k => $tag):  ?><a href="<?php  print "tag/{$tag["id"]}"  ?>"><?php  print $tag["tag"];  ?></a><?php  if ($k+1 < $tagCount) print ", ";    endforeach; }  ?>
</div>
<?php  }  ?>
</li>
<?php  endforeach; }  ?>
		
		
	</ul>
</body>
</html>
