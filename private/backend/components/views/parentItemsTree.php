<?php
/* @var array $links */

$i = 0;
foreach ($links as $link) {
	++$i;
	echo '<ul><li>' . $link . '</li>';
}
echo str_repeat('</ul>', $i);

