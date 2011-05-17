<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Example: advanced-1</title>
</head>
<body>
	<ul>
<?php  foreach($posts as $r):  ?><li>
			<h2 class="title"><?php  if (isset($r["Post"]['title'])) print $r["Post"]['title'];
else if (isset($r["Post"]->{'title'})) print $r["Post"]->{'title'};  ?>
</h2>
			<p class="body"><?php  if (isset($r["Post"]['body'])) print $r["Post"]['body'];
else if (isset($r["Post"]->{'body'})) print $r["Post"]->{'body'};  ?></p>
			<h3>Comments</h3>
			<ul class="comments">
<?php  foreach($r["Comment"] as $comment):  ?><li>
					<strong class="author"><?php  if (isset($comment['author'])) print $comment['author'];
else if (isset($comment->{'author'})) print $comment->{'author'};  ?></strong>: <span class="body"><?php  if (isset($comment['body'])) print $comment['body'];
else if (isset($comment->{'body'})) print $comment->{'body'};  ?></span>
				</li>
<?php  endforeach;  ?>
			</ul>
<div class="tags">
<?php  $tagCount = count($r["Tag"]);  ?><strong>Tags:</strong> <?php  foreach($r["Tag"] as $k => $tag):  ?><a href="<?php  print "tag/{$tag["id"]}"  ?>"><?php  print $tag["tag"];  ?></a><?php  if ($k+1 < $tagCount) print ", ";    endforeach;  ?>
</div>
		</li>
<?php  endforeach;  ?>
		
		
	</ul>
</body>
</html>
