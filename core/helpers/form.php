<?php
function form_start($method = "GET", $action = "", $name = "default" , $misc = "" )
{
	echo "<form name='".$name."' action='".$action."' method='".$method."' . ".$misc." >\n";
}

function form_end()
{
	echo "</form>";
}

function form_textbox($name,$value = "", $class="", $id="", $misc='')
{
	echo "<input type='text' name='". $name ."' id='".$id."' class='".$class."' value='" . cleanOutput($value) . "' />\n";
}

function form_textarea($name, $value = "", $class="", $id="", $misc='')
{
	echo "<textarea name='". $name ."' id='".$id."' class='".$class."'>" . cleanOutput($value) . "</textarea>\n";
}

function form_passbox($name,$value = "", $class="", $id="", $misc='')
{
	echo "<input type='password' name='". $name ."' id='".$id."' class='".$class."' value='" . cleanOutput($value) . "' />\n";
}

function form_checkbox($name,$value = "", $class="", $id="", $misc='')
{
	echo "<input type='checkbox' name='". $name ."' id='".$id."' class='".$class."' value='" . cleanOutput($value) . "' />\n";
}

function form_submit($name, $value = "", $class="", $id="", $misc='')
{
	echo "<input type='submit' name='". $name ."' id='".$id."' class='".$class."' value='" . cleanOutput($value) . "' />\n";
}


//validator messages for forms

function showSuccessMessage($status_message)
{
	$global = GlobalRegistry::getInstance();
	?>
		<div id='message-green'>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="green-left">
					<?php echo $status_message; ?>
				</td>
				<td class="green-right">
					<a class="close-green"><img src="<?php echo $global->siteUrl?>include/images/table/icon_close_green.gif"   alt="" /></a></td>
			</tr>
			</table>
		</div>
		<?php
}
?>