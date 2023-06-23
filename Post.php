<?php
    include('db.php');
    include('global.php');

    class Post {

        private $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function insertPost($title, $content, $userId) {
            $date = date('Y-m-d H:i:s');
            $image = empty($_FILES['image']['name']) ? '' : $this->uploadImage();
            if($image != '' && !$image){
                // invalid image type
                return false;
            }
            $stmt = $this->db->prepare("INSERT INTO post(title, content, image, created_at, updated_at, user_id) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $content, $image, $date, $date, $userId);
            $result = $stmt->execute();
            if($result && !empty($_POST['tags'])) {
                // insert post success
                // get the ID of the inserted post
                $postId = mysqli_insert_id($this->db);
                $this->insertTag($postId, $_POST['tags']);
            }
            return $result;
        }

      public function findPostById($postId){
        $sql = "SELECT post.*, user.username, GROUP_CONCAT(tag.name SEPARATOR ' ') AS tags
                  FROM post
                  JOIN user ON post.user_id = user.id
                  LEFT JOIN post_tag ON post.id = post_tag.post_id
                  LEFT JOIN tag ON post_tag.tag_id = tag.id
                  WHERE post.id = $postId";

        $result = mysqli_query($this->db, $sql);
        return $result;
      }

      public function findAllPost() {
          $sql = "SELECT post.*, user.username, GROUP_CONCAT(tag.name SEPARATOR ' ') AS tags
                  FROM post
                  JOIN user ON post.user_id = user.id
                  LEFT JOIN post_tag ON post.id = post_tag.post_id
                  LEFT JOIN tag ON post_tag.tag_id = tag.id";
      
          $where = [];
          if(isset($_GET['tag'])) {
              // tag selected when searching post
              $tagId = $_GET['tag'];
              $where[] = "tag.id = $tagId";
          }
          if(isset($_GET['title']) && !empty($_GET['title'])) {
              // title or content search
              $title = $_GET['title'];
              $title = "%$title%";
              $where[] = "(post.title LIKE ? OR post.content LIKE ?)";
          }
          if(!empty($where)) {
              $sql .= " WHERE " . implode(" AND ", $where);
          }
      
          $sql .= " GROUP BY post.id ORDER BY post.id DESC";
      
          // pagination
          $pageSize = $GLOBALS['pageSize'];
          $page = 1;
          if(isset($_GET['page'])) {
              $page = $_GET['page'];
          }
          $offset = $pageSize * ($page - 1);
          $sql .= " LIMIT $pageSize OFFSET $offset";
          $stmt = $this->db->prepare($sql);
          if(isset($title)){ $stmt->bind_param("ss", $title, $title); }
            
            $stmt->execute();
            return $stmt->get_result();
      }

      public function getPostCount() {
          $sql = "SELECT COUNT(DISTINCT post.id) as total FROM post";
          if(isset($_GET['tag'])) {
              // tag selected when searching post
              $tagId = $_GET['tag'];
              $sql .= " JOIN post_tag ON post.id = post_tag.post_id WHERE post_tag.tag_id = $tagId";
          }
          if(isset($_GET['title']) && !empty($_GET['title'])) {
              // title or content search
              $title = $_GET['title'];
              $title = "%$title%";
              if(isset($_GET['tag'])) {
                  // tag already added to WHERE clause
                  $sql .= " AND (post.title LIKE ? OR post.content LIKE ?)";
              } else {
                  // no tag in WHERE clause
                  $sql .= " WHERE (post.title LIKE ? OR post.content LIKE ?)";
              }
          }
          $stmt = $this->db->prepare($sql);
          if(isset($title)){ $stmt->bind_param("ss", $title, $title); }
          $stmt->execute();
          return mysqli_fetch_assoc($stmt->get_result())['total'];
      }

        public function updatePost($postId, $title, $content){
            $date = date('Y-m-d H:i:s');
            if(!empty($_FILES['image']['name'])){
                // has image
                $image = $this->uploadImage();
                if(!$image){
                    // invalid image type
                    return false;
                }

                $oldImage = $this->findImageByPostId($postId);
                if($oldImage != null && $oldImage != ''){
                    // delete old image
                    $this->deleteImage($oldImage);
                }

                $stmt = $this->db->prepare("UPDATE post SET title = ?, content = ?, image = ?, updated_at = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $title, $content, $image, $date, $postId);
            }else{
                // no image
                $stmt = $this->db->prepare("UPDATE post SET title = ?, content = ?, updated_at = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $content, $date, $postId);
            }
            $result = $stmt->execute();

            if($result && isset($_POST['tags'])) {
                $this->insertTag($postId, $_POST['tags']);
            }
            return $result;
        }

        public function deletePostById($postId){
            $image = $this->findImageByPostId($postId);
            if($image != null && $image != ''){
                // delete image
                $this->deleteImage($image);
            }
            $this->updatePostTag($this->deletePostTagByPostId($postId));
            return mysqli_query($this->db, "DELETE FROM post WHERE id = $postId");
        }

        private function uploadImage(){
            $imagename= $_FILES['image']['name'];
            $imagetmp = $_FILES['image']['tmp_name'];
            
            $allowed = array('jpeg', 'png', 'jpg');
    
            $ext = pathinfo($imagename, PATHINFO_EXTENSION);
            if(in_array($ext, $allowed)){
                // generate a unique filename
                $uniqueName = uniqid().'.'.$ext;
                move_uploaded_file($imagetmp, "images/".$uniqueName);
            }else{
                // invalid image format
                return false;
            }
            return $uniqueName;
        }

        private function deleteImage($imageName){
            $imagePath = "images/".$imageName;
            if(file_exists($imagePath)){
                // image exists
                unlink($imagePath);
            }
        }

        private function deletePostTagByPostId($postId){
            $deletedTagIds = array();
            $result = mysqli_query($this->db, "SELECT tag_id FROM post_tag WHERE post_id = $postId");
            if (mysqli_num_rows($result) > 0) {
                // tags related to the post exist
                while($row = mysqli_fetch_assoc($result)) {
                    $deletedTagIds[] = $row["tag_id"];
                }
            }
            mysqli_query($this->db, "DELETE FROM post_tag WHERE post_id = $postId");
            return $deletedTagIds;
        }

        private function updatePostTag($tagIds){
            foreach($tagIds as $tagId){
                $result = mysqli_query($this->db, "SELECT tag_id FROM post_tag WHERE tag_id = $tagId");
                if (mysqli_num_rows($result) == 0) {
                    // the tag doesn't related to any post, delete it
                    mysqli_query($this->db, "DELETE FROM tag WHERE id = $tagId");
                }
            }
        }

        private function findImageByPostId($postId){
            $result = mysqli_query($this->db, "SELECT image FROM post WHERE id = $postId");
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                return $row['image'];
            }else{
                return null;
            }
        }

        private function insertTag($postId, $tags) {
			// delete all post tag relationship related to the post id
			$deletedTagIds = $this->deletePostTagByPostId($postId);

			// convert tags to lowercase and split by space
			$tags = array_unique(explode(' ', strtolower($tags)));
			foreach($tags as $tag) {
				// check if the tag exists in the database
                $stmt = $this->db->prepare("SELECT id FROM tag WHERE name = ?");
                $stmt->bind_param("s", $tag);
                $stmt->execute();
                $result = $stmt->get_result();
				if(mysqli_num_rows($result) > 0) {
					// tag exists, get its ID
					$row = mysqli_fetch_assoc($result);
					$tagId = $row['id'];
				} else {
					// tag does not exist, create it
					$stmt = $this->db->prepare("INSERT INTO tag(name) VALUES(?)");
                    $stmt->bind_param("s", $tag);
                    $stmt->execute();
					// get the ID of the inserted tag
					$tagId = mysqli_insert_id($this->db);
				}
                $stmt = $this->db->prepare("INSERT INTO post_tag(post_id, tag_id) VALUES(?, ?)");
                $stmt->bind_param("ii", $postId, $tagId);
                $stmt->execute();
			}
            
            $this->updatePostTag($deletedTagIds);
		}
    }

?>
