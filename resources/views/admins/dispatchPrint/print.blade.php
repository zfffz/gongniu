@extends('admins.layouts.app')

@section('include')

@endsection

@section('title', '打印上海公牛发货单')
<script src="/js/LodopFuncs.js"></script>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>

@section('section')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-3">
                    <h4>打印</h4>
                    <button type="button" id="btn-submit" class="btn btn-info btn-xs"><i class="fa fa-trash-o fa-print"></i>打印</button>
                    <input id="printstatus" type="hidden" />
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card" id="card">
            <style>table,th{border:none;height:18px} td{border: 1px solid #000;height:18px}</style>
            <input class="col-md-1" type="hidden" id="count" name="count" value={{$n}} />
            @foreach($data2 as $datas )
           <div class="card-body" >
               <div id={{$datas[0]->divid}} >
                  <div class="row">
                      <input class="col-md-2" type="hidden" id="cdlcode" value = {{ $datas[0]->cDLCode}} />
                      <span class="col-md-10 text-center"><h3 style="font-family:黑体; font-size:25px">上海公牛配货单</h3></span>
                      <p class="col-md-10 text-center"  style="font-family:黑体; font-size:11pt; line-height:4px ">地址、电话:上海市春中路368号 60899198</p>
                  </div>
                  <div class="row"  >
                      <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">发货单号:{{ $datas[0]->cDLCode}}</h5>
                      <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">发货日期:{{ $datas[0]->dDate }}</h5>
                      <h5 class="col-md-2 col-sm-2 col-xs-2" style="font-family:黑体; font-size:11pt; line-height:9pt">客户编码:{{ $datas[0]->cCusCode }}</h5>
                      <h5 class="col-md-2 col-sm-2 col-xs-2" style="font-family:黑体; font-size:11pt; line-height:9pt">区域仓库:{{ $datas[0]->no }}</h5>
                      <h5 class="col-md-5 col-sm-5 col-xs-5" style="font-family:黑体; font-size:11pt; line-height:9pt">客户简称:{{ $datas[0]->ccusabbname }}</h5>
                      <h5 class="col-md-7 col-sm-7 col-xs-7" style="font-family:黑体; font-size:11pt; line-height:9pt">收货地址:{{ $datas[0]->cshipaddress }}</h5>
                      <h5 class="col-md-4 col-sm-4 col-xs-4" style="font-family:黑体; font-size:11pt; line-height:9pt">联系人:{{ $datas[0]->ccontactname }}</h5>
                      <h5 class="col-md-4 col-sm-4 col-xs-4" style="font-family:黑体; font-size:11pt; line-height:9pt">手机:{{ $datas[0]->cmobilephone }}</h5>
                      <h5 class="col-md-4 col-sm-4 col-xs-4" style="font-family:黑体; font-size:11pt; line-height:9pt">电话:{{ $datas[0]->cofficephone }}</h5>
                      <h5 class="col-md-3 col-sm-2 col-xs-2" style="font-family:黑体; font-size:11pt; line-height:9pt">业务员:{{ $datas[0]->cpersonname }}</h5>
                      <h5 class="col-md-3 col-sm-2 col-xs-2" style="font-family:黑体; font-size:11pt; line-height:9pt">发运方式:{{ $datas[0]->cscname }}</h5>
                      <h5 class="col-md-3 col-sm-2 col-xs-2" style="font-family:黑体; font-size:11pt; line-height:9pt">结算方式:{{ $datas[0]->cssname }}</h5>
                      <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">订单号:{{ $datas[0]->csocode }}</h5>
                      <h5 class="col-md-12 col-sm-12 col-xs-12" style="font-family:黑体; font-size:11pt; line-height:9pt">备注:{{ $datas[0]->cmemo }}</h5>
                  </div>
               </div>
               <div  id={{$datas[0]->tableid}} >
                <table class="table table-hover table-bordered" style="width: 100%;border: 1px solid black">
                    <tbody>
                    <tr>
                        <th style="font-family:黑体; font-size:11pt">行</th>
                        {{--<th style="font-family:黑体; font-size:11pt">仓库</th>--}}
                        {{--<th style="font-family:黑体; font-size:11pt">存货编码</th>--}}
                        <th style="font-family:黑体; font-size:11pt">存货名称</th>
                        <th style="font-family:黑体; font-size:11pt">规格</th>
                        <th style="font-family:黑体; font-size:11pt">单位</th>
                        <th style="font-family:黑体; font-size:11pt">数量</th>
                        <th style="font-family:黑体; font-size:11pt">金额</th>
                        <th style="font-family:黑体; font-size:11pt">条码</th>
                    </tr>
                    @foreach ($datas[1] as $dats)
                        <tr>
                            <td width="7%" style="font-family:黑体; font-size:11pt">{{ $dats->ROWNU }}</td>
                            <td width="30%" style="font-family:黑体; font-size:11pt">{{ $dats->cInvName }}</td>
                            <td width="10%" style="font-family:黑体; font-size:11pt">{{ $dats->cInvStd }}</td>
                            <td width="7%" style="font-family:黑体; font-size:11pt">{{ $dats->cComUnitName }}</td>
                            <td width="10%" style="font-family:黑体; font-size:11pt">{{ ($dats->iQuantity*1) }}</td>
                            <td width="10%" style="font-family:黑体; font-size:11pt">{{ ($dats->isum) }}</td>
                            <td width="26%" style="font-family:黑体; font-size:11pt">{{ $dats->cInvDefine5 }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td width="7%" colspan="1" style="font-family:黑体; font-size:11pt">合计</td>
                        <td width="30%" colspan="1"></td>
                        <td width="10%" colspan="1"></td>
                        <td width="7%" colspan="1"></td>
                        <td width="10%" style="font-family:黑体; font-size:11pt" ><font  tdata="Sum" format="#,##0.00" tindex="5" >######</font></td>
                        <td width="10%" style="font-family:黑体; font-size:11pt" ><font  tdata="Sum" format="#,##0.00" tindex="6" >######</font></td>
                        <td width="26%" colspan="1"></td>
                    </tr>
                    </tbody>
                </table>
               </div>
               <div  id={{$datas[0]->pageid}}>
                 <div class="row" >
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">制单人:{{ $datas[0]->cmaker }}</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">制单时间:{{ $datas[0]->createtime }}</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">审核人:{{ $datas[0]->cverifier }}</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">审核日期:{{ $datas[0]->dverifydate }}</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">配货员签字：</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">对货员签字：</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">打包员签字：</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">客户签字:</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">白联:留存</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">红联:仓库</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">蓝联:回单</h5>
                   <h5 class="col-md-3 col-sm-3 col-xs-3" style="font-family:黑体; font-size:11pt; line-height:9pt">黄联:客户</h5>
               </div>
               </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    <!-- /.col -->
@endsection

@section('script')
    <script>
        $('#btn-submit').on('click', function(){
            LODOP=getLodop();
            LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_无边线表格");
            LODOP.SET_PRINT_PAGESIZE(1,2400,1390,'');//定义纸张
            LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);//横向时的正向显示
            LODOP.SET_PRINT_MODE("AUTO_CLOSE_PREWINDOW",1);//打印后自动关闭预览窗口
            var strBodyStyle = "<link href=\"http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css\" rel=\"stylesheet\"><style> .card{color: black}.table-bordered table,.table-bordered tbody tr th,.table-bordered tbody tr td{border: 1px solid  black; color: black/* 整体表格边框 */}</style>";
            //LODOP.ADD_PRINT_TABLE(50,10,"50%",220,document.getElementById("card").innerHTML);
            //LODOP.SET_PRINT_STYLEA(0,"Top2Offset",-40); //这句可让次页起点向上移
          //  LODOP.ADD_PRINT_BARCODE(Top,Left,Width,Height,QRCode,'$datas[0]->cDLCode');
            var m= $('#count').val();
            for(var j=1;j<=m;j++){
               var divid = 'div'+j;
               var tableid = 'table'+j;
               var pageid ='page'+j;
                var cdlcode = $("#"+divid+ " input[id='cdlcode']").val();
                var printtime = new Date();
                LODOP.ADD_PRINT_HTM(5, 5, '97%', '100%',strBodyStyle+"<body>"+document.getElementById(divid).innerHTML+"</body>");
                LODOP.ADD_PRINT_TABLE(220,5, '97%', 'BottomMargin:9mm',strBodyStyle+"<body>"+document.getElementById(tableid).innerHTML+"</body>");
                LODOP.SET_PRINT_STYLEA(0,"Offset2Top",-215); //设置次页开始的上边距偏移量，解决table第二页不顶格的问题
                LODOP.ADD_PRINT_HTM(5, 5, '97%', '100%',strBodyStyle+"<body>"+document.getElementById(pageid).innerHTML+"</body>");
                LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);//以上内容紧跟在前一个对象之后

              //  LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
        //        LODOP.ADD_PRINT_HTM('12cm', 5, '97%', '100%',strBodyStyle+"<body>"+document.getElementById(pageid).innerHTML+"</body>");
                LODOP.ADD_PRINT_HTM('12.5cm',5,300,'8mm',"<font style='font-size:10pt' format='Num'><span tdata='pageNO'>第##页</span>/<span tdata='pageCount'>共##页</span></font>"); //打印页码
                LODOP.SET_PRINT_STYLEA(0,"ItemType",1);//设置上面的为页眉页脚，每页固定位置输出
           //     LODOP.SET_PRINT_STYLEA(0,"LinkedItem",1);
                LODOP.ADD_PRINT_BARCODE(5,750,80, 80, 'QRCode', cdlcode);  //打印发货单二维码
                LODOP.ADD_PRINT_TEXT(5,5,'50mm','5mm',printtime.toLocaleString( ));
                LODOP.NewPageA();  //自动分页
               // LODOP.ADD_PRINT_HTM(5, 5, '97%', '100%',strBodyStyle+"<body>"+document.getElementById("div1").innerHTML+"</body>");
            }

            if (LODOP.CVERSION) CLODOP.On_Return=function(TaskID,Value){
                document.getElementById('printstatus').value=Value;
                if (document.getElementById('printstatus').value >0){
                    $('#printstatus').change();
                }
            };
            LODOP.PREVIEW();

        });

        $('#printstatus').change(function(){
            var printcount= $('#printstatus').val();
            if (printcount > 0) {
                var m= $('#count').val();
                var datas ={};
                datas.items = {};
                for(var j=1;j<=m;j++) {
                    var divid = 'div' + j;
                    var cdlcode = $("#" + divid + " input[id='cdlcode']").val();
                    datas.items[j-1]={};
                    datas.items[j-1].cdlcode = cdlcode;
                }

                $.ajax({
                    url:"{{route('dispatchPrint.updPrintstatus')}}",
                    data:JSON.stringify(datas),
                    type:'post',
                    dataType:'json',
                    headers:{
                        Accept:"application/json",
                        "Content-Type":"application/json",
                        'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                    },
                    processData:false,
                    cache:false,
                    timeout: 1000,
                    success:function(t){
                        //插入成功
                        if (t.FTranType ==0 ){//这里的FTranType对应后台数组的FTranType，判断要用“==”
                            alert(t.FText);   //t.FTranType ==0 插入失败，可能是发货单号不存在等原因
                            //插入失败，则添加插入失败的提示音（判断t.FText)
                        }
                    },
                    error:function(){
                        //系统错误，有可能是后台php语法错误，sql语句运行错误等
                        alert("error");
                        //disLoad();
                    }
                });

            }
        });


    </script>
@endsection