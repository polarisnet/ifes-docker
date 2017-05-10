<?php
	class OZDoctor{
		var $db;
		var $totalRow;
		
		function OZDoctor($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function rebuildItemImage(){
			$path = DIR_MEDIA."/kth-pic";
			$fileList = scandir($path);
			unset($fileList[0]);
			unset($fileList[1]);
			require_once DIR_MODULE.'/item_management/items/item.class.php';
			$objItem = new Item($GLOBALS['myDB']);

			$allowedExts = array("jpg", "jpeg", "png");
			foreach($fileList AS $file){
				$tempFile = explode(".", $file);
				$extension = strtolower(end($tempFile));
				$itemData = array();
				if(in_array($extension, $allowedExts)){
					$sql = "SELECT id, uid, item_no_1 FROM items WHERE LOWER(item_no_1) = '".strtolower(trim($tempFile[0]))."'";
					$this->db->query($sql);
					if($this->db->nextRecord()){
						$itemData = $this->db->getRecord();
					}
					
					if(!empty($itemData)){
						$itemPath = DIR_MEDIA.'/item-image/'.$itemData['uid'];
						if(!file_exists($itemPath)){
							mkdir($itemPath, 0777);
						}
						
						$fileTargetPath = $itemPath.'/'.$file;
						$fileSourcePath = $path.'/'.$file;
						copy($fileSourcePath, $fileTargetPath);
						
						/** Refer to Item Class - Start **/
						if($extension == "jpg" || $extension == "jpeg" ){
							$src = imagecreatefromjpeg($fileTargetPath);
						}else if($extension=="png"){
							$src = imagecreatefrompng($fileTargetPath);
						}else{
							$src = imagecreatefromgif($fileTargetPath);
						}
						
						list($width,$height) = getimagesize($fileTargetPath);
						$targetWidth = array(800, 400, 200, 150, 85);
						$targetPrefix = array('thw800_', 'thw400_', 'thw200_', 'thw150_', 'thw85_');
						foreach($targetWidth AS $key => $value){
							$newwidth = $value;
							$newheight = ($height/$width)*$newwidth;
							$tmp = imagecreatetruecolor($newwidth,$newheight);
							imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
							$tmpfilename = $itemPath."/".$targetPrefix[$key].$file;
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
							$tmpfilename = $itemPath."/".$targetPrefix[$key].$file;
							imagejpeg($tmp,$tmpfilename,100);
							imagedestroy($tmp);
						}
						imagedestroy($src);
						/** Refer to Item Class - End **/
						
						$fileExist = false;
						$imageData = array();
						$sql = "SELECT `id` FROM items_img WHERE parent = '".$itemData['id']."' AND path = '/".$file."'";
						$this->db->query($sql);
						if($this->db->nextRecord()){
							$imageData = $this->db->getRecord();
							$fileExist = true;
						}
			
						if($fileExist){
							$imageData['modified_date'] = date("Y-m-d H:i:s");
							$objItem->updateImg($imageData);
							echo '<br> Modified ('.$itemData['item_no_1'].') '.$file;
						}else{
							$imageData['created_date'] = date("Y-m-d H:i:s");
							$imageData['parent'] = $itemData['id'];
							$imageData['path'] = "/".$file;
							$objItem->saveImg($imageData);
							echo '<br> Created ('.$itemData['item_no_1'].')'.$file;
						}
					}else{
						echo '<br> Cannot insert '.$file.' not exist';
					}
				}else{
					echo '<br> Invalid extension '.$file;
				}					
			}
			echo '<br>Operation done!';
		}
		
		function rebuildMediaFolder($mode = 'clean', $media = "item-image", $table = "items", $field = "uid"){
			if($mode == 'clean'){
				$path = DIR_MEDIA."/".$media;
				$backup = $path."_backup";
				$backupStatus = false;
				$backupFile = "";
				if(file_exists($path)){
					if($media == 'device-data' || $media == 'user-image'){
						if($media == 'device-data'){
							$backupFile = ".htaccess";
						}else{
							$backupFile = "default-avatar.png";
						}
						if(file_exists($path."/".$backupFile)){
							mkdir($backup, 0755);
							if(copy($path."/".$backupFile, $backup."/".$backupFile)){
								$backupStatus = true;
							}
						}
					}else{
						$backupStatus = true;
					}
					if($backupStatus){
						rrmdir($path);
					}else{
						echo 'Cannot create backup point. Doctor has been fired!';
						return;
					}
				}
				mkdir($path, 0755);
				
				$uidData = array();
				$updateData = array();
				$sql = "SELECT id, ".$field." FROM ".$table." ORDER BY id ASC";
				$this->db->query($sql);
				while($this->db->nextRecord()){
					$result = $this->db->getRecord();
					$resultUID = $result[$field];
					if($resultUID == '' || $resultUID == '0'){
						$resultUID = strtolower(generateSalt('30'));
						while(in_array($resultUID, $uidData)){
							$resultUID = strtolower(generateSalt('30'));
						}
						$tempData = array();
						$tempData['id'] = $result['id'];
						$tempData[$field] = $resultUID;
						array_push($updateData, $tempData);
					}
					mkdir($path."/".$resultUID, 0755);
					echo '<br>'.$resultUID;
					array_push($uidData, $resultUID);
				}
				
				foreach($updateData AS $newData){
					$sql = "UPDATE $table SET $field = '".$newData[$field]."', modified_date = '".date("Y-m-d H:i:s")."' WHERE id = '".$newData['id']."'";
					$this->db->query($sql);
				}
				
				if($media == 'device-data' || $media == 'user-image'){
					copy($backup."/".$backupFile, $path."/".$backupFile);
					rrmdir($backup);
				}
				echo '<br>Operation done!';
			}
		}
	}
?>