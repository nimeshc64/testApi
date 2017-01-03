<?php
/**
 * Created by PhpStorm.
 * User: Nimesh
 * Date: 1/3/2017
 * Time: 10:12 AM
 */
namespace App\Http\Controllers;
use App\Logs;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class LogsController extends Controller
{

    /**
     * @param Request $request
     * branch , terminal
     * @return Exception|string
     * pullNow 1 || 0
     */
    function check(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=$request->input('terminal');
        $pullnow="";

        try{
            $result= DB::table('logs')
                ->select('pullNow')
                ->where('branch','=',$branch)
                ->where('terminal','=',$terminal)
                ->get();

            if($result==null)
            {
                $save=$this->save($branch,$terminal);
                if($save)
                {
                    return 0;
                }
            }else{
                foreach ($result as $rs)
                {
                    $pullnow=$rs->pullNow;
                }
            }
        }
        catch (Exception $ex)
        {
            return $ex;
        }
        return $pullnow;
    }


    /**
     * @param Request $request
     * file , branch , terminal
     * @return string
     */
    function logUpload(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=$request->input('terminal');
        $DirPath=base_path().'/LogUpload/'.$branch.'/'.$terminal.'/';

        try{

            if ($_FILES)
            {
                $fileName = $_FILES["file"]["name"];
                if($this->Directry($branch,$terminal))
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $DirPath.$fileName);
                    $update=$this->update($branch,$terminal,$fileName);
                    return $update;
                }
                else{
                    return "false";
                }
            }
            else{
                return "false";
            }

        }
        catch (Exception $ex)
        {
            return "logUpload: ".$ex;
        }
    }

    /**
     * @param Request $request
     * branch , terminal , filename
     * @return mixed
     */
    function logDownload(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=$request->input('terminal');
        $filename=$request->input('fileName');

        $file= base_path().'/LogUpload/'.$branch.'/'.$terminal.'/'.$filename;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file,$filename, $headers);
    }

    /**
     * @param $branch
     * @param $terminal
     * @return mixed
     */
    function save($branch, $terminal)
    {
        $log=new Logs();
        $log->branch=$branch;
        $log->terminal=$terminal;
        $log->pullNow=0;
        $log->lastFileName="";
        $status=$log->save();
        return response()->json($status);
    }


    /**
     * @param $branch
     * @param $terminal
     * @param $fileName
     * @return string
     */
    function update($branch, $terminal, $fileName)
    {
        $UpdateDetails = DB::table('logs')
            ->where('branch', $branch)
            ->where('terminal',$terminal)
            ->update(['lastFileName' => $fileName,'pullNow'=>0,'updated_at' => Carbon::now()]);
        if($UpdateDetails==1)
        {
            return 'true';
        }else{
            return 'false';
        }
    }

    /**
     * @param $branch
     * @param $terminal
     * @return bool|string
     */
    private function Directry($branch, $terminal)
    {
        $baseDirectory=base_path().'/LogUpload';
        $subDirectory1=base_path().'/LogUpload/'.$branch;
        $subDirectory2=base_path().'/LogUpload/'.$branch.'/'.$terminal;
        $status=false;

        try
        {
            if (!is_dir($baseDirectory) || !is_dir($subDirectory1) || !is_dir($subDirectory2))
            {
                if(!is_dir($baseDirectory)){
                    mkdir($baseDirectory,0777);
                    $status=true;
                }
                if(!is_dir($subDirectory1)){
                    mkdir($subDirectory1,0777);
                    $status=true;
                }
                if(!is_dir($subDirectory2)){
                    mkdir($subDirectory2,0777);
                    $status=true;
                }
            }
            else{
                $status=true;
            }
            return $status;
        }
        catch (Exception $ex)
        {
            return "Directory: ".$ex;
        }
    }

    /**
     * @param Request $request
     * branch , terminal
     * @return array
     */
    function getFileName(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=$request->input('terminal');
        $filePath=base_path().'/LogUpload/'.$branch.'/'.$terminal.'/';
        $files =  array_diff(scandir($filePath), array('.', '..'));
        $list=[];
        foreach ($files as $file)
        {
            $list[]=$file;
        }
        return $list;
    }

    /**
     * @return array
     */
    function getAllBranch()
    {
        $branch=Logs::distinct()->select('branch')->get();
        $data=[];
        foreach ($branch as $bra)
        {
            $data[]=$bra->branch;
        }
        return $data;
    }

    /**
     * @param Request $request
     * branch
     * @return array
     */
    function getAllTerminal(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=Logs::select('terminal')->where('branch','=',$branch)->get();
        $data=[];
        foreach ($terminal as $ter)
        {
            $data[]=$ter->terminal;
        }
        return $data;
    }

    /**
     * @param Request $request
     * branch , terminal
     * @return string
     */
    function pullNow(Request $request)
    {
        $branch=$request->input('branch');
        $terminal=$request->input('terminal');

        $UpdateDetails = DB::table('logs')
            ->where('branch', $branch)
            ->where('terminal',$terminal)
            ->update(['pullNow'=>1,'updated_at' => Carbon::now()]);
        if($UpdateDetails==1)
        {
            return 'true';
        }else{
            return 'false';
        }
    }

}