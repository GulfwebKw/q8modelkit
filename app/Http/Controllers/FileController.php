<?php

namespace App\Http\Controllers;

use Response;
use Image;

class FileController extends Controller
{

	public function show($main, $file)
	{
		//For Files and Imgs
		if (!empty($file)) {
			$path = base_path('public/uploads/' . $main . '/' . $file);

			$file_extension = strtolower(substr(strrchr($path, "."), 1));
			switch ($file_extension) {
				case "gif":
					$ctype = "image/gif";
					break;
				case "png":
					$ctype = "image/png";
					break;
				case "jpeg":
				case "jpg":
					$ctype = "image/jpeg";
					break;
				case "svg":
					$ctype = "image/svg+xml";
					break;
				default:
			}

			if (file_exists($path)) {
				header('Content-type: ' . $ctype);
				header("X-Sendfile: $path");
				return readfile($path);
			} else {

				$path = base_path('public/uploads/no-image.png');
				return readfile($path);
			}
		} else {
			$path = base_path('public/uploads/no-image.png');
			return readfile($path);
		}
	}

	// Show Thumbnail for Images
	public function showthumb($main, $thumb = '', $file)
	{
		if (!empty($file)) {

			if (!empty($thumb)) {
				$path = base_path('public/uploads/' . $main . '/' . $thumb . '/' . $file);
			} else {
				$path = base_path('public/uploads/' . $main . '/' . $file);
			}

			if (file_exists($path)) {
				return Image::make($path)->response();
			} else {
				$path = base_path('public/uploads/no-image.png');
				return Image::make($path)->response();
			}
		} else {
			$path = base_path('public/uploads/no-image.png');
			return Image::make($path)->response();
		}
	}

	//For Videos
	public function showvideo($file)
	{


		if (!empty($file)) {
			$path = base_path('public/uploads/slideshow/' . $file);

			if (file_exists($path)) {
				$response = Response::make($path, 200);
				$response->header('Content-Type', 'video/mp4');
				return $response;
			} else {
				$path = base_path('public/uploads/no-image.png');
				return Image::make($path)->response();
			}
		} else {
			$path = base_path('public/uploads/no-image.png');
			return Image::make($path)->response();
		}
	}
}
