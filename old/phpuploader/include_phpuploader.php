<?php

$PhpUploader_FSEncoding="ISO-8859-1//TRANSLIT";

//$PhpUploader_FSEncoding="utf-8";

$PhpUploader_InternalEncoding="utf-8";

error_reporting(E_ALL ^ E_NOTICE);

if(@$_GET["ContextValue"])
{
	if(!@$_SESSION)
	{
		session_id(@$_GET["ContextValue"]);
	}
	else
	{
		echo("Session has started already, unable to set the session id! Please include uploader before session_start().");
		exit(200);
	}
}

function PhpUploader_GetNamespace()
{
	return "CuteWebUI";
}

$phpuploader_logstart=false;
function PhpUploader_Log($message)
{
	$logfile="log.txt";
	
	return;
	
	//change the $logfile and comment the 'return;' for log
	
	$h=PhpUploader_FileOpen(__FILE__,__LINE__,$logfile,"a");
	
	global $phpuploader_logstart;
	if( ! $phpuploader_logstart )
	{
		PhpUploader_FileWrite(__FILE__,__LINE__,$h,"\r\n");
	}
	$phpuploader_logstart=true;
	
	PhpUploader_FileWrite(__FILE__,__LINE__,$h,$message);
	PhpUploader_FileWrite(__FILE__,__LINE__,$h,"\r\n");
	PhpUploader_FileClose(__FILE__,__LINE__,$h);
}



function PhpUploader_Unescape($str)
{ 
	global $PhpUploader_InternalEncoding;
	
    $str = rawurldecode($str); 
    //throw(new Exception( $str ));
    preg_match_all("/(%u[0-9A-Fa-f]{4}|%|[^%]+)/",$str,$r); 
    $ar = $r[0]; 
    foreach($ar as $k=>$v)
    {
        if(strlen($v) == 6 && substr($v,0,2) == "%u")
        {
			if(substr($v,0,4)=="%u00")
			{
				$ar[$k] = iconv("ISO-8859-1",$PhpUploader_InternalEncoding,pack("H2",substr($v,-2)));
			}
            else
            {
				$ar[$k] = iconv("UCS-2",$PhpUploader_InternalEncoding,pack("H4",substr($v,-4)));
            }
        }
    }
    //throw(new Exception( join("",$ar) ));
    return join("",$ar); 
} 

function PhpUploader_GetQSD($name)
{
    $val=@$_GET[$name];
    if(!$val)
        return null;
    $val=str_replace("\\'","'",$val);
    return PhpUploader_Unescape($val);
}

function PhpUploader_GetFileName($file)
{
	$str=PhpUploader_GetQSD("_VFN");
	if($str)
	{
		if(substr($str,0,1)==".")
			$str="uploadedfile"+$str;
		return $str;
	}
	return $file["name"];
}

function PhpUploader_GetBaseName($path)
{
	if(strpos($path,"\0"))throw (new Exception("Invalid path !!"));
	$path=str_replace("\\","/",$path);
	$p=strrpos($path,"/");
	if($p)
	{
		$path=substr($path,$p+1);
	}
	return $path;
}



function PhpUploader_MoveUploadedFile($_file,$_line,$src,$dst)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	if(!PhpUploader_FileExists($src))
		throw(new Exception("File not exists : $src , at $_file line $_line"));
		
	$er=error_reporting(0);
	$re=move_uploaded_file(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$src),iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$dst));
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to move $src to $dst' , at $_file line $_line"));
	}
	return $re;
}

function PhpUploader_FileExists($path)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	return file_exists(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$path));
}
function PhpUploader_GetFiles($_file,$_line,$pattern)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	$er=error_reporting(0);
	$re=glob(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$pattern));
	error_reporting($er);
	if(!$re)
		return array();
	$l=count($re);
	for($i=0;$i<$l;$i++)
	{
		$re[$i]=iconv($PhpUploader_FSEncoding,$PhpUploader_InternalEncoding,$re[$i]);
	}
	return $re;
}
function PhpUploader_MakeDir($_file,$_line,$dir,$flag)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	$dir=iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$dir);
	$er=error_reporting(0);
	$re=mkdir($dir,$flag);
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to make dir '$dir' , at $_file line $_line"));
	}
	return $re;
}
function PhpUploader_Copy($_file,$_line,$src,$dst)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	$er=error_reporting(0);
	$re=copy(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$src),iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$dst));
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to copy $src to $dst' , at $_file line $_line"));
	}
	return $re;
}
function PhpUploader_Move($_file,$_line,$src,$dst)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	PhpUploader_Log("Move From $src");
	PhpUploader_Log("Move To $dst");
	$er=error_reporting(0);
	$re=rename(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$src),iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$dst));
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to move $src to $dst' , at $_file line $_line"));
	}
	return $re;
}
function PhpUploader_FileTime($_file,$_line,$file)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	return filemtime(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$file));
}
function PhpUploader_Delete($_file,$_line,$file)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	$er=error_reporting(0);
	$re=unlink(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$file));
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to delete $file' , at $_file line $_line"));
	}
	return $re;
}
function PhpUploader_FileOpen($_file,$_line,$filepath,$flag)
{
	global $PhpUploader_FSEncoding;
	global $PhpUploader_InternalEncoding;
	
	$er=error_reporting(0);
	$re=fopen(iconv($PhpUploader_InternalEncoding,$PhpUploader_FSEncoding,$filepath),$flag);
	error_reporting($er);
	if($re===false)
	{
		$le=error_get_last();
		throw(new Exception($le["message"] . " , failed to open $filepath' , at $_file line $_line"));
	}
	return $re;
}
function PhpUploader_FileRead($_file,$_line,$handle,$len)
{
	return fread($handle,$len);
}
function PhpUploader_FileWrite($_file,$_line,$handle,$data)
{
	return fwrite($handle,$data);
}
function PhpUploader_FileClose($_file,$_line,$handle)
{
	return fclose($handle);
}




