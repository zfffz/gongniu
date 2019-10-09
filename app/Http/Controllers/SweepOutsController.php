<?php

namespace App\Http\Controllers;

use App\Models\Sweep_out_item;
use App\Models\SweepOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SweepOutsController extends CommonsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sweepOuts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //打包员
        $packagers = DB::table('bs_gn_wl')
            ->select('cpersoncode as no','cpersonname as name')
            ->where('wlcode','=','03')
            ->get();
        return view('sweepOuts.create',compact('packagers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sweep_out=\DB::transaction(function() use ($request){
            //创建一张新的打包出库单
            $sweep_out = new SweepOut([
                'packager_no'=>$request->input('packager'),
                'user_no'=>Auth::id()
            ]);
            $sweep_out->save();

            //创建一张新的项目清单
            $sweep_out_items = $request->input('items');
            $i=1;

            foreach($sweep_out_items as $data){
                // 先检查发货单号是否重复
                $jg = Sweep_out_item::where('dispatch_no','=',$data['dispatch_no'])->get();

                if(count($jg)>0){
                    echo json_encode(array('status'=>0,'text'=>'发货单号'.$jg[0]->dispatch_no.'，系统已经存在，不允许重复创建！'));
                    exit();
                }

                $sweep_out_item = $sweep_out->sweep_out_items()->make([
                    'entry_id'=>$i,
                    'dispatch_no'=> $data['dispatch_no'],
                    'location_no'=> $data['location_no']
                ]);

                $sweep_out_item->save();

                $i++;
            }

            // 更新
            $sweep_out->update(['count' => ($i-1)]);

            return $sweep_out;
        });

        return $sweep_out;
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

    public function dispatch_data(Request $request){
        // 获取默认库位编码
        $dispatch_no = $request->dispatch_no;
        $data = DB:: table('dispatchlist as t1')
            ->select('t1.cDLCode','t1.cCusCode','t3.no')
            ->leftJoin('zzz_customer_locations as t2','t1.cCusCode','=','t2.customer_no')
            ->leftJoin('zzz_storage_locations as t3','t2.location_id','=','t3.id')
            ->where('t1.cDLCode','=',$dispatch_no)->get();

        echo json_encode($data);

    }
}
