<?php
/**
 * Created by PhpStorm.
 * User: Nimesh
 * Date: 12/22/2016
 * Time: 10:07 AM
 */
namespace App\Http\Controllers;
use App\Update;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class UpdateController extends Controller
{

    public function upload(Request $request)
    {
        $directoryPath = base_path() . '/ZipUpload/';
        $fileName = $_FILES["file"]["name"];
        $version=$request->input('version');

        try {

            if ($_FILES) {
                if ($this->Directry())
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $directoryPath . $version.".zip");
                    if (is_file($directoryPath . $version.".zip"))
                    {
                        $save= $this->saveVersion($version);
                        return $save;
                    }
                    else
                    {
                        return "File upload Error";
                    }
                }
                else
                {
                    return "Server Directory Error";
                }

            }
            else
            {
                return "File Upload Error";
            }
        }
        catch (Exception $ex)
        {
            return "Upload: " . $ex;
        }
    }

    public function download(Request $request)
    {
        $filename=$request->input('fileName');
        $file= base_path() . '/ZipUpload/'.$filename.'.zip';

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file,$filename.'.zip', $headers);

    }

    private function Directry()
    {
        $directoryPath= base_path().'/ZipUpload';

        try
        {
            if (!is_dir($directoryPath))
            {
                mkdir($directoryPath,0777);
                return true;
            }
            return true;
        }
        catch (Exception $ex)
        {
            return "Directory: ".$ex;
        }
    }

    public function saveVersion($version)
    {
        $update=new Update();
        $update->version=$version;
        $save=$update->save();
        return response()->json($save);
    }

    public function getLastVersion()
    {
        $lastRecord = Update::latest('id')->first();
        $version=$lastRecord['version'];
        return $version;
    }

    public function inq(Request $request)
    {
        $tid=$request->input('tid');
        $ac=$request->input('inq');

        if($tid=='B0000001' && $ac=='1234567891234')
        {
            return '0x9000';
            //return $request;
        }
        else{
           return '0x9100';
//            return $request;
        }
    }

}