function PhpUploader_JSEncode($str)
{
	$str=str_replace("\\","\\\\",$str);
	$str=str_replace("'","\\x27",$str);
	$str=str_replace("\"","\\\"",$str);
	$str=str_replace("\r","\\\r",$str);
	$str=str_replace("\n","\\\n",$str);
	return $str;
}
function PhpUploader_GetGuid($str)
{
	if(!preg_match("/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/i",$str))
		throw(new Exception("Invalid Guid : " . $str));
	return $str;
}
function PhpUploader_CreateGuid()
{
	//this is windows only?
	//return PhpUploader_GetGuid(substr(com_create_guid(),1,36));
	return preg_replace_callback("/X/",create_function("",'return substr("0123456789ABCDEF",rand(0,15),1);'),"XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX");
}

function PhpUploader_ParseByteSetting($val) { 
	$val = trim($val); 
	$last = strtolower($val[strlen($val)-1]); 
	switch($last) { 
		// The 'G' modifier is available since PHP 5.1.0 
		case 'g': 
		$val *= 1024; 
		case 'm': 
			$val *= 1024; 
		case 'k': 
			$val *= 1024; 
	} 

	return $val; 
}

function PhpUploader_GetSystemTempFolder()
{
	$str=ini_get('upload_tmp_dir');
	if($str==null||strlen($str)==0)
	{
		$str="/tmp";
		if(!is_dir($str))
		{
			$str=dirname(__FILE__);
		}
	}
	else
	{
		if(!is_dir($str))
		{
			$result= PhpUploader_MakeDir(__FILE__,__LINE__,$str,0777);
			if(!$result)
			{
				throw(new Exception("The folder $str does not exist.  Please check the permission or specify a temp folder using TempDirectory property."));
			}
		}
	}
	
	if (substr($str,strlen($str)-(1))!="/")
		$str=$str."/";
	$str=$str."uploadertemp";
	
	if (!is_dir($str))
	{
		$result= PhpUploader_MakeDir(__FILE__,__LINE__,$str,0777);
		if(!$result)
		{
			throw(new Exception("Unable to create temp folder: $str  Please check the permission or specify a temp folder using TempDirectory property."));
		}
	}
	
	return $str;
}

class PhpUploader
{
	/****************************************************************\
		PROPERTIES
	\****************************************************************/
	
	/// Gets or sets the maximum allowed size of the file, in KB.
	public $MaxSizeKB=-1;
	
	///Set to "Http" or "Partial"
	public $FlashUploadMode;
	
	///server ability , this value is get from upload_max_filesize
	///when FlashUploadMode=="Http" , limit the file size to this value
	public $MaxHttpSizeKB=-1;
	
	///when FlashUploadMode=="Partial" , limit the file size to this value
	public $MaxPartialSizeKB=102400;
	
	/// Gets or sets the allowed file extensions for uploading. 
	/// <value>A comma-separated list of allowed extensions</value>
	public $AllowedFileExtensions="";
	
	/// <summary>
	/// Gets or sets the current file name filter string, which determines the choices that appear in the file browser dialog box. 
	/// Please note that only ValidateOption.AllowedFileExtensions property determine the allowed file extensions for uploading.
	/// </summary>
	/// <remarks>
	/// <p>For each filtering option, the filter string contains a description of the filter, followed by the vertical bar (|) and the filter pattern. The strings for different filtering options are separated by the vertical bar. <br />
	/// </p>
	/// <p>The following is an example of a filter string:<br />
	/// </p>
	/// <p>Text files (*.txt)|*.txt|All files (*.*)|*.* <br />
	/// </p>
	/// <p>You can add several filter patterns to a filter by separating the file types with semicolons, for example: <br />
	/// </p>
	/// <p>Image Files(*.BMP;*.JPG;*.GIF)|*.BMP;*.JPG;*.GIF|All files (*.*)|*.* </p>
	/// </remarks>
	public $DialogFilter;
	
	public $UploadCursor="Auto";
	
	/// Gets or sets the insert text.
	public $InsertText="Upload files";
	
	/// It allows the developers specify a server control to use as the insert button of ajax uploader.
	public $InsertButtonID;
	
	/// It allows the developers specify a server control to use as the cancel button of ajax uploader.
	public $CancelButtonID;
	
	/// It allows the developers specify a server control to show the progress text of ajax uploader.
	public $ProgressTextID;
	
	/// It allows the developers specify a server control to use as the progress control of ajax uploader.
	public $ProgressCtrlID;
	
	/// Gets or sets a value indicating whether the progress bar is displayed.
	public $ShowProgressBar=true;
  
	/// Gets or sets a value indicating whether the progress information is displayed when uploading.
	public $ShowProgressInfo=true;
	
	/// The global type of upload method to use.
	/// Auto|IFrame|Flash|Silverlight
	public $UploadType="Auto";
	
	/// Gets or sets a boolean indicating whether Uploader automatically starts uploading as soon as a user selects a file.
	public $ManualStartUpload=false;
	
