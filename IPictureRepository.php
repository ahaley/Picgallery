<?php

namespace Picgallery;

interface IPictureRepository
{
	public function imageExists($image);
}