<br /><br />
The first view included
<br /><br />
<?php
echo "start of first view<br /><br />";
echo "just create a database and a table called tests to test the model functionality (if it gives back an error):<br />";
while ($row = $res->getRow())
{
	var_dump($row);
}
echo "end of first view<br /><br />";
?>