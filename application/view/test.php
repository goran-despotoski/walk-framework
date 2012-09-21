<br /><br />
<?php
echo "start of view<br /><br />";
while ($row = $res->getRow())
{
	var_dump($row);
}
echo "end of view<br /><br />";
?>