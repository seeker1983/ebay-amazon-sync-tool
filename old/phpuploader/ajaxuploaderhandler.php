<?php require_once "include_phpuploader.php" ?>
<?php require_once "smart_resize_image.function.php" ?>
<?php

set_time_limit(3600);

$uploader=new PhpUploader();

$uploader->PreProcessRequest();



$mvcfile=$uploader->GetValidatingFile();

if($mvcfile->FileName=="thisisanotvalidfile")
{
	$uploader->WriteValidationError("My custom error : Invalid file name. ");
	exit(200);
}


if( $uploader->SaveDirectory )
{
	if(!$uploader->AllowedFileExtensions)
	{
		$uploader->WriteValidationError("When using SaveDirectory property, you must specify AllowedFileExtensions for security purpose.");
		exit(200);
	}

	$cwd=getcwd();
	chdir( dirname($uploader->_SourceFileName) );
	if( ! is_dir($uploader->SaveDirectory) )
	{
		$uploader->WriteValidationError("Invalid SaveDirectory ! not exists.");
		exit(200);
	}
	chdir( $uploader->SaveDirectory );
	$wd=getcwd();
	chdir($cwd);

	$targetfilepath=  "../uploads/" . $mvcfile->FileName;
	if( file_exists ($targetfilepath) )
		unlink($targetfilepath);

	$mvcfile->CopyTo( $targetfilepath );
    // After image copy we will resize it
    // $uploader->WriteValidationError(pathinfo($targetfilepath, PATHINFO_DIRNAME ).'/'.);
    
    // exit(200);
    $file = $targetfilepath;
    $extension = pathinfo($targetfilepath, PATHINFO_EXTENSION );
	
    $resizedFile = str_replace('.'.$extension, '_600.'.$extension, $targetfilepath);
	 smart_resize_image($file , null, 600 , 400 , false , $resizedFile , false , false ,100);
	 
	$resizedFile = str_replace('.'.$extension, '_700.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 700 , 400 , false , $resizedFile , false , false ,100 );

	    $resizedFile = str_replace('.'.$extension, '_800.'.$extension, $targetfilepath);
	   smart_resize_image($file , null, 800 , 200 , false , $resizedFile , false , false ,100);
	 
	$resizedFile = str_replace('.'.$extension, '_900.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 900 , 400 , false , $resizedFile , false , false ,100 );
	
     $resizedFile = str_replace('.'.$extension, '_1000.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 1000 , 400 , false , $resizedFile , false , false ,100 );
    
	$resizedFile = str_replace('.'.$extension, '_14.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 600 , 500 , false , $resizedFile , false , false ,100 );

	$resizedFile = str_replace('.'.$extension, '_65.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 600 , 600 , false , $resizedFile , false , false ,100 );

	$resizedFile = str_replace('.'.$extension, '_75.'.$extension, $targetfilepath);
    smart_resize_image($file , null, 700 , 500 , false , $resizedFile , false , false ,100 );

	}

$uploader->WriteValidationOK("");

?>