<?php
	class Banner{
		var $db;
		var $totalRow;
		
		function Banner($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function listBannerField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `banner` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listBanner($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'banner', $condition);
			$sql = "SELECT * FROM `banner` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){
					$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); 
					$result['raw'] = encryption($result['id'], $salt, true);					
				} else {
					$result['enc_id'] = '';
				}				
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function getBannerPathById($id){
			$output = "";
			$sql = "SELECT `path` FROM `banner` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['path'];
			}
			return $output;
		}	
				
		function saveUploadImg($file){
			$output = array();
			$output['success'] = false;
			$output['ori_filename'] = $file["name"];
			$path = DIR_MEDIA."/site-image/banner";
			$allowedExts = array("jpg", "jpeg", "png");
			$tempArray = explode(".", $file["name"]);	
			$extension = strtolower(end($tempArray));
		
			if ((($file["type"] == "image/jpeg") || ($file["type"] == "image/png") || ($file["type"] == "image/pjpeg")) && in_array($extension, $allowedExts)){
				if(($file["size"] < 2000000)){
					if ($file["error"] > 0){
						$output['message'] = "File Corrupted. Return Code: " . $file["error"] . "<br>";
					}else{
						$filename = strtolower($file["name"]);
						while(file_exists($path."/".$filename)){
							$filename = strtolower(generateSalt('6')).'.'.$extension;
						}
						move_uploaded_file($file["tmp_name"], $path."/".$filename);
						$output['success'] = true;
						$output['filename'] = $filename;
						$output['message'] = $output['ori_filename'].' uploaded successfully';
						
						/*Image resize with sampling*/
						$uploadedfile = $path."/".$filename;
						if($extension=="jpg" || $extension=="jpeg" ){
							$src = imagecreatefromjpeg($uploadedfile);
						}else if($extension=="png"){
							$src = imagecreatefrompng($uploadedfile);
						}else{
							$src = imagecreatefromgif($uploadedfile);
						}
			
						list($width,$height) = getimagesize($uploadedfile);
						$targetWidth = array(150);
						$targetPrefix = array('thw150_');
						foreach($targetWidth AS $key => $value){
							$newwidth = $value;
							$newheight = ($height/$width)*$newwidth;
							$tmp = imagecreatetruecolor($newwidth,$newheight);
							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
							$tmpfilename = $path."/".$targetPrefix[$key].$filename;
							imagejpeg($tmp,$tmpfilename,100);
							imagedestroy($tmp);
						}
						
						imagedestroy($src);
					}
				}else{
					$output['message'] = "File size too large. Max file size is 2MB.";
				}
			}else{
				$output['message'] = "Invalid file extension. Allowed extension (*.jpg, *.png).Please upload another file type.";
			}
			return $output;
		}
		
		function removePhysicalImg($id){
			$path = DIR_MEDIA."/site-image/banner";
			$sql = "SELECT `path` FROM `banner` WHERE 1=1 AND `id`='".$id."'";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$targetPrefix = array('thw150_');
				$result_data = $this->db->getRecord();
				unlink($path.'/'.$result_data['path']);
				foreach($targetPrefix AS $key => $value){
					$thumb = $value.$result_data['path'];
					unlink($path.'/'.$thumb);
				}
			}
			return true;
		}
		
		function checkBannerExist($id){
			$sql = "SELECT * FROM `banner` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getBannerData($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `banner` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}			
				
		function deleteBanner($id){
			if($this->removePhysicalImg($id)){
				if($this->db->delete("banner", "`id`='".$id."'")){				
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function saveBanner($data){
			if($this->db->insert("banner", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateBanner($data){
			if($this->db->update("banner", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}			
		
	}
?>