	/// Gets or sets a boolean indicating whether Uploader should render a browse button for FireFox/Opera in Iframe Mode.
	public $ShowFrameBrowseButton=false;
	
	/// If true, the users are able to select multiple files in the file browser dialog then upload them at once. 
	public $MultipleFilesUpload=false;
	
	/// Gets or sets the maximum number of files that can be uploaded.
	public $MaxFilesLimit=-1;
	
	///Specifies the number of files when the CancelAll button appears
	public $NumFilesShowCancelAll=2;
	
	/// set a special page for Flash - Integrated Windows Authentication 
	public $FlashUploadPage;
	
	
	/// JavaScript code that be executed when the browse button be clicked.
	public $ButtonOnClickScript;
	
	/// Gets or sets the progress text template.
	/// %P% = percent,%T% = seconds remain,%F% = filename. %SEND% = uploaded size , %SIZE% = file size , %KBPS% means KB/s , %KBPS% = B/s 
	/// For example : %F%.. %P% %SEND%/%SIZE% , %KBPS% , %T% seconds left.
	public $ProgressTextTemplate;
		
	/// Gets or sets the "Cancel all Uploads" message.
	public $CancelAllMsg="Cancel all Uploads";
	
	/// Gets or sets the "Cancel upload" message.
	public $CancelUploadMsg="Cancel upload";
	
	/// Gets or sets the "upload is processing" message.
	public $UploadProcessingMsg;
	
	/// Gets or sets the "File too large" message.
	public $FileTooLargeMsg;
	
	/// Gets or sets the "maximum number of files" message.
	public $MaxFilesLimitMsg;
	
	/// Gets or sets the message for windows file browse dialog 32K file name length limitation.
	public $WindowsDialogLimitMsg;
	
	/// Gets or sets the "file type not support" message.
	public $FileTypeNotSupportMsg;
	
	/// Gets or sets the "Uploading.." message.
	public $UploadingMsg="Uploading..";
	
	/// Gets or sets the FlashWarning Image.
	public $FlashWarningImage;
	
	/// The width of the Progress Panel. Default is 360.
	public $ProgressPanelWidth=360;
	
	/// The height of the Progress bar. Default is 20.
	public $ProgressBarHeight=20;
	
	public $ProgressInfoStyle="padding-left:3px;font:normal 12px Tahoma;";
	
	/// Continuous|Blocks
	public $ProgressBarStyle="Continuous";
	
	/// Gets or sets the background image for the ProgressBar control.
	public $ProgressBarBackgroundImage;

	/// Specifies the border style for the ProgressBar control.
	public $ProgressBarBorderStyle="border-style:solid;border-width:1px;border-style:#444444;";
	
	/// Gets or sets a progress fill picture for the ProgressBar control.
	public $ProgressPicture;
  
	/// Sets or retrieves the appearance of the file input control.
	public $InputboxCSSText="";
  
	/// Specifies where AjaxUploader should put the temporary files.
	public $TempDirectory;
	
	public $SaveDirectory;
	
	
	public $ResourceHandler;
	public $UploadUrl;
	public $LicenseUrl;
	
	
	function SetAttribute($name,$value)
	{
		$this->$name=$value;
	}
	function GetAttribute($name)
	{
		return $this->$name;
	}
	
	//__set/__get do not intercept the public fields
	function __set($propname,$propvalue)
	{
		$this->SetAttribute($propname,$propvalue);
	}
	function __get($propname)
	{
		return $this->GetAttribute($propname);
	}
	
	var $_preprocessed=false;
	var $_isverify=false;
	var $_isiframemode=false;
	var $_isaddonupload=false;
	var $_fileguid=null;
	var $_filevalidating=null;
	
	
	function PhpUploader()
	{
		$this->Name="AjaxUploaderFiles";
		
		$cd=dirname($this->GetWebPath(__FILE__));
		
		$this->ResourceDirectory="$cd/resources";
		$this->ResourceHandler="$cd/ajaxuploaderresource.php";
		$this->UploadUrl="$cd/ajaxuploaderhandler.php";
		
		
		//$this->TempDirectory=dirname(dirname(__FILE__)) . "/uploadertemp";
		//do not set it here ! may get exception !
		//$this->TempDirectory=PhpUploader_GetSystemTempFolder();
	
		$upload_max_filesize=PhpUploader_ParseByteSetting(ini_get('upload_max_filesize'));
		$post_max_size=PhpUploader_ParseByteSetting(ini_get('post_max_size'));
		$this->MaxHttpSizeKB=$upload_max_filesize/1024;
		
		if( $this->MaxHttpSizeKB > 16384 )	//16M
		{
			$this->FlashUploadMode="Http";
		}
		else
		{
			$this->FlashUploadMode="Partial";
		}
		
		
		if( $this->_IsUploadRequest() )
		{
			$this->Name=$_GET["_UploadControlID"];
			$this->PreProcessRequest();
		}
	}
	
	function GetWebPath($pfile)
	{
		$scriptfile=@$_SERVER['SCRIPT_FILENAME'];
		if(!$scriptfile)$scriptfile=$_SERVER['ORIG_SCRIPT_FILENAME'];
		
		$ppath=$scriptfile;
		$vpath=$_SERVER['SCRIPT_NAME'];
		
		$ppath=str_replace("//","/",str_replace("\\","/",$ppath));
		$vpath=str_replace("//","/",str_replace("\\","/",$vpath));
		$pfile=str_replace("//","/",str_replace("\\","/",$pfile));
		
		$lfile=strtolower($pfile);
		$lpath=strtolower($ppath);
		
		$l=min(strlen($pfile),strlen($ppath));
		for($i=0;$i<strlen($pfile);$i++)
		{
			if(substr($lfile,$i,1)!=substr($lpath,$i,1))
			{
				$vroot=substr($vpath,0,strlen($vpath)-(strlen($ppath)-$i));
				return $vroot . substr($pfile,$i);
			}
		}
	}
	
