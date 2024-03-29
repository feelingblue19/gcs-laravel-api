<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Storage\StorageClient;
use Throwable;

class StorageController extends Controller
{
    private $storage;
    private $bucketName;


    public function __construct()
    {
        $googleConfigFile = file_get_contents(config_path('googlecloud.json'));

        $this->storage = new StorageClient([
            'keyFile' => json_decode($googleConfigFile, true)
        ]);

        $this->bucketName = config('app.storage_bucket');
        
    }

    public function upload(Request $request)
    {
        $bucket = $this->storage->bucket($this->bucketName);

        $fileSource = $request->file('file');
        $fileSource = file_get_contents($_FILES['file']['tmp_name']);

        $fileName = $request->file_name;

        $bucket->upload($fileSource, [
            'name' => $fileName
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'File successfully uploaded',
            'data' => null,
        ]);
    }

    public function download(Request $request)
    {
        try {
            $tempStream = tmpfile();
            $tempFile = stream_get_meta_data($tempStream)['uri'];

            $bucket = $this->storage->bucket($this->bucketName);

            $object = $bucket->object($_GET['file_name']);
			// $request->file_name
            if (!$object->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'File not found',
                    'data' => null
                ], 404);
            }

            $content = $object->downloadAsStream([
                'restOptions' => [
                    'sink' => $tempStream
                ]
            ]);
			
            return response()->json([
                'status' => 1,
                'message' => 'File successfully fetched',
                'data' => [
                    'encoded_file' => base64_encode(file_get_contents($tempFile))
                ]
            ], 200);
        } catch (Throwable $th) {
            unlink($tempFile);

            return response()->json([
                'status' => 0,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function delete(Request $request)
    {
		try {
			$bucket = $this->storage->bucket($this->bucketName);

			$object = $bucket->object($request->file_name);

			if (!$object->exists()) {
				return response()->json([
					'status' => 0,
					'message' => 'File not found',
					'data' => null
				], 404);
			}

			$object->delete();

			return response()->json([
				'status' => 1,
				'message' => 'File successfully deleted',
				'data' => null
			], 200);
		} catch (Throwable $th)  {
			return response()->json([
                'status' => 0,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
		}
        
    }
}
