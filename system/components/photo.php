<?php
class photo extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load_model("gallery_model");
	}
	
	public function load($id)
	{
		$latest_photos = $this->gallery_model->getGalleriesForUser(0);
		$str = "
		<div class=\"gbox-title\"><span>Последни Галерии</span></div>
				<div class=\"gbox-content\">
					<ul>
					";
					
		while ($row = $latest_photos->getRow())
		{
			$str .= "<li>". linkReturn("photo","view_gallery",$row["name"],array($row["gallery_id"]))."</li>";
		}
		$str .="	</ul>
				</div>";
		
		return $str;
	}
}
?>