	function Render()
	{
		try
		{
			echo $this->GetString();
		}
		catch(Exception $x)
		{
			echo("Error:" . $x->getMessage());
			exit(200);
		}
	} 

	function GetString()
	{
		$this->Maintain();
		
		$this->SaveSecuritySettings();
				
		$resourcehandler=$this->ResourceHandler;   
		$code="";
		
		if($this->SaveDirectory)
		{
			if(!$this->AllowedFileExtensions)
			{
				$code.="<div style='font-weight:bold;color:red;font-size:16px;'>";
				$code.="When using SaveDirectory property, you must specify AllowedFileExtensions for security purpose.";
				$code.="</div>";
			}
		}


		if(!$this->InsertButtonID)
		{
			$code.="<button id='" . $this->Name . "Button' onclick='return false;'>" . $this->InsertText . "</button>";
		}
		
		$code.="<input type='hidden' id='" . $this->Name . "' name='" . $this->Name . "' autocomplete='off' />";
		
		$code.="<script type='text/javascript' src='$resourcehandler?type=script'></script>";
		
		$code.="<img id='" . $this->Name . "_Loader' UniqueID='" . $this->Name . "' ";
		$code.=" Namespace='".PhpUploader_GetNamespace()."' UploadModuleNotInstall='1' ServerLang='PHP' src='$resourcehandler?type=file&amp;file=continuous.gif'";
		
		if(!$this->InsertButtonID)
		{
			$code.=" InsertButtonID='" . $this->Name . "Button'";
		}
		
		$code.=$this->_GenerateAttribute("ResourceDirectory");
		$code.=$this->_GenerateAttribute("ResourceHandler");
		$code.=$this->_GenerateAttribute("UploadUrl");
		
		$code.=$this->_GenerateAttribute("InsertButtonID");
		$code.=$this->_GenerateAttribute("CancelButtonID");
		$code.=$this->_GenerateAttribute("ProgressTextID");
		$code.=$this->_GenerateAttribute("ProgressCtrlID");
		
		$code.=$this->_GenerateAttribute("AllowedFileExtensions");
		$code.=$this->_GenerateAttribute("AllowedFileRegExp");
		
		if($this->FlashUploadMode=="Partial")
		{
			$code.=" FlashLoadMode='1'";
			$code.=$this->_GenerateAttribute("MaxPartialSizeKB");
		}
		
		//$code.=$this->_GenerateAttribute("FlashUploadMode");
		
		$code.=$this->_GenerateAttribute("MaxHttpSizeKB");
		
		if($this->MaxSizeKB>0)$code.=$this->_GenerateAttribute("MaxSizeKB");
		
		if($this->MaxFilesLimit>0)$code.=$this->_GenerateAttribute("MaxFilesLimit");
		
		$code.=$this->_GenerateAttribute("LicenseUrl");
		
		$code.=$this->_GenerateAttribute("FileTypeNotSupportMsg");
		$code.=$this->_GenerateAttribute("FileTooLargeMsg");
		$code.=$this->_GenerateAttribute("MaxFilesLimitMsg");
		$code.=$this->_GenerateAttribute("WindowsDialogLimitMsg");
		$code.=$this->_GenerateAttribute("CancelUploadMsg");
		$code.=$this->_GenerateAttribute("CancelAllMsg");
		$code.=$this->_GenerateAttribute("UploadingMsg");
		$code.=$this->_GenerateAttribute("UploadProcessingMsg");
		$code.=$this->_GenerateAttribute("FlashWarningImage");
		$code.=$this->_GenerateAttribute("FlashUploadPage");
		$code.=$this->_GenerateAttribute("DialogFilter");
		$code.=$this->_GenerateAttribute("UploadCursor");
		$code.=$this->_GenerateAttribute("ButtonOnClickScript");
		$code.=$this->_GenerateAttribute("UploadType");
		$code.=$this->_GenerateAttribute("ManualStartUpload","bool");
		$code.=$this->_GenerateAttribute("ShowFrameBrowseButton","bool");
		$code.=$this->_GenerateAttribute("MultipleFilesUpload","bool");
		$code.=$this->_GenerateAttribute("ShowProgressBar","bool");
		$code.=$this->_GenerateAttribute("ShowProgressInfo","bool");
		
		$code.=$this->_GenerateAttribute("NumFilesShowCancelAll");
		$code.=$this->_GenerateAttribute("ProgressTextTemplate");
		
		$code.=$this->_GenerateAttribute("ProgressPanelWidth");
		$code.=$this->_GenerateAttribute("ProgressBarHeight");
		$code.=$this->_GenerateAttribute("ProgressInfoStyle");
		$code.=$this->_GenerateAttribute("ProgressBarStyle");
		$code.=$this->_GenerateAttribute("ProgressBarBackgroundImage");
		$code.=$this->_GenerateAttribute("ProgressPicture");
		$code.=$this->_GenerateAttribute("ProgressBarBorderStyle");
		
		
		//TODO:Opera!!
		$code.=" onload='this.style.display=&quot;none&quot; ; ".PhpUploader_GetNamespace()."_AjaxUploader_Initialize(this.id);' onerror='this.onload()' ContextValue='" .session_id(). "' />";
		return $code;
	}
	
