<?php

class anm22_wb_galleries{
	
	private $galleries;
	private $categories;
	
	function __construct(){
		$galleries = array();
		$categories = array();
	}
	
	function getGalleriesArray(){
		return $this->galleries;	
	}
	
	function addCategory($categoryName){
		$existing = false;
		if(!empty($this->categories)){
			foreach($this->categories as $key => $category){
				if($category == $categoryName){
					$existing = true;
					break;
				}
			}
		}
		if(!$existing){
			$this->categories[] = $categoryName;
		} else {
			/*Handle the case in which a category with the same name already exists*/
		}
	}
	
	function removeCategory($categoryName){
		if(!empty($this->categories)){
			foreach($this->categories as $key => $name){
				if($name == $categoryName){
					unset($this->categories[$key]);
					break;
				}
			}
		}
	}
	
	function addGallery($galleryToAdd){
		$this->galleries[] = $galleryToAdd;
	}
	
	function removeGallery($galleryTs){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryTs){
				unset($this->galleries[$k]);
				$this->galleries=array_values($this->galleries);
				break;
			}
		}
	}
	
	function editGalleryById($galleryTs,$newGalleryTitle,$newGalleryCategory,$newGalleryPublic){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryTs){
				$this->galleries[$k]->setTitle($newGalleryTitle);
				$this->galleries[$k]->setCategory($newGalleryCategory);
				$this->galleries[$k]->setPublicBool($newGalleryPublic);
				break;
			}
		}
	}
	
	function editImageById($galleryId,$imageId,$imageNewName,$imageNewTitle){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryId){
				for($j=0;$j<$this->galleries[$k]->getImagesCount();$j++){
					$arrayToWorkOn = $this->galleries[$k]->getImagesArray();
					if($arrayToWorkOn[$j]->getCreationDate() == $imageId){
						$arrayToWorkOn[$j]->setName($imageNewName);
						$arrayToWorkOn[$j]->setTitle($imageNewTitle);
					}
				}
			}
		}
	}
	
	function getGalleryById($galleryTs){
		$k;
		$galleryToBeReturned;
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryTs){
				$galleryToBeReturned = $this->galleries[$k];
				break;
			}
		}
		return $galleryToBeReturned;
	}
	
	function addImageToGallery($galleryId,$newImage){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryId){
				$this->galleries[$k]->addImage($newImage);
				break;
			}
		}
	}
	
	function removeImageFromGallery($galleryId,$imageId){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryId){
				$this->galleries[$k]->removeImage($imageId);
				break;
			}
		}
	}
	
	function moveImageToTheLeft($galleryId,$imageId){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryId){
				for($j=0;$j<$this->galleries[$k]->getImagesCount();$j++){
					$arrayToWorkOn = $this->galleries[$k]->getImagesArray();
					if($arrayToWorkOn[$j]->getCreationDate() == $imageId && $j!=0){
						$temp = $arrayToWorkOn[$j-1];
						$this->galleries[$k]->setImageAtIndex($arrayToWorkOn[$j],($j-1));
						$this->galleries[$k]->setImageAtIndex($temp,$j);
						$this->galleries[$k]->setImagesWholeArray(array_values($this->galleries[$k]->getImagesArray()));
						break;
					}
				}
			}
		}
	}
	
	function moveImageToTheRight($galleryId,$imageId){
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$galleryId){
				for($j=0;$j<$this->galleries[$k]->getImagesCount();$j++){
					$arrayToWorkOn = $this->galleries[$k]->getImagesArray();
					if($arrayToWorkOn[$j]->getCreationDate() == $imageId && $j!=($this->galleries[$k]->getImagesCount()-1)){
						$temp = $arrayToWorkOn[$j+1];
						$this->galleries[$k]->setImageAtIndex($arrayToWorkOn[$j],($j+1));
						$this->galleries[$k]->setImageAtIndex($temp,$j);
						$this->galleries[$k]->setImagesWholeArray(array_values($this->galleries[$k]->getImagesArray()));
						break;
					}
				}
			}
		}
	}
	
	function editImageContainingGallery($imageId,$oldGalleryId,$newGalleryId){
		$imageToSwitch;
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$oldGalleryId){
				$imageToSwitch = $this->galleries[$k]->getImageById($imageId);
				$this->galleries[$k]->removeImage($imageId);
				break;
			}
		}
		for($k=0;$k<$this->getGalleriesCount();$k++){
			if($this->galleries[$k]->getCreationDate()==$newGalleryId){
				$this->galleries[$k]->addImage($imageToSwitch);
				break;
			}
		}
	}
	
	function toStringDebug(){
		return 'Sono presenti '.$this->getGalleriesCount().' gallery.';
	}
	
	function getGalleriesCount(){
		return count($this->galleries);
	}
	
	function getCategories(){
		return $this->categories;
	}
	
	function toJsonReadyArray(){
		$associativeArray = array();
		for($i=0;$i<$this->getGalleriesCount();$i++){
			$galleryNew = array();
			$galleryNew["title"] = $this->galleries[$i]->getTitle();
			$galleryNew["category"] = $this->galleries[$i]->getCategory();
			$galleryNew["creationDate"] = $this->galleries[$i]->getCreationDate();
			$galleryNew["publicBool"] = $this->galleries[$i]->getPublicBool();
			$galleryNew["images"] = array();
			for($j=0;$j<$this->galleries[$i]->getImagesCount();$j++){
				$imageNew = array();
				$arrayToWorkOn = $this->galleries[$i]->getImagesArray();
				$imageNew["name"] = $arrayToWorkOn[$j]->getName();
				$imageNew["extension"] = $arrayToWorkOn[$j]->getExtension();
				$imageNew["title"] = $arrayToWorkOn[$j]->getTitle();
				$imageNew["creationDate"] = $arrayToWorkOn[$j]->getCreationDate();
				$galleryNew["images"][] = $imageNew;
			}
			$associativeArray[] = $galleryNew;
		}
		return $associativeArray;
	}
	
}

