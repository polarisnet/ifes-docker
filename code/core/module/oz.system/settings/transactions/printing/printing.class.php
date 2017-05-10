<?php
	class Printing{
		var $db;
		var $totalRow;
		
		function Printing($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getPrintingCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_image_upload', $condition);
			$sql = "SELECT `id`, `caption`, `path` FROM `sys_image_upload` WHERE 1=1 ".$condition." ORDER BY `item_order` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['caption'] = strtoupper($result_data['caption']);
				$temp['path'] = HTTP_MEDIA.'/printing-image'.$result_data['path'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listPrintingField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_image_upload` ";
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
		
		function getPrintingIdByCode($code){
			$output = "";
			$sql = "SELECT `id` FROM `sys_image_upload` WHERE `code`='".$code."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['id'];
			}
			return $output;
		}
		
		function getPrintingCodeById($id){
			$output = "";
			$sql = "SELECT `code` FROM `sys_image_upload` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['code'];
			}
			return $output;
		}
		
		function getPrintingSymbolById($id){
			$output = "";
			$sql = "SELECT `symbol` FROM `sys_image_upload` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['symbol'];
			}
			return $output;
		}
		
		function listPrinting($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_image_upload', $condition);
			$sql = "SELECT * FROM `sys_image_upload` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
                        
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function checkPrintingExist($id){
			$sql = "SELECT * FROM `sys_image_upload` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkPrintingCodeExist($code, $id = ""){
			$sql = "SELECT * FROM `sys_image_upload` WHERE LOWER(`code`)='".strtolower($code)."'";
			if($id != ""){
				$sql .= " AND `id` != '".$id."'";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getPrintingData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_image_upload` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		
		function deletePrinting($id){
			if($this->db->delete("sys_image_upload", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function savePrinting($data){
			if($this->db->insert("sys_image_upload", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updatePrinting($data){
			if($this->db->update("sys_image_upload", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
                
                function saveUploadImg($file){
			$output = array();
			$output['success'] = false;
			$output['ori_filename'] = $file["name"];
			$path = DIR_MEDIA."/printing-image/";
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
						chmod($path."/".$filename, 0777);
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
						$targetWidth = array(800, 400, 200, 150, 85);
						$targetPrefix = array('thw800_', 'thw400_', 'thw200_', 'thw150_', 'thw85_');
						foreach($targetWidth AS $key => $value){
							$newwidth = $value;
							$newheight = ($height/$width)*$newwidth;
							$tmp = imagecreatetruecolor($newwidth,$newheight);
							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
							$tmpfilename = $path."/".$targetPrefix[$key].$filename;
							imagejpeg($tmp,$tmpfilename,100);
							imagedestroy($tmp);
						}
						
						$targetHeight = array(600, 100);
						$targetPrefix = array('thh600_', 'thh100_');
						foreach($targetHeight AS $key => $value){
							$newheight = $value;
							$newwidth = ($width/$height)*$newheight;
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
                
                function checkImgExist($id){
			$sql = "SELECT * FROM `sys_image_upload` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
                
                function updateImg($data){
			if($this->db->update("sys_image_upload", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
                
                function getImgData($id){
			$output = array();
			$sql = "SELECT * FROM `items_img` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
                
                function deleteImgByItemId($id){
			if($this->db->delete("items_img", "`parent`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function deleteImg($id){
			if($this->removePhysicalImg($id)){
				if($this->db->delete("sys_image_upload", "`id`='".$id."'")){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
                
                function removePhysicalImg($id){
			$path = DIR_MEDIA."/printing-image/";
			$sql = "SELECT `path` FROM `sys_image_upload` WHERE 1=1 AND `id`='".$id."'";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$targetPrefix = array('thw800_', 'thw400_', 'thw200_', 'thw150_', 'thw85_', 'thh600_', 'thh100_');
				$result_data = $this->db->getRecord();
				unlink($path.$result_data['path']);
				foreach($targetPrefix AS $key => $value){
					$thumb = $value.substr($result_data['path'], 1);
					unlink($path.'/'.$thumb);
				}
			}
			return true;
		}
                
                function saveImg($data){
			if($this->db->insert("sys_image_upload", $data)){
				return true;
			}else{
				return false;
			}
		}
	}
?>