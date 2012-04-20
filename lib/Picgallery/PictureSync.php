<?php

namespace Picgallery;

require_once 'IPictureRepository.php';

class PictureSync
{
	private $repository;
	
	public function __construct(IPictureRepository $repository)
	{
		$this->repository = $repository;
	}

	public function updateImageList($image_list)
	{
		foreach ($image_list as $image) {
			$photoId = $this->repository->imageExists(
                $image->file
            );
			if (!$photoId) {
				$image->inRepository = false;
			}
			else {
				$image->inRepository = true;
				$image->photoId = $photoId;
			}
		}
	
		return $image_list;
	}
}
