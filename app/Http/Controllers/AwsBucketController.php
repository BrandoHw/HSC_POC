<?php

namespace App\Http\Controllers;

use Aws\Exception\AwsException;
use Illuminate\Http\Request;
use Aws\Laravel\AwsFacade;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Storage;

class AwsBucketController extends Controller
{
    //
    public function getImage(){
        $url = 'C:\Users\User\Downloads\greyimage.png';
        $s3 = AwsFacade::createClient('s3');
        $buckets = $s3->listBuckets();
        foreach ($buckets['Buckets'] as $bucket) {
            echo $bucket['Name'] . "\n";
        }

        try {
            $s3->putObject(array(
                'Bucket'     => 'wecare-hsc-images',
                'Key'        => 'YOUR_OBJECT_KEY',
                'SourceFile' => $url,
            ));
        } catch (S3Exception $e){
            echo $e->getMessage();
        }catch (AwsException $e) {
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . "\n";
            echo $e->getAwsErrorType() . "\n";
            echo $e->getAwsErrorCode() . "\n";
        
            // This dumps any modeled response data, if supported by the service
            // Specific members can be accessed directly (e.g. $e['MemberName'])
            var_dump($e->toArray());
        }

    }

    public function uploadImage(){}

    public function getThumbnail(){}
}
