<?php

namespace App\Lib;

use Exception;

class FileUpload {
    private $fileServerUrl = 'https://fileserver1.mycraftit.com';
    private $fileServerUploadScript = '/upload.php';
    private $allowedImageTypes = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
    private $allowedDocumentTypes = ['doc', 'docx', 'odt', 'pdf', 'xls', 'xlsx', 'ods', 'ppt', 'pptx', 'txt'];
    private $maxSize = [
        // 2MB
        'image' => 2000000,
        // 5MB
        'document' => 5000000,
    ];

    public $file;
    public $fileData;

    public $uploadError;
    public $finalImageURL;

    
    function __construct($fileBlob, $oldFileData, $permitToFileType = null) {
        $this->file = base64_decode(explode(',', $fileBlob)[1]);

        $fileExtn = explode('.', $oldFileData['name']);

        $this->fileData = [
            // get only last seperator from file: "test.datei.txt"
            'fileExtension' => strtolower($fileExtn[array_key_last($fileExtn)]),
        ];

        // checks valid formats and sets fileData format
        $this->checkFormat();

        // checks valid sizes
        $this->checkSize($oldFileData['size']);
        $this->fileData['readableSize'] = $this->formatSizeBytesAsHumanText($oldFileData['size']);
        
        // checks if only one fileType is allowed for the operation
        if($permitToFileType) $this->permitFileType($permitToFileType);

    }

    public function url() {
        return $this->finalImageURL;
    }

    public function size() {
        return $this->fileData['readableSize'];
    }

    public function upload() {

        if($this->uploadError) {
            return $this->uploadError;
        }

        $this->generateFileName();

        $this->sumibtCurlRequest();

        return 'success';

    }

    public function hasErrors() {
        $err = $this->uploadError;
        if(empty($err) || $err == '') {
            return false;
        }else {
            return true;
        }
    }

    public function returnError() {
        if($this->uploadError == 'format')
            return 'Ein falsches Format wurde angegeben';
        if($this->uploadError == 'size')
            return 'Der Anhang ist zu groß';
        else
            return 'Ein Fehler beim Hochladen ist aufgetreten';
    }

    private function finalizeImageData() {

        // generating file URL for database usage
        if($this->fileData['fileType'] == 'image')
            $imgURL = $this->fileServerUrl.'/uploads/images/'.$this->fileData['fullFileName'];
        else
            $imgURL = $this->fileServerUrl.'/uploads/documents/'.$this->fileData['fullFileName'];

        $this->finalImageURL = $imgURL;
        $this->fileData['finalImageURL'] = $imgURL;
    }

    private function generateFileName() {
        $this->fileData['fileName'] = uniqid('phpUpload-');
        $this->fileData['fullFileName'] = $this->fileData['fileName'].'.'.$this->fileData['fileExtension'];

    }

    private function permitFileType($permit) {
        if($permit == 'document') if($this->fileData['fileType'] != 'document') $this->uploadError = 'format';
        if($permit == 'image') if($this->fileData['fileType'] != 'image') $this->uploadError = 'format';
    }

    private function checkFormat() {
        if(in_array($this->fileData['fileExtension'], $this->allowedImageTypes)){
            // Image
            $this->fileData['fileType'] = 'image';
        }else if(in_array($this->fileData['fileExtension'], $this->allowedDocumentTypes)){
            // Document
            $this->fileData['fileType'] = 'document';
        }else{
            $this->uploadError = 'format';
        }
    }

    private function checkSize($oldFileSize) {
        if($this->fileData['fileType'] == 'image')
            if($oldFileSize > $this->maxSize['image'])
                $this->uploadError = 'size';

        if($this->fileData['fileType'] == 'document')
            if($oldFileSize > $this->maxSize['document'])
                $this->uploadError = 'size';
    }

    private function submitGuzzleRequest() {
        $apiURL = $this->fileServerUrl.$this->fileServerUploadScript;
        $postInput = array(
            'fileName' => $this->fileData['fullFileName'],
            'fileData' => $this->file,
        );
                
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
     
        $this->finalizeImageData();

    }

    private function formatSizeBytesAsHumanText($size, $precision = 2){
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
    
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    private function sumibtCurlRequest(){
        $this->submitGuzzleRequest();
        
        // $data = array(
        //     'fileName' => $this->fileData['fullFileName'],
        //     'fileData' => $this->file,
        // );
        
        // // start curl set up for remote file upload
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $this->fileServerUrl.$this->fileServerUploadScript);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        // curl_setopt($curl, CURLOPT_POST, 1);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // $response = curl_exec($curl);
        // curl_close($curl);

        // var_dump($data);
        // var_dump($response);
        // echo 'curl done';

        // $this->finalizeImageData();

    }



}
