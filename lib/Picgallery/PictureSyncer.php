<?php

namespace Picgallery;

require_once 'ImageRepository.php';

class PictureSyncer
{
	private $repository;
	
	public function __construct(ImageRepository $repository)
	{
		$this->repository = $repository;
	}

	public function mapImageExistence($imageList)
	{
		foreach ($imageList as $image) {
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
	
		return $imageList;
	}
}
