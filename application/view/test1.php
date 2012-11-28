The second view included!

<?php
echo "start of second view view<br /><br />";
while ($row = $res->getRow())
{
	var_dump($row);
}
echo "end of second view<br /><br />";
?>