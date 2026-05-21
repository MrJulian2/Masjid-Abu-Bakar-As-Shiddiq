<?php

namespace App\Http\Controllers\Admin\Admin_Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AdminusersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }
 

    public function index()
    {
       
        $users = User::all();     
        return view('admin.Admin_Users.list_admin',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Admin_Users.add_admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'username' => 'required|string|alpha_dash|min:6|max:100|unique:users',
            'email' => ' required|email|string|max:100|unique:users',
            'password'=> ['required','min:6','confirmed', Rules\Password::defaults()],
            'role'=> 'in:admin,ketua,wakil,bendahara,sekretaris,panitia_qurban,event_admin',
        ],[
            'nama.required'=> 'Nama Harus Di Isi',
            'nama.min' => 'Nama Minimal 3 Huruf',
            'nama.max'=> 'Nama Maksimal 100 Huruf',
            'username.required'=>'Username Harus Di isi',
            'username.min'=>'Username Harus Minimal 6',
            'username.alpha_dash' => 'Hanya Boleh Huruf,Nomor, _ , -',
            'username.unique'=>'Username Sudah Ada Silahkan Membuat Username Lain',
            'email.required'=>'Email Harus Di Isi',
            'email.email'=>'Masukkan Email Dengan Benar @... ',
            'email.unique'=>'Email Sudah Sudah Ada',
            'password.required'=>'Password Harus Di Isi',
            'password.min'=>'Password Minimal 6 Huruf',
            'password.confirmed'=>'Password Konfirmasi Tidak Cocok ',
            'role.in' => 'Role Harus Di  '
        ]);

        // $role = [
        //     'role' => ['admin', 'ketua','wakil','bendahara','sekretaris'],
        // ];
        // Validator::make($role, [
        //     'role' => [
        //         'required',
        //         'array',
        //         Rule::in(['admin', 'ketua','wakil','bendahara','sekretaris']),
        //     ],
        // ]);
        $user = User::create([
            'name' => $request->nama,
            'username'=>$request->username,
            'email'=> $request->email,
            'password'=>Hash::make($request->password),
            'role'=> $request->role,
        ]);
        $user->save();
        Alert::success('Tambah Data', 'Data Berhasil Di Tambah');
        return redirect('/admin/users');

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
        $edit = User::find($id);
        return view('admin.Admin_Users.edit_admin',compact('edit'));
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
    //   dd($request);
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'username' => 'required|string|min:6|max:100|unique:users,username,'.$id,
            'email' => ' required|email|string|max:100|unique:users,email,'.$id,
            'role' => 'in:admin,ketua,wakil,bendahara,sekretaris',
        ], [
            'nama.required' => 'Nama Harus Di Isi',
            'nama.min' => 'Nama Minimal 3 Huruf',
            'nama.max' => 'Nama Maksimal 100 Huruf',
            'username.required' => 'Username Harus Di isi',
            'username.min' => 'Username Harus Minimal 6',
            'username.unique' => 'Username Sudah Ada Silahkan Membuat Username Lain',
            'email.required' => 'Email Harus Di Isi',
            'email.email' => 'Masukkan Email Dengan Benar @... ',
            'email.unique' => 'Email Sudah Sudah Ada',
            'role.in' => 'Role Harus Di  '
        ]);
      
        User::find($id)->update($request->all());
        Alert::success('Edit Data', 'Data Berhasil Di Edit');
        return redirect('/admin/users')->with('edit', 'Data Berhasil di Edit');
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
       $delete = User::find($id);
       $delete->delete();
       Alert::success('Menghapus Data', 'Data Berhasil Di Hapus');
        return redirect('/admin/users');
        
    }
}
