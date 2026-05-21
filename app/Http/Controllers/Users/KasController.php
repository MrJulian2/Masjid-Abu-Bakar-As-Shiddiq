<?php

namespace App\Http\Controllers\users;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\kas_masjid;
use App\Models\saldo;
use App\Models\photoGalery;
use App\Models\event;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $semuaSaldo = kas_masjid::all();
        // $startDate = date('2021-9-3');
        // $endDate = date('2021-8-27');

      
        $saldoMinggulalu = saldo::whereBetween('tanggal', [Carbon::now()->subDay(7)->format('Y-m-d'), Carbon::now()->format('Y-m-d')])->orderBy('tanggal','asc')->first();
        if (!$saldoMinggulalu) {
            $saldoMinggulalu = (array)$saldoMinggulalu;
            $saldoMinggulalu['saldo'] = 0;
            $saldoMinggulalu = (object)$saldoMinggulalu;
        }
        // $saldoMinggulalu = saldo::latest()->first();
        $kasWeek = kas_masjid::whereBetween('tanggal', [Carbon::now()->subDay(7)->format('Y-m-d'), Carbon::now()->subDay(1)->format('Y-m-d')])->orderBy('tanggal','asc')->get();
        // dd($saldoMinggulalu);
       
        // $kasWeek = kas_masjid::whereDay('tanggal','>' ,Carbon::now())->orderBy('tanggal','asc')->get();

        $photo = photoGalery::paginate(3);


        $event = event::orderBy('tanggal','asc')->paginate(4);
        // $event = event::where('tanggal' ,'', Carbon::now() )->paginate(4);
        
        
        $upcomingEvent = event::where('tanggal' ,'>', Carbon::now() )->orderby('tanggal','asc')->paginate(3);
        // dd($upcomingEvent);


        return view('User.PartialsUser.home',compact('kasWeek','saldoMinggulalu','photo','event','upcomingEvent'));




    }


    public function showEvent($id){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
