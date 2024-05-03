
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array('jpg', 'jpeg', 'png', 'pdf', 'gif', 'tiff', 'doc', 'docx');
// max file size in bytes
$sizeLimit = 49 * 1024 * 1024;
//Gus*****************
include_once 'dbname.php';
$m = new dbname();
$dbname = $m->getdbname();
//Gus*****************
// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
if (!is_dir('temp/' . $m->getdbname() . '/')) {
    mkdir("temp/" . $m->getdbname(), 0775, true);
    chmod("temp/" . $m->getdbname(), 0775);
}
if (!is_dir('temp/' . $m->getdbname() . '/')) {
    die('not directory');
} else {
    //die($m->getdbname());
}



$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
if($uploader->Error) {
    echo json_encode(array("Code"=>"500","Msj"=>$uploader->Error));
    exit();
}
$result = $uploader->handleUpload('temp/' . $m->getdbname() . '/');
/* print_r($result);
  print_r('ivan');
  exit(); */



// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

//echo $result;
class qqFileUploader {

    private $allowedExtensions;
    private $sizeLimit;
    private $file;
    private $uploadName;
    public $Error = '';

    /**
     * @param array $allowedExtensions; defaults to an empty array
     * @param int $sizeLimit; defaults to the server's upload_max_filesize setting
     */
    function __construct(array $allowedExtensions = null, $sizeLimit = null) {

        if ($allowedExtensions === null) {
            $allowedExtensions = array();
        }
        if ($sizeLimit === null) {
            $sizeLimit = $this->toBytes(ini_get('upload_max_filesize'));
        }

        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = new qqUploadedFileXhr();
        }
    }

    /**
     * Get the name of the uploaded file
     * @return string
     */
    public function getUploadName() {
        if (isset($this->uploadName))
            return $this->uploadName;
    }

    /**
     * Get the original filename
     * @return string filename
     */
    public function getName() {
        if ($this->file)
            return $this->file->getName();
    }

    /**
     * Internal function that checks if server's may sizes match the
     * object's maximum size for uploads
     */
    private function checkServerSettings() {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            //$this->Error = 'error : increase post_max_size and upload_max_filesize to ' . $size;
            //die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    /**
     * Convert a given size with units to bytes
     * @param string $str
     */
    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val = intval($val) * 1024;
            case 'm': $val = intval($val) * 1024;
            case 'k': $val = intval($val) * 1024;
        }
        return $val;
    }

    /**
     * Handle the uploaded file
     * @param string $uploadDirectory
     * @param string $replaceOldFile=true
     * @returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE) {
        if (!is_writable($uploadDirectory)) {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file) {
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = @$pathinfo['extension'];  // hide notices if extension is empty

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
        }

        $ext = ($ext == '') ? $ext : '.' . $ext;

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            $filename = str_replace(' ', '-', $filename);
            if (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext)) {
                // $filename .= '-'.date("Y-m-d") .'-'.date("H-i-s");
            }
        }

        $this->uploadName = $filename . $ext;
        $m = new dbname();
        $dbname = $m->getdbname();
        if ($this->file->save($uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext)) {
            /**/
            $FileName = 'temp/' . "$dbname/" . $filename . $ext;
            if (!file_exists($FileName)) {
                return array('error' => 'File Not Found');
            }
            /**/
            return array('success' => 'temp/' . "$dbname/" . $filename . $ext);
        } else {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {

    function __construct() {
        
    }

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()) {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    /**
     * Get the original filename
     * @return string filename
     */
    public function getName() {
        return $_GET['qqfile'];
    }

    /**
     * Get the file size
     * @return integer file-size in byte
     */
    public function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int) $_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path) {
        $tmpName = $_FILES['qqfile']['tmp_name'];
        $fp = fopen($tmpName, 'r');
        $content = fread($fp, filesize($tmpName));
        $content = addslashes($content);
        fclose($fp);

        return move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
    }

    /**
     * Get the original filename
     * @return string filename
     */
    public function getName() {
        return $_FILES['qqfile']['name'];
    }

    /**
     * Get the file size
     * @return integer file-size in byte
     */
    public function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

/**
 * Class that encapsulates the file-upload internals
 */
?>
