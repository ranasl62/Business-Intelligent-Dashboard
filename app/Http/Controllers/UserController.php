<?php

namespace App\Http\Controllers;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Log;
use Auth;
class UserController extends Controller
{
    use Log;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('Admin-Permission'))abort(403);
        $this->log('User Control','User Control ','User');

        try{
            $users    = User::all();
            $allRoles = Role::all();
            $data['title']='User Management';
            return view('userdetails.user.index',compact(['users','allRoles','data']));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if(!Auth::user()->can('Admin-Permission'))abort(403);
            $allRoles = Role::all();
            return view('userdetails.user.create',compact('allRoles'));
        }catch(\Exception $e){
            Log::error($e->getmessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if(!Auth::user()->can('Admin-Permission'))abort(403);
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect('user/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            $user= User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ]);
            foreach ($request->roles as $role){
                $user->attachRole($role);
            }
            // $user->attachRole(Role::where('name','general-user')->first());
            return redirect('user')->withMessage('User Created');
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return "Show ".$id;
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            if(Auth::user()->can('Admin-Permission'))
                $allRoles = Role::all();
            else{ 
                $allRoles =  User::find($id);
                $allRoles = $allRoles->roles;
            }
           $data['title']='User Management';
        //    $roles=Role::all();
           $user=User::find($id);
           return view('userdetails.user.edit',compact(['user','allRoles','data']));
       }catch(\Exception $e){
        \Log::error($e->getMessage());
        dd("Error");
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info='';
        try{   

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect('user/'.Auth::user()->id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            $user = User::find($id);
            if( Auth::user()->can('Admin-Permission') || Auth::user()->id==$id){
                $user->password= bcrypt($request['password']);
                $user->name=$request['name'];
                if(!User::where('email',$request->email)->first()){
                    $user->email=$request['email'];
                }else {
                    $info.='This email: '.$request['email'].' already exist';
                }
                $user->save();
            }else{
                abort(403);
            }
            if(Auth::user()->can('Admin-Permission')){
                $user=User::find($id);
                $roles=$request->roles;
                DB::table('role_user')->where('user_id',$id)->delete();            
                foreach ($roles as $role){
                    $user->attachRole($role);
                }
            }
            $info.='\n Updated user details';
            $error="failed to update";
            return redirect('user/'.Auth::user()->id.'/edit')->with(['info'=>$info]);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return redirect('user/'.Auth::user()->id.'/edit')->with(['error'=>$info]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()->can('adminAdmin-Permission'))abort(403);

         try{
            DB::table("users")->where('id',$id)->delete();
            return back()->withMessage('Role Deleted');
        }catch(\Exception $e){
            Log::error($e->getmessage());
        }
    }
}