	function _GenerateAttribute($name,$type=null)
	{
		$value=$this->$name;
		
		if(!$value)return "";
		
		switch($name)
		{
			case "AllowedFileExtensions":
				$name="Extensions";
				break;
			case "AllowedFileRegExp":
				$name="FileRegExp";
				break;
			case "ProgressPanelWidth":
				$name="PanelWidth";
				break;
			case "ProgressBarHeight":
				$name="BarHeight";
				break;
			case "ProgressBarStyle":
				$name="BarStyle";
				break;
			case "ProgressInfoStyle":
				$name="InfoStyle";
				break;
			case "ProgressBarBackgroundImage":
				$name="BgImage";
				break;
			case "ProgressBarBorderStyle":
				$name="BorderStyle";
				break;
		}
		
		if($type=="bool")
		{
			$value=$value?"1":"0";
		}
		
		return " $name='".htmlspecialchars($value,ENT_QUOTES)."'";
	}

	
	function _CheckProcessed()
	{
		if(!$this->_preprocessed)
			throw(new Exception("Call PreProcessRequest before access this member!"));
	}

	function IsValidationRequest()
	{
		$this->_CheckProcessed();
		return $this->_isverify;
	}
	function GetCurrentFileGuid()
	{
		$this->_CheckProcessed();
		return $this->_fileguid;
	}
	function GetValidatingFile()
	{
		if(!$this->_filevalidating)
		{
			if(!$this->IsValidationRequest())
				throw(new Exception("Current request is not validation request"));
			$this->_filevalidating = $this->_InternalGetFile($this->_fileguid,false);
			if(!$this->_filevalidating)
			{
				$dir = $this->InternalGetTempDirectory();
				throw(new Exception("Unable to find file " . $this->_fileguid . " in $dir"));
			}
		}
		return $this->_filevalidating;
	}
	function GetUploadedFile($guid)
	{
		$guid=PhpUploader_GetGuid($guid);
		return $this->_InternalGetFile($guid,true);
	}
	function _InternalGetFile($guid,$checkValidated)
	{
		$dir = $this->InternalGetTempDirectory();
		$arr=PhpUploader_GetFiles(__FILE__,__LINE__,"$dir/*." . $guid . ".*");
		if($arr==null||count($arr)==0)
			return null;
		if( $checkValidated && substr(PhpUploader_GetBaseName($arr[0]),0,10)!="persisted.")
			throw(new Exception("This file can not be validated!"));
		$mvcfile=new PhpUploadFile();
		$mvcfile->FileGuid=$guid;
		$mvcfile->FilePath=str_replace("\\","/",$arr[0]);
		$mvcfile->FileSize=filesize($arr[0]);
		$mvcfile->FileName=substr(PhpUploader_GetBaseName($arr[0]),47,-5);//also remove suffix ".resx"
		return $mvcfile;
	}
	
	function InternalGetTempDirectory()
	{
		$dir=$this->TempDirectory;
		if($dir==null)
			$dir=PhpUploader_GetSystemTempFolder();
		$dir=str_replace("\\","/",$dir);
		if(substr($dir,strlen($dir)-1,1)=="/")
		{
			$dir=substr($dir,0,strlen($dir)-1);
		}
		return $dir;
	}
	
	function Maintain_WithEncode()
	{
		$dir = $this->InternalGetTempDirectory();
		$arr=PhpUploader_GetFiles(__FILE__,__LINE__,"$dir/*.resx");
		if($arr==null)return;
		$now=time();
		foreach($arr as $filename)
		{
			$mt=PhpUploader_FileTime(__FILE__,__LINE__,$filename);
			if ( $now - $mt > 18000 ) //5 hours
			{
				PhpUploader_Delete(__FILE__,__LINE__,$filename);
			}
		}
	}
	function Maintain()
	{
		$dir = $this->InternalGetTempDirectory();
		$arr=glob("$dir/*.resx");
		if($arr==null)return;
		$now=time();
		foreach($arr as $filename)
		{
			$mt=filemtime($filename);
			if ( $now - $mt > 18000 ) //5 hours
			{
				unlink($filename);
			}
		}
	}
	
	function _SaveSecuritySetting($settingname)
	{
		$_SESSION[$this->Name."_val_" . $settingname]=$this->$settingname;
		$_SESSION[$this->Name."_set_" . $settingname]="1";
	}
	function _LaveSecuritySetting($settingname)
	{
		if(@$_SESSION[$this->Name."_set_" . $settingname])
		{
			$this->$settingname=$_SESSION[$this->Name."_val_" . $settingname];
		}
	}

	function SaveSecuritySettings()
	{
		if(!@$_SESSION)session_start();
		
		$scriptfile=@$_SERVER['SCRIPT_FILENAME'];
		if(!$scriptfile)$scriptfile=$_SERVER['ORIG_SCRIPT_FILENAME'];
		
		$this->_SourceFileName = $scriptfile;
		$this->_SaveSecuritySetting("_SourceFileName");
		$this->_SaveSecuritySetting("MaxSizeKB");
		$this->_SaveSecuritySetting("AllowedFileExtensions");
		$this->_SaveSecuritySetting("AllowedFileRegExp");
		$this->_SaveSecuritySetting("SaveDirectory");
		$this->_SaveSecuritySetting("TempDirectory");
	}
	function LoadSecuritySettings()
	{
		if(!@$_SESSION)session_start();
		
		$this->_LaveSecuritySetting("_SourceFileName");
		$this->_LaveSecuritySetting("MaxSizeKB");
		$this->_LaveSecuritySetting("AllowedFileExtensions");
		$this->_LaveSecuritySetting("AllowedFileRegExp");
		$this->_LaveSecuritySetting("SaveDirectory");
		$this->_LaveSecuritySetting("TempDirectory");
		if(!$this->_SourceFileName)
		{
			throw (new Exception("$this->Name : Session expired! Please refresh the page and try again."));
		}
	}
	
