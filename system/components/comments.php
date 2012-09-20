<?php
/**
 * 
 * Enter description here ...
 * @property comment_model $comment_model
 * @property FormValidator $FormValidator
 * @author Goran Despotoski
 *
 */
class comments extends Controller 
{
	private $comment_type;
	private $id;
	private $controller;
	private $action;

	public function __construct()
	{
		parent::__construct();
		$this->global = GlobalRegistry::getInstance();
		$this->load_model("comment_model");
	}
	
	public function load($comment_type, $id,$controller, $action, $is_logged_in)
	{
		$this->comment_type = $comment_type;
		$this->id = $id;
		$this->controller = $controller;
		$this->action = $action;
		$this->load_component("FormValidator","object");
		
		$temp = "";
		if($this->_isPost() && $this->_post("comments_form_post", "string", "") != "" && $is_logged_in)
		{
			
			$this->FormValidator->addValidation("comment_text", "req", "Внесете текст");
			$comment_text = $this->_post("comment_text", "string", "");
			if($this->FormValidator->ValidateForm())
			{
				$this->comment_model->insert((int)$_SESSION["ses_user_id"], $comment_text, $comment_type, $id);
			}
		}
		$temp .= $this->showComments(); 
		return $temp;
	}
	
	public function showComments()
	{
		$comments = $this->comment_model->getComments($this->comment_type,$this->id);
		$text = '
		<div style="display:block;margin-top: 20px;">
			<h3 style="text-decoration:underline;color:grey">Коментари:</h3>';
		
		if($comments->rowCount() > 0)
		while($row = $comments->getRow())
		{
			$text.="<div class='comment-div'>";
			$text.= $row['user_name']."<br />";
			$text.= "<div class='comment-content'> ".strip_tags($row['content'])."</div>";
//			$text.= "<div class='comment-date'> ".$row['date_commented']."</div>";
			$text.= "</div>";
		}else 
			$text .="<div class='info'>Нема коментари во системот</div>";
		$text.='</div>';
		$text.=$this->showCommentForm();
		return nl2br($text);
	}
	
	public function showCommentForm()
	{
		
		if(isset($_SESSION['ses_user_type']))
		return $this->FormValidator->returnErrors() . '
		<form id="comment_form" name="comment_form" method="post" action="">
			
			<b>Искоментирај и ти</b><br />
			<textarea name="comment_text" style="height:80px;width:100%;"></textarea>
			<input type="hidden" name="comments_form_post" value="'.time().'" />
			<input name="submit" type="submit" value="Коментирај" />
		</form>
		'; 
		return '<div class="info">Логирајте се или регистрирајте се за да оставате коментари</div>';
	}
	public function leaveComment($user_id, $comment_text) 
	{
		$temp = "";
		if(trim($comment_text) == "")
		{
			$temp .="<div class='error'>Внесете коментар</div>";
			$temp .= $this->showComments();
		}
		
		if((int)$user_id != 0)
		{
			
			if($this->update($sql))
				redirect($this->global->siteUrl."/". $this->controller."/".$this->action."/".$this->id."");
		}
		return $temp;
	}
	
	//admin section functionalities
	public function loadAdmin($comment_type, $id,$controller, $action, $is_logged_in)
	{
		$this->comment_type = $comment_type;
		$this->id = $id;
		$this->controller = $controller;
		$this->action = $action;
		
		$temp = "";
		if($this->_isPost() && $this->_post("comments_form_post", "string", "") != "" && $is_logged_in)
		{
			$this->load_helper("FormValidator");
			$this->FormValidator->addValidation("comment_text", "req", "Внесете текст");
			$comment_text = $this->_post("comment_text", "string", "");
			if($this->FormValidator->ValidateForm())
			{
				$this->comment_model->insert((int)$_SESSION["ses_user_id"], $comment_text, $comment_type, $id);
			}
		}
		$temp .= $this->showAdminComments(); 
		return $temp;
	}
	
	public function showAdminComments()
	{
		$comments = $this->comment_model->getComments($this->comment_type,$this->id);
		$text = '
		<div style="display:block;margin-top: 0px;">
		';
		
		if($comments->rowCount() > 0)
		{
			$text .="<table>";
			$text .= "	<tr>";
			$text .= "		<th>Коментирал</th>";
			$text .= "		<th>Содржина на коментар</th>";
			$text .= "		<th>Време на постирање</th>";
			$text .= "		<th>Објавен?</th>";
			$text .= "	</tr>";
			while($row = $comments->getRow())
			{
				$text.="	<tr>\n";
				$text.= "		<td>". $row['user_name']."</td>\n";
				$text.= "		<td> ".nl2br(strip_tags($row['content']))."</td>\n";
				$text.= "		<td> ".$row['date_commented']."</td>\n";
				$text.= "		<td><input type='checkbox' value='Y' ".(($row['approved']== 'Y')?"checked='checked'":'')." name='comment[".$row["id"]."]' /></td>\n";
				$text.="	</tr>\n";
			}
			$text .="</table>";
		}
		else 
		{
			$text .="<div class='info'>Нема коментари во системот</div>";
		}
		$text.='</div>';
		return $text;
	}
}
?>