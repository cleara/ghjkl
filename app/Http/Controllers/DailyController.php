<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Daily;
use DB;
use Illuminate\Support\Facades\Validator;

class DailyController extends Controller
{
    public function index($id)
    {
    	try{
            $dataUser = User::where('id', $id)->first();
            if($dataUser != NULL){
            $data["count"] = daily::count();
	        $daily = array();
	        $dataDailyScrum = DB::table('daily')->join('users','users.id','=','daily.id_user')
											   ->select('daily.id', 
														'users.firstname', 
														'users.lastname',
														'users.email', 
														'daily.team', 
														'daily.activity_yesterday', 
														'daily.activity_today', 
														'daily.problem_yesterday', 
														'daily.solution',
														'daily.created_at')
                                               ->where('daily.id_user','=', $id)
	                                           ->get();

	        foreach ($dataDailyScrum as $p) {
	            $item = [
	                "id"          			=> $p->id,
	                "firstname"  			=> $p->firstname,
	                "lastname"  			=> $p->lastname,
	                "team"    	  			=> $p->team,
	                "activity_yesterday"  	=> $p->activity_yesterday,
	                "activity_today"  		=> $p->activity_today,
	                "problem_yesterday"  	=> $p->problem_yesterday,
					"solution" 				=> $p->solution,
					"created_at"             =>$p->created_at,
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
	        $data["status"] = 1;
	        return response($data);

            } else {
                return response([
					'status' => 0,
					'message' => 'Data Daily Scrum tidak ditemukan'
                ]);
              }
	    } catch(\Exception $e){

			return response()->json([
				'status' => '0',
				'message' => $e->getMessage()
			]);
      	}
	}
//read
    public function getAll($limit = 10, $offset = 0){
        try{
            $data["count"] = daily::count();
	        $daily = array();
	        $dataDailyScrum = DB::table('daily')->join('users','users.id','=','daily.id_user')
											   ->select('daily.id', 
														'users.firstname', 
														'users.lastname', 
														'daily.team', 
														'daily.activity_yesterday', 
														'daily.activity_today', 
														'daily.problem_yesterday', 
														'daily.solution')
	                                           ->get();

	        foreach ($dataDailyScrum as $p) {
	            $item = [
	                "id"          				=> $p->id,
	                "firstname"  				=> $p->firstname,
	                "lastname"  				=> $p->lastname,
	                "team"    	  				=> $p->team,
	                "activity_yesterday"  		=> $p->activity_yesterday,
	                "activity_today"  			=> $p->activity_today,
	                "problem_yesterday"  		=> $p->problem_yesterday,
	                "solution" 					=> $p->solution
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
			$data["status"] = 1;
			
	        return response($data);
	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
	}
//create
    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
    			'id_user'    				=> 'required|numeric',
  				'team'						=> 'required|string',
  				'activity_yesterday'		=> 'required|string',
  				'activity_today'			=> 'required|string',
  				'problem_yesterday'			=> 'required|string',
  				'solution'					=> 'required|string',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}
    		if(User::where('id', $request->input('id_user'))->count() > 0){
						$data = new daily();
						$data->id_user 					= $request->input('id_user');
						$data->team 					= $request->input('team');
						$data->activity_yesterday 		= $request->input('activity_yesterday');
						$data->activity_today 			= $request->input('activity_today');
						$data->problem_yesterday 		= $request->input('problem_yesterday');
						$data->solution 				= $request->input('solution');
						$data->save();

		    		return response()->json([
		    			'status'	=> '1',
		    			'message'	=> 'Data Daily Scrum berhasil ditambahkan!'
		    		], 201);
    		} else {
    			return response()->json([
	                'status' => '0',
	                'message' => 'Data Daily Scrum tidak ditemukan.'
	            ]);
    		}
      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
	  }
//delete
      public function delete($id)
      {
          try{
  
              $delete = daily::where("id", $id)->delete();
              if($delete){
                return response([
                  	"status"  => 1,
                	"message"   => "Data Daily Scrum berhasil dihapus."
                ]);
              } else {
                return response([
                  	"status"  => 0,
                    "message"   => "Data Daily Scrum Gagal dihapus."
                ]);
              }
              
          } catch(\Exception $e){
              return response([
					"status"	=> 0,
					"message"   => $e->getMessage()
              ]);
          }
      }
}