	function _IsUploadRequest()
	{
		if(@$_SERVER['REQUEST_METHOD']!="POST")
			return false;
		
		if(@$_GET['UseUploadModule']==null)
			return false;
		
		if(@$_GET['_Namespace']!=PhpUploader_GetNamespace())
			return false;
		
		if(@$_GET['PageUpload']!="1")
			return false;
		
		return true;
	}
	function PreProcessRequest()
	{
		$this->_PreProcessRequestInternal();
		if( ! $this->IsValidationRequest() )
			exit(200);
	}
	function _PreProcessRequestInternal()
	{
		if($this->_preprocessed)
			return false;
		$this->_preprocessed=true;
		
		if( ! $this->_IsUploadRequest() )
			return false;
	
		if(@$_GET['_GetAddonInfo']=="size")
		{
			//TODO:handle silverlight resume feature.
			exit(404);
		}
		
		//if(@$_GET['GetUploaderError']=="1")
		//{
		//	exit(404);
		//}
		
		if(@$_GET['_Addon']=="xhttp")
		{
			$this->_isaddonupload=true;
			try
			{
				PhpUploader_Log("start xhttp upload");
				$this->LoadSecuritySettings();
				$this->_HandleXhttpUpload();
			}
			catch(Exception $x)
			{
				PhpUploader_Log("Error:" . $x->getMessage());
				exit(200);
			}
			echo("OK");
			exit(200);
		}
		else if(@$_GET['_Addon']=="upload")
		{
			$this->_isaddonupload=true;
				
			try
			{
				PhpUploader_Log("start addon upload");
				$this->LoadSecuritySettings();
				$this->_HandleAddonUpload();
			}
			catch(Exception $x)
			{
				PhpUploader_Log("Error:" . $x->getMessage());
				echo("Error:" . $x->getMessage());
				exit(200);
			}
			echo("OK");
			exit(200);
		}
		else
		{
			$this->_isverify=true;
			try
			{
				if(@$_GET['_Addon']==null)
				{
					$this->_isiframemode=true;
					PhpUploader_Log("start iframe upload");
					$this->LoadSecuritySettings();
					$this->_HandleIFrameUpload();
				}
				else
				{
					PhpUploader_Log("start addon verify");
					$this->LoadSecuritySettings();
					$this->_HandleAddonVerify();
				}
			}
			catch(Exception $x)
			{
				PhpUploader_Log("Error:" . $x->getMessage());
				$this->_EndWithUploadError($x->getMessage());
			}
		}
		
		return true;
	}
	
	function _ValidateFile($filename,$filesize)
	{	
		$this->SecurityCheckFileName($filename);
		
		$maxsize=$this->MaxSizeKB;
		if($maxsize && $maxsize>0)
		{
			if($filesize > $maxsize*1024)
			{
				throw (new Exception("Error:TOOLARGE"));
			}
		}
		
		$exts=$this->AllowedFileExtensions;
		if($exts)
		{
			$exts=preg_replace("/[,;|]/","|",$exts);
			$exts=explode("|",$exts);
			
			$extension=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
			$found=false;
			foreach($exts as $ext)
			{
				$ext=strtolower(preg_replace("/[\\*\\.]/","",$ext));
				if($ext==$extension)
				{
					$found=true;
					break;
				}
			}
			if(!$found)
			{
				throw (new Exception("Error:INVALIDEXT"));
			}
		}
		else if(!$this->_SourceFileName)
		{
			throw (new Exception("$this->Name : Session expired! Please refresh the page and try again."));
		}
		
		$fre=$this->AllowedFileRegExp;
		if($fre)
		{
			if(preg_replace("/$fre/","?",$filename)==$filename){
				throw (new Exception("$filename is not allowed."));
			}
		}
		
		PhpUploader_Log("Validated : $filename , AllowedFileExtensions : $this->AllowedFileExtensions");
	}
	function SecurityCheckFileName($filename)
	{
		//TO LOWER CASE!!
		$filename=strtolower($filename);
		
		if(strpos($filename,"\0"))
			throw (new Exception("Invalid filename !!"));
		
		if(strpos($filename,".php"))
		{
			PhpUploader_Log("try_upload_php_xfile");
			throw(new Exception("(1) fails to upload xfile : " . $filename));
		}
		if(strpos($filename,".asp"))
		{
			PhpUploader_Log("try_upload_asp_xfile");
			throw(new Exception("(1) fails to upload xfile : " . $filename));
		}
		
		$extension=pathinfo($filename,PATHINFO_EXTENSION);
		if($extension=="php")
		{
			PhpUploader_Log("try_upload_php_file");
			throw(new Exception("(1) fails to upload file : " . $filename));
		}
	}
	