class anm22_wb_gallery{
	
	private $images;
	private $title;
	private $creationDate;
	private $category;
	private $publicBool;
	
	function __construct($galleryTitle,$galleryCategory,$galleryPublic,$galleryCreationDate){
		$this->images = array();
		$this->title = $galleryTitle;
		$this->creationDate = $galleryCreationDate;
		$this->category = $galleryCategory;
		$this->publicBool = $galleryPublic;
	}
	
	function getImageById($imageId){
		$imageToBeReturned = NULL;
		for($k=0;$k<$this->getImagesCount();$k++){
			if($this->images[$k]->getCreationDate()==$imageId){
				$imageToBeReturned = $this->images[$k];
				break;
			}
		}
		return $imageToBeReturned;
	}
	
	function getImagesArray(){
		return $this->images;
	}
	
	function setImagesWholeArray($imagesArray){
		$this->images = $imagesArray;
	}
	
	function addImage($imageToAdd){
		$this->images[] = $imageToAdd;
	}
	
	function removeImage($imageToRemoveId){
		foreach($this->images as $key=>$image){
			if($imageToRemoveId == $image->getCreationDate()){
				unset($this->images[$key]);
				$this->images=array_values($this->images);
				break;
			}
		}
	}
	
	function getTitle(){
		return $this->title;
	}
	
	function setTitle($newTitle){
		$this->title = $newTitle;
	}
	
	function getCreationDate(){
		return $this->creationDate;
	}
	
	function setCreationDate($newCreationDate){				/*For Debugging*/
		$this->creationDate = $newCreationDate;
	}
	
	function getCategory(){
		return $this->category;
	}
	
	function setCategory($newCategory){
		$this->category = $newCategory;
	}
	
	function getPublicBool(){
		return $this->publicBool;
	}
	
	function setPublicBool($newPublicBool){
		$this->publicBool = $newPublicBool;
	}
	
	function getImagesByDate(){
		$imagesOrderedByDate = array();
		$tmpArray = array();
		$tmpArray = $this->images;
		$tmpElement;
		$elementCount = count($tmpArray);
		for($i=0;$i<$elementCount;$i++){
			$tmpElement = $tmpArray[0];
			$tmpKey = 0;
			foreach($tmpArray as $key=>$element){
				if($tmpElement->getCreationDate() < $element->getCreationDate()){
					$tmpElement = $element;
					$tmpKey = $key;
				}
			}
			$imagesOrderedByDate[] = $tmpElement;
			unset($tmpArray[$key]);
		}
		return $imagesOrderedByDate;
	}
	
	function addImageAtIndex($newImage,$index){
		$firstPart = array_slice($this->images,0,$index);
		$secondPart = array_slice($this->images,$index);
		$this->images = $firstPart;
		$this->images[] = $newImage;
		$this->images = array_merge($this->images,$secondPart);
	}
	
	function setImageAtIndex($image,$index){
		$this->images[$index] = $image;
	}
	
	function getImagesCount(){
		return count($this->images);
	}
	
	function toStringDebug(){
		return 'Sono presenti '.$this->getImagesCount().' immagini nella gallery '.$this->getTitle().'.';
	}
	
}

class anm22_wb_img{
	
	private $name;
	private $extension;
	private $title;
	private $creationDate;
	private $publicBool;
	
	function __construct($imageName,$imageExtension,$imageTitle,$imageCreationTime){
		$this->name = $imageName;
		$this->extension = $imageExtension;
		$this->title = $imageTitle;
		$this->creationDate = $imageCreationTime;
	}
	
	function getName(){
		return $this->name;
	}
	
	function setName($newName){
		$this->name = $newName;
	}
	
	function getExtension(){
		return $this->extension;
	}
	
	function setExtension($newExtension){				/*Debugging only*/
		$this->extension = $newExtension;
	}
	
	function getTitle(){
		return $this->title;
	}
	
	function setTitle($newTitle){
		$this->title = $newTitle;
	}
	
	function getCreationDate(){
		return $this->creationDate;
	}
	
	function setCreationDate($newCreationDate){				/*For Debugging*/
		$this->creationDate = $newCreationDate;
	}
	
	function getPublicBool(){
		return $this->publicBool;
	}
	
	function setPublicBool($newPublicBool){
		$this->publicBool = $newPublicBool;
	}
	
	function getPermalink(){
		return $this->getCreationDate().'-'.str_replace(' ','-',$this->getName());
	}
	
}

?>