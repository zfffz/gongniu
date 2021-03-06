<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
// use PDO;

class DispatchPrintController extends CommonsController
{
    public function index()
    {
        return view('admins.dispatchPrint.index');
    }

    public function getData(Request $request)
    {

        $builder = \DB::table('dispatchlist as t1')
            ->select(
                \DB::raw("
			t1.cDLCode, 
			convert(char(10),t1.dDate,120) as dDate,
			t2.cSTName,
			t1.cDepCode,
			t1.cCusName,
			t3.cCusAbbName,
			t4.cPsn_Name,
			t1.cMemo,
			t1.cMaker,
			t5.cSCName,
			case t6.isprint when 1 then '是' else '否' end as status,
			isnull(t6.iprintCount,0) as iprintCount
            "))
        ->leftJoin('SaleType as t2','t1.cSTCode','t2.cSTCode')
        ->leftJoin('Customer as t3','t1.cCusCode','t3.cCusCode')
        ->leftjoin('hr_hi_person as t4','t1.cPersonCode','t4.cPsn_Num')
        ->leftjoin('ShippingChoice as t5','t1.cSCCode','t5.cSCCode')
        ->leftjoin('zzz_dispatchlist_printstatus as t6','t1.cDLCode','t6.cDLCode');

        $data=parent::dataPage3($request,$this->condition($builder,$request),'asc');

        return $data;
    }

//打印发货单
    public function getPrint(Request $request)
    {
        $data = explode('|',substr($request['datas'],0,-1));
        $n=0;
        $m=1;
        foreach ($data as $cdlcode){

            $head = \DB::table('Sales_FHD_H as a')
                ->select(
                    \DB::raw("
            a.cDLCode,a.cCusCode,a.ccusabbname,a.cmemo,a.cshipaddress,
            a.cpersonname,a.cscname,a.dDate,d.ccontactname,d.cmobilephone,
            d.cofficephone,d.cssname,a.csocode,c.no,
            a.cmaker,a.cverifier,CONVERT(varchar(10), a.dcreatesystime,23) as createtime, a.dverifydate,
            '' as divid, '' as tableid, '' as pageid
            "))
                ->Join('zzz_customer_locations as b','a.cCusCode','b.customer_no')
                ->Join('zzz_storage_locations as c','b.location_id','c.id')
                ->leftJoin('Sales_FHD_T as d','a.cdlcode','d.cdlcode')
                ->where('a.cDLCode','=',$cdlcode)->get();
            ;
            $body = \DB::table('Sales_FHD_H as t1')
                ->select(
                    \DB::raw("
            ROW_NUMBER() OVER(ORDER BY t2.cInvCode) ROWNU,t4.cWhName,t2.cInvcode,t2.cInvName,t2.iQuantity,t2.isum,t3.cInvStd,t5.cComUnitName,t3.cInvDefine5
            "))
                ->Join('dispatchlists as t2', 't1.dlid','t2.dlid')
                ->Join('inventory as t3' ,'t3.cInvCode' , 't2.cInvCode')
                ->Join('Warehouse as t4' , 't4.cWhCode' , 't2.cWhCode')
                ->Join('ComputationUnit as t5' , 't5.cComunitCode' , 't3.cComUnitCode')
                ->where('t1.cDLCode','=',$cdlcode)
                ->get();

            $head[0]->divid = 'div'.$m;  //拼div的id
            $head[0]->tableid ='table'.$m;  //拼table对应的div的id
            $head[0]->pageid ='page'.$m;   //拼页脚id
            $data1[0] = $head[0];
            $count = count($body);
            if ($count>0) {
                for($i=0;$i<$count;$i++){
                    $data1[1][$i] = $body[$i];
                }
            }
            $data2[$n][0]=$data1[0];
            $data2[$n][1]=$data1[1];
            $n=$n+1;
            $m=$m+1;
        }
        //echo json_encode(array('status'=>0,'returndata'=>$data2));
        //dd($data2);
        return view('admins.dispatchPrint.print',compact('data2','n'));
        //return redirect()->route('dispatchPrint.printpage');

    }



 //    public function lgetPrint(Request $request)
 //    {
 //        $data = explode('|',substr($request['datas'],0,-1));
 //        $n=0;
 //        $m=1;
 //        $s=0;
 //        // dd($data);
 //        foreach ($data as $cdlcode){

 //                $head = \DB::table('zzz_sweep_checks as a')
 //                ->select(
 //                    \DB::raw("
 //            a.dispatch_no as cDLCode,'' as divid
 //            "))
 //                ->where('a.dispatch_no','=',$cdlcode)->get();

 //                 $body = \DB::table('zzz_sweep_check_items as t1')
 //                ->select(
 //                    \DB::raw("
 //            ROW_NUMBER() OVER(ORDER BY entry_id desc) ROWNU,t1.cinvname as cInvName,t1.iquantity as iQuantity,t1.zb
 //            "))
 //                ->leftJoin('zzz_sweep_checks as t2', 't1.parent_id','t2.id')
 //                ->where('t2.dispatch_no','=',$cdlcode)
 //                ->whereNOTNULL('t1.zb')
 //                ->get();;

 //               $arrayData = \DB::table('zzz_sweep_check_items as t1')
 //               ->select(
 //                    \DB::raw("t1.zb,'' as tableid"))
 //                ->leftJoin('zzz_sweep_checks as t2' ,'t2.id' , 't1.parent_id')
 //                ->where('t2.dispatch_no','=',$cdlcode)
 //                ->whereNOTNULL('t1.zb')
 //                ->groupby('t1.zb')
 //                ->get();

 //                // $count= DB::select('select count(t1.zb) as count from zzz_sweep_check_items as t1 left Join zzz_sweep_checks as t2 on t2.id=t1.parent_id where t2.dispatch_no = ? and t1.zb is not null  group by (t1.zb)', [$cdlcode]);

 //                $count=count($arrayData);

 //            //$head[0]->divid = $m;
 //            $data3[$n] = $head[0];
 //            // $arrayData[0]->tableid 
 //            // dd($count[0]->count);
 //            // dd($head[0]);
 //        $t=1;
 //        $data1=[];  //置空
 //       // $s=$s+($count[0]->count);
 //        //$arrayData[0]->tableid ='table'.$t;
 //        // dd($arrayData[0]->tableid);

 // // dd($arrayData);
 // foreach ($arrayData as $zb){

 //            $zb1=$zb->zb;
 //           // $data3[$n]->divid='div'.$m;
 //            // $head = \DB::table('zzz_sweep_checks as a')
 //            //     ->select(
 //            //         \DB::raw("
 //            // a.dispatch_no as cDLCode,'' as divid
 //            // "))
 //            //     ->where('a.dispatch_no','=',$cdlcode)->get();


 //                $body1 = \DB::table('zzz_sweep_check_items as t1')
 //                ->select(
 //                    \DB::raw("
 //            ROW_NUMBER() OVER(ORDER BY entry_id desc) ROWNU,t1.cinvname as cInvName,t1.iquantity as iQuantity,t1.zb
 //            "))
 //                ->leftJoin('zzz_sweep_checks as t2', 't1.parent_id','t2.id')
 //                ->where('t1.zb','=',$zb1)
 //                ->where('t2.dispatch_no','=',$cdlcode)
 //                ->whereNOTNULL('t1.zb')
 //                ->get();                  

 //            $count = count($body1);

 //            $data1[0]=array('divid'=>'div'.$t);



 //            if ($count>0) {
 //                for($i=0;$i<$count;$i++){
 //                    $data1[$t][$i] = $body1[$i];    //$data1[1] 子表
 //                }

 //            }
 //            //dd($data1);
         
 //            // $head[0]->divid = $m;
 //            // $data1[0] = $head[0];    //$data1[0] 主表
 //            // $data2[$t][0]=$data1[0];  //主表
 //            // $data2[$t][1]=$data1[$t];  //子表
 //         //   $head[0]->divid = $m;
 //         //   $data1[0] = $head[0];    //$data1[0] 主表
 //           // $data2[$n][$t]=$data1[$t][$tableid];  //主表
          
 //             //i是分组下的明细循环
 //             //t是分组循环
 //             //n是发货单循环
 //           $data2[$n]=$data1[0];
 //           $data2[$n][$t+1]=$data1[$t]; 
 //           // print_r($tableid);
 //             $t=$t+1;
 //              // $data5[$t] = $arrayData[$t-1]->tableid ;
 //        }
 

 //        //  $data3[$n][0]=$data2[$n];  //主表

 //        //  $data3[$n][1]=$data2[$n];  //子表
             
 //            $data4[$n][0]=$data3[$n];    //主表
 //            // $data4[$n][1]=$data5[$t-1]; 

 //            $data4[$n][1]=$data2[$n];   //子表


          
     
 //            // $s=$s+1;
 //            $n=$n+1;
 //            $m=$m+1;
 //        }
 //        //echo json_encode(array('status'=>0,'returndata'=>$data2));
 //        dd($data4);
 //        // dd(t);
 //         // dd($s);
       
 //         // dd( $s);
 //        return view('admins.dispatchPrint.lable',compact('data4','s'));
 //        //return redirect()->route('dispatchPrint.printpage');

 //    }


//测试
        public function lgetPrint(Request $request)
    {
        $data = explode('|',substr($request['datas'],0,-1));
        $n=1;
        $s=0;
        $t=1;
        $m=0;
        // dd($data);
        foreach ($data as $cdlcode){

                $head = \DB::table('zzz_sweep_checks as t1')
                ->select(
                    \DB::raw("
            t1.dispatch_no ,t2.zb,'' as divid
            "))
                ->leftJoin('zzz_sweep_check_items as t2', 't2.parent_id','t1.id')
                ->where('t1.dispatch_no','=',$cdlcode)
                ->whereNOTNULL('t2.zb')
                ->groupby('t1.dispatch_no')
                ->groupby('t2.zb')
                ->get();

                

 foreach ($head as $zb){
         

                $body1 = \DB::table('zzz_sweep_check_items as t1')
                ->select(
                    \DB::raw("
            ROW_NUMBER() OVER(ORDER BY entry_id desc) ROWNU,t1.cinvname as cInvName,t1.iquantity as iQuantity,t1.zb
            "))
                ->leftJoin('zzz_sweep_checks as t2', 't1.parent_id','t2.id')
                ->where('t1.zb','=',$zb->zb)
                ->where('t2.dispatch_no','=',$zb->dispatch_no)
                ->whereNOTNULL('t1.zb')
                ->get();                  

        
            $count=count($body1);
            if ($count>0) {
                for($i=0;$i<$count;$i++){
                    $data1[$t][$i] = $body1[$i];    //$data1[1] 子表
                }

            }

            $zb->divid='div'.$t;

           $data2[$m][0]=$zb;
// if ($n>1) {
// $n=$n-1;
//      }
//             for($t=1;$t<$count;$t++){
           $data2[$m][1]=$data1[$t]; 
           $t=$t+1;
           $m=$m+1;
        }
           $n=$n+1;
        }
        //echo json_encode(array('status'=>0,'returndata'=>$data2));
        // dd($data2);
        // dd(t);
         // dd($s);
       
        return view('admins.dispatchPrint.lable',compact('data2','m'));
        //return redirect()->route('dispatchPrint.printpage');

    }


//    public function printpage(request $request){
//        $data2 = $request->all();
//        return view('admins.dispatchPrint.print',compact('data2'));
//    }


     //打印外箱箱标
    public function outboxPrint(Request $request)
    {
        $data = explode('|',substr($request['datas'],0,-1));
        $n=0;
        $m=1;
        foreach ($data as $cdlcode){

            $head = \DB::table('Sales_FHD_H as a')
                ->select(
                    \DB::raw("
            a.cDLCode,a.ccusabbname,a.cshipaddress,
            b.CTNS,'' as divid
            "))
                ->Join('zzz_sweep_checks as b','a.cDLCode','b.dispatch_no')
                ->where('a.cDLCode','=',$cdlcode)->get();
            ;

            $head[0]->divid = 'div'.$m;

            $data1[$n] = $head[0];
            $n=$n+1;
            $m=$m+1;
        }
        //dd($data1);
        //echo json_encode(array('status'=>0,'returndata'=>$data2));
        return view('admins.dispatchPrint.outboxprint',compact('data1','n'));

    }


    //更新发货单打印次数和打印状态
    public function updPrintstatus(Request $request)
    {
        $updcdlcode = $request->input('items');
        foreach($updcdlcode as $data){
          //  $time=date('Y-m-d h:i:s', time());
            DB::beginTransaction();
            try{
                //更新发货单打印次数
                $query1 = \DB::table('dispatchlist')
                    ->select(
                        \DB::raw("isnull(iPrintCount,0) as iPrintCount
            "))
                    ->where('cDLCode','=',$data['cdlcode'])->get();

                $jg1=DB::table('DispatchList')
                    ->where('cdlcode','=',$data['cdlcode'])
                    ->update(
                        [
                            'iPrintCount'=>$query1[0]->iPrintCount + 1,
                        ]
                    );

                //插入发货单打印日志zzz_print_diary
                $jg2=DB::table('zzz_print_diary')->insert(
                    [
                        'FBillNo'=>$data['cdlcode'],
                        'FCreateTime'=>date('Y-m-d h:i:s', time()),
                        'FCreateUserID'=>$request->user()->id
                    ]
                );

                //插入u8打印日志
                $jg3=DB::table('PrintPolicy_VCH')->insert(
                    [
                        'PolicyID'=>'01_131460',
                        'lastPrintTime'=>date('Y-m-d h:i:s', time()),
                        'VchID'=>$data['cdlcode'],
                        'VchUniqueID'=>$data['cdlcode'],
                        'Total'=>'1'
                    ]
                );

                if (!$jg1) {
                    throw new \Exception("2");
                }
                if (!$jg2) {
                    throw new \Exception("3");
                }
                if (!$jg3) {
                    throw new \Exception("4");
                }
                DB::commit();
                echo json_encode(array("FTranType"=>1,"FText"=>'打印更新成功！'),JSON_UNESCAPED_UNICODE);
            }catch(\Exception $e){
                DB::rollback();//事务回滚
                echo $e->getMessage();
                echo json_encode(array("FTranType"=>0,"FText"=>'数据异常！'),JSON_UNESCAPED_UNICODE);
            }
            //将打印信息写入日志

        }
    }

    private function condition($table,$searchKey){

        $bedate = explode(" - ",$searchKey->dateKey);
        $bgdate = $bedate[0];
        $eddate = date("Y-m-d",strtotime("+1day",strtotime($bedate[1])));
        //dd($searchKey);
        if($searchKey!=''){

               $table->where('t1.dDate','>=',$bgdate);
               $table->where('t1.dDate','<=',$eddate);

            if($searchKey->cSTcodeKey!='' || $searchKey->cSTcodeKey!=null ){
                $table->where('t1.cSTCode','=',$searchKey->cSTcodeKey);
            }

            if($searchKey->cDLCodeKey!='' || $searchKey->cDLCodeKey!=null ){
                $table->where('t1.cDLCode','=',$searchKey->cDLCodeKey);
            }

            if($searchKey->cDepartmentKey!='' || $searchKey->cDepartmentKey!=null ){
                $table->where('t1.cDepCode','=',$searchKey->cDepartmentKey);
            }

            if($searchKey->status =='1' ){
                $table->where('t6.isprint ','=','1');
            }

            if($searchKey->status =='0' ){
                $table->where(function($query){
                    $query->whereNull('t1.iPrintCount ')
                          ->orwhere('t1.iPrintCount','=','0');
                });

            }

        }

        return $table;
    }
}
