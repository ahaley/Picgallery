<?php

namespace Picgallery;

class FileHelper
{
	public function isImageType($mime_type)
	{
		return false !== strpos($mime_type, 'image');
	}

	public function getFileExtension($path)
	{
		preg_match('/\.(\w+)$/', strtolower($path), $matches);
		if (count($matches) < 2)
			return null;
		return $matches[1];
	}
}