	function _HandleXhttpUpload()
	{
		$this->_fileguid=PhpUploader_GetGuid($_GET['_AddonGuid']);
		$filedata=$_POST["filedata"];
		$filedata=preg_replace("/[\\-]/","+",$filedata);
		$data=base64_decode($filedata,false);

		$fn=PhpUploader_GetQSD("_PartialFileName");
		$ps=@$_GET["_PartialStart"];
		
		if(strpos($fn,"\0"))throw (new Exception("Invalid partial path !!"));
			
		$existfile=$this->_InternalGetFile($this->_fileguid,false);
		
		if($ps==0)
		{
			if($existfile)
				throw(new Exception("Guid already exists!"));
			
			$basename=PhpUploader_GetBaseName($fn);

			$this->SecurityCheckFileName($basename);
			
			$dir = $this->InternalGetTempDirectory();
			$filepath="$dir/uploading." . $this->_fileguid . "." . $basename . ".resx";
			$fh=PhpUploader_FileOpen(__FILE__,__LINE__,$filepath,"x+b");
			PhpUploader_FileWrite(__FILE__,__LINE__,$fh,$data);
			PhpUploader_FileClose(__FILE__,__LINE__,$fh);
			
			PhpUploader_Log("File Save to : $filepath");
		}
		else
		{
			if(!$existfile)
				throw(new Exception("File not exists!"));
			if($existfile->FileSize!=$ps)
				throw(new Exception("Invalid size ! $ps/" . $existfile->FileSize));
			$fh=PhpUploader_FileOpen(__FILE__,__LINE__,$existfile->FilePath,"a+b");
			PhpUploader_FileWrite(__FILE__,__LINE__,$fh,$data);
			PhpUploader_FileClose(__FILE__,__LINE__,$fh);
			
			PhpUploader_Log("File Append to : $existfile->FilePath");
		}
	}
	
	function _HandleAddonUpload()
	{
		$this->_fileguid=PhpUploader_GetGuid($_GET['_AddonGuid']);
		
		$file="";
		foreach($_FILES as $key => $eachfile)
		{
			$file=$eachfile;
		}

		if(!$file)
		{
			$inival=ini_get('file_uploads');
			
			if(!$inival)
				throw(new Exception("Php ini error : make sure 'file_uploads' is On."));

			$inival=strtolower($inival);
			if( $inival == "off" || $inival=="0" || $inival=="false" || $inival=="no" )
				throw(new Exception("Php ini error : 'file_uploads' is $inival ."));

			throw(new Exception("Php No Request Files"));
		}

		//The PHP will convert '.' to '_'
		$addonpartial=@$_POST[PhpUploader_GetNamespace().".AjaxUploader.Partial"];
		if(!$addonpartial)$addonpartial=@$_POST[PhpUploader_GetNamespace()."_AjaxUploader_Partial"];
		
		$this->SecurityCheckFileName(PhpUploader_GetFileName($file));

		$existfile=$this->_InternalGetFile($this->_fileguid,false);
		
		if($addonpartial)
		{
			if($addonpartial=="Start")
			{
				if($existfile)
					throw(new Exception("Guid Error!$addonpartial"));
				$this->_MoveHttpFile($file,$this->_fileguid);
			}
			else
			{
				if(!$existfile)
					throw(new Exception("File Not Found!"));
				$this->_AppendHttpFile($file,$existfile->FilePath);
			}
		}
		else
		{
			if($existfile)
				throw(new Exception("Guid Error !"));
			$this->_MoveHttpFile($file,$this->_fileguid);
		}
		
	}
	function _HandleAddonVerify()
	{
		$this->_fileguid=PhpUploader_GetGuid($_GET['_AddonGuid']);
		
		$mvcfile=$this->GetValidatingFile();
		
		$newname=PhpUploader_GetBaseName(PhpUploader_GetQSD("_VFN"));
		if($newname&&$newname!=$mvcfile->FileName)
		{
		    $mvcfile->FileName=$newname;
		    $this->_ValidateFile($mvcfile->FileName,$mvcfile->FileSize);
		    PhpUploader_Log("UpdateFileName $newname . " . $mvcfile->FilePath);
		    $mvcfile->UpdateFileName();
		    PhpUploader_Log("UpdateFileName FINISH");
		}
		else
		{
		    $this->_ValidateFile($mvcfile->FileName,$mvcfile->FileSize);
		}
	}
	function _HandleIFrameUpload()
	{
		$file="";
		foreach($_FILES as $key => $eachfile)
		{
			$file=$eachfile;
		}
		if(!$file)
		{
			throw(new Exception("No Request Files "));
		}
		
		$this->_ValidateFile(PhpUploader_GetBaseName(PhpUploader_GetFileName($file)),$file["size"]);
		
		$this->_fileguid=PhpUploader_CreateGuid();
		
		$this->_MoveHttpFile($file,$this->_fileguid);
		
		//cache it to $this->_filevalidating
		$mvcfile=$this->GetValidatingFile();
	}

