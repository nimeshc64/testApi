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

    public function dep(Request $request)
    {
        $d1=$request->input('d1');
        $d2=$request->input('d2');
        $d3=$request->input('d3');
        $d4=$request->input('d4');
        $d5=$request->input('d5');
        $d6=$request->input('d6');
        $d7=$request->input('d7');
        $d8=$request->input('d8');
        $amt=$request->input('amt');
        $tid=$request->input('tid');
        $ac=$request->input('ac');
        $ni=$request->input('ni');
        $mo=$request->input('mo');
        $fac=$request->input('fac');
        $loc=$request->input('loc');
        $bra=$request->input('bra');


//        if($d1==1&&$d2==1&&$d3==1&&$d4==1&&$d5==1&&$d6==1&&$d7==1&&$d8==1&&$amt==8680&&)
//        {
//            return response()->json(['0x9000','000000000001','sn000000001']);
//        }
//        else{
//            return response()->json(['0x9100','000000000001','sn000000001']);
//        }


        return $request;

    }

}