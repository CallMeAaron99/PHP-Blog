<?php
	include_once('db.php');

	class Tag {

		private $db;
		
		public function __construct($db){
			$this->db = $db;
		}

		public function findAllTag(){
			$sql = "SELECT * FROM tag";
			$result = mysqli_query($this->db,$sql);
			return $result;
		}

		public function findTagByPostId($postId){
			$data = [];
			//$sql = "SELECT * FROM tags";
			$sql = "SELECT tag.*
					FROM tag
					INNER JOIN post_tag
					ON post_tag.post_id = post.id
					WHERE post_tag.post_id=$postId";
			$result = mysqli_query($this->db,$sql);
			//return $result;
			//$result = mysqli_query($this->db,$sql);
			foreach($result as $res){
				// literate tags
				array_push($data, $res['name']);
			}
			return $data;
		}
	}
?>