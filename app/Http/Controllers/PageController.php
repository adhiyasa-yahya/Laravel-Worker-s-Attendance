<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use DB;
use App\Employe;
use App\Present;

class PageController extends Controller
{
    //

    public function index()
    {
        return view('absensi');
        
    }

    public function find_nik($nik = null)
    {
        $data = Employe::where('nik',$nik)->get();

        return response()->json($data, 200);

    }

    public function find_absen($nik = null)
    {
        $employee = Employe::where('nik',$nik)->first();

        $data = Present::where(DB::raw("date(time_in)") , '=', DB::raw("date(now())") )->where('id_employee',$employee->id)->get();

        return response()->json($data, 200);

    }

    public function absen_in($nik = null)
    {
        $employee = Employe::where('nik',$nik)->first();

        try {
            $data = new Present;
            $data->id_employee = $employee->id;
            $data->today =  now();
            $data->time_in = now();
            $data->save();

            $return = array(
                'employee' => $employee,
                'message' => 'sukses', 
            );

            return response()->json($return, 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th, 505);

        };

    }

    public function absen_out($nik = null)
    {
        $employee = Employe::where('nik',$nik)->first();
        $data = Present::where('id_employee',$employee->id)->whereNull('time_out')->first();

        try {  
            if ($data != null) {
                $data->time_out = now();
                $data->save();

                $return = array(
                    'employee' => $employee,
                    'message' => 'sukses', 
                );
    
                return response()->json($return, 200);
            }

            if ($data == null) { 
                $return = array(
                    'employee' => $employee,
                    'message' => 'notNull', 
                );
    
                return response()->json($return, 200);
            }

        } catch (\Throwable $th) {
            //throw $th;
            $return = array(
                'employee' => $employee,
                'message' => 'gagal', 
                'error' => $th->getMessage(), 
            );
            return response()->json($return, 505);

        }

    }

    public function monitoring_absen()
    {
        # code...
        return view('control'); 
    }


    public function _fetch_absens(Request $request)
    {

        # code...
        $start = $request->start;
        $limit = $request->length;
        $keyword = $request->input('search.value');

        $sort_field = $request->order[0]['column'];
        $sort = $request->order[0]['dir'];
        switch ($sort_field) {
            case 1:
                $sort_column = 'name';
                break;
 
            default:
                $sort_column = 'name';
                break;
        }


        $query = Employe::where(function ($filter) use ( $keyword)
        { 
            if (!is_null($keyword)) {
                $filter->where('name', 'like', '%'. $keyword. '%');
            }
        });

        $total = $query->count();
        $result = $query->get(); 
        foreach ($result as $key => $value) {
            $query =  Present::where('id_employee',$value->id)->where(DB::raw("date(time_in)") , '=', DB::raw("date(now())") )->first();
            
            $pri_absen =  $query->time_in != '' ? '2' : '1'; 
            $pri_hasnt_absen =  $query->time_out != '' ? '2' : '1'; 
            $has_absen =  $query->time_in != '' ? '<span class="badge badge-primary badge-pill">Hadir</span>' : '<span class="badge badge-warning badge-pill">Belum Hadir</span>'; 
            $hasnt_absen =  $query->time_out != '' ? '<span class="badge badge-secondary badge-pill">Pulang</span>':" "; 

            if ($query->time_in != '') {
                if ( $query->time_out != '') {
                    $status = '<span class="badge badge-secondary badge-pill">Pulang</span>';
                    $update = date('d-m-Y h:m:i', strtotime($query->time_out));
                }else {
                    $status = '<span class="badge badge-primary badge-pill">Hadir</span>';
                    $update = date('d-m-Y h:m:i', strtotime($query->time_in));
                }
            } else {
                    $status = '<span class="badge badge-warning badge-pill">Belum Hadir</span>';
                    $update = '';
            }
            

            $result[$key]->code_masuk =  $pri_absen ;
            $result[$key]->code_pulang =  $pri_hasnt_absen ;
            $result[$key]->masuk =  $has_absen ;
            $result[$key]->pulang =  $hasnt_absen;
            $result[$key]->status =  $status;
            $result[$key]->nama = $value->name; 
            $result[$key]->update = $update; 
        }

        $array = json_decode($result, true);
        $data =  collect($array)->sortByDesc('code_masuk')->jsonSerialize();
        
        $new_data = [];
        foreach ($data as $key => $value) {
            $new_data[] = $data[$key];
        }
 
        $collection = collect($new_data);
   

        $return = array(
            'draw' => (int) $request->draw,
            'recordsTotal' => $limit,
            'recordsFiltered' => $total,
            'data' =>  $collection
        );
        
        return response()->json($return, 200);
    }

}
