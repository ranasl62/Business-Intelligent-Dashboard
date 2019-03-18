<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Log;
class RoleController extends Controller
{
    use Log;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        // $this->middleware('permission:');
    }
    public function index()
    {

        $this->log('User Role Control','Role Control','Role');

        try{
            $roles=Role::all();
            $data['title']="User Role";
            return view('userdetails.role.index',compact(['roles','data']));
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            abort(403);
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
               $data['title']="User Role";            
               $permissions=Permission::all();
               return view('userdetails.role.create',compact(['permissions','data']));
        }catch(\Exception $e){
            \Log::error($e->getMessage());
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
            $data['title']="User Role";
            $role=Role::create($request->except(['permission','_token']));
            $role->perms()->sync($request->permission);
            return redirect()->route('role.index',compact('data'))->withMessage('Role Created');

        }catch(\Excetion $e){
            \Log::error($e->getMessage());
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
        //
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
            $data['title']="User Role";
            $role=Role::find($id);
            $permissions=Permission::all();
            $role_permissions = $role->perms()->pluck('id','id')->toArray();
             return view('userdetails.role.edit',compact(['role','role_permissions','permissions','data']));
         }catch(\Exception $e){
            \Log::error($e->getMessage());
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
        try{
            $data['title']="User Role";
            $role=Role::find($id);
            $role->name=$request->name;
            $role->display_name=$request->display_name;
            $role->description=$request->description;
            $role->save();
            DB::table('permission_role')->where('role_id',$id)->delete();
            $role->perms()->sync($request->permission);
            return redirect()->route('role.index',compact('data'))->withMessage('role Updated');
        }catch(\Excetion $e){
            \Log::error($e->getMessage());
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
        try{
            $data['title']="User Role";
            DB::table("roles")->where('id',$id)->delete();
            $data['title']="User Role";            
               $roles=Role::all();
            $data['title']="User Role";
            return view('userdetails.role.index',compact(['roles','data']));
        }catch(\Exception $e){
            \Log::error($e->getmessage());
        }

    }
}