	function _MoveHttpFile($file,$guid)
	{
		if(!@$file["tmp_name"])
			throw(new Exception("(1) tmp_name is not available for " . PhpUploader_GetFileName($file)));
		if(!PhpUploader_FileExists($file["tmp_name"]))
			throw(new Exception("(1) tmp_name is not exists " . $file["tmp_name"]));

		PhpUploader_Log("_MoveHttpFile $file : $guid");
		
		$this->SecurityCheckFileName(PhpUploader_GetFileName($file));
		
		$extension=strtolower(pathinfo(PhpUploader_GetFileName($file),PATHINFO_EXTENSION));
		if($extension=="php")
		{
			PhpUploader_Log("try_upload_php_file");
			throw(new Exception("(1) fails to upload file : " . PhpUploader_GetFileName($file)));
		}
		
		$dir = $this->InternalGetTempDirectory();
		$filepath="$dir/uploading." . $guid . "." . PhpUploader_GetBaseName(PhpUploader_GetFileName($file)) . ".resx";
		
		PhpUploader_Log(" to : $filepath");
		
		//throw(new Exception("Get file ".PhpUploader_GetFileName($file)." - $filepath"));

		$result=PhpUploader_MoveUploadedFile(__FILE__,__LINE__,$file["tmp_name"],$filepath);
		if(!$result)
		{
			throw(new Exception("Unable save file to $filepath"));
		}
	}
	function _AppendHttpFile($file,$path)
	{
		if(!$file["tmp_name"])
			throw(new Exception("(2) tmp_name is not available for " . PhpUploader_GetFileName($file)));
		if(!PhpUploader_FileExists($file["tmp_name"]))
			throw(new Exception("(2) tmp_name is not exists " . $file["tmp_name"]));
	
		PhpUploader_Log("_AppendHttpFile $file : $path");
		
		$len=filesize($file["tmp_name"]);
		$src=PhpUploader_FileOpen(__FILE__,__LINE__,$file["tmp_name"],"r");
		$data=PhpUploader_FileRead(__FILE__,__LINE__,$src,$len);
		PhpUploader_FileClose(__FILE__,__LINE__,$src);
		
		$dsc=PhpUploader_FileOpen(__FILE__,__LINE__,$path,"a");
		PhpUploader_FileWrite(__FILE__,__LINE__,$dsc,$data);
		PhpUploader_FileClose(__FILE__,__LINE__,$dsc);
	}

	function WriteValidationOK($message)
	{
		if(!$message)$message="";
		
		//change "uploading." to "persisted." :
		$mvcfile=$this->GetValidatingFile();
		
		$oldpath=$mvcfile->FilePath;
		if( PhpUploader_FileExists($oldpath) )
		{
			$oldpath=str_replace("\\","/",$oldpath);
			$bn=PhpUploader_GetBaseName($oldpath);
			if(substr($bn,0,10)=="uploading.")
			{
				$newpath=substr($oldpath,0,-strlen($bn)) . "persisted." . substr($bn,10);
				PhpUploader_Move(__FILE__,__LINE__,$oldpath,$newpath);
				$mvcfile->FilePath=$newpath;
			}
		}

		$uploadid = $this->_GetAndCheckUploadID();
		echo("<script type='text/javascript'>");
		echo("if(window.parent.CurrentUpload)window.parent.CurrentUpload.UploadOK('" . $uploadid . "','" . $this->_fileguid . "','" . PhpUploader_JSEncode($message) . "')");
		echo("</script>");
		
		PhpUploader_Log("WriteValidationOK");
	}
		
	function WriteValidationError($message)
	{
		//Error , delete that file?
		$mvcfile=$this->GetValidatingFile();
		
		try
		{
			PhpUploader_Delete(__FILE__,__LINE__,$mvcfile->FilePath);
		}
		catch(Exception $x)
		{
		}
		
		$uploadid = $this->_GetAndCheckUploadID();
		echo("<script type='text/javascript'>");
		echo("if(window.parent.CurrentUpload)window.parent.CurrentUpload.UploadError('" . $uploadid . "','" . PhpUploader_JSEncode($message) . "')");
		echo("</script>");
		
		PhpUploader_Log("WriteValidationError $message");
	}
	
	function _EndWithUploadError($message)
	{
		PhpUploader_Log("_EndWithUploadError $message");
		
		$uploadid = $this->_GetAndCheckUploadID();
		echo("<script type='text/javascript'>");
		echo("if(window.parent.CurrentUpload)window.parent.CurrentUpload.UploadError('" . $uploadid . "','" . PhpUploader_JSEncode($message) . "')");
		echo("</script>");
		exit(200);
	}
	
	function _GetAndCheckUploadID()
	{
		$id = @$_GET['_UploadID'];
		if(stripos($id,"<")!=false||stripos($id,">")!=false||stripos($id,"'")!=false||stripos($id,"\"")!=false||stripos($id,"\\")!=false)
			throw(new Exception("Invalid _UploadID"));
		return $id;
	}  
	
}

class PhpUploadFile
{
	public $FileGuid;
	public $FileName;
	public $FileSize;
	public $FilePath;
	function UpdateFileName()
	{
	    $oldpath=$this->FilePath;
	    $bname=PhpUploader_GetBaseName($oldpath);
	    $folder=substr($oldpath,0,strlen($oldpath)-strlen($bname));
	    $newpath=$folder.substr($bname,0,47).$this->FileName.".resx";
	    PhpUploader_Move(__FILE__,__LINE__,$this->FilePath,$newpath);
	    $this->FilePath=$newpath;
	}
	function MoveTo($newpath)
	{
		if(is_dir($newpath))
			$newpath=$newpath . "/" . $this->FileName;
		PhpUploader_Move(__FILE__,__LINE__,$this->FilePath,$newpath);
		PhpUploader_Log(" MoveTo $newpath from $this->FilePath");
	}
	function CopyTo($newpath)
	{
		if(is_dir($newpath))
			$newpath=$newpath . "/" . $this->FileName;
		PhpUploader_Copy(__FILE__,__LINE__,$this->FilePath,$newpath);
		PhpUploader_Log(" CopyTo $newpath from $this->FilePath");
	}
	function Delete()
	{
		PhpUploader_Delete(__FILE__,__LINE__,$this->FilePath);
	}
}


?>