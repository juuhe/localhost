
function uld(type, htmls) {
    var html = '';
    var width = 500;
    if (type == 'del') {
        url = a_url.del;
        title = '删除计划';
        text = '确定要删除吗？删除后无法恢复!';
    }
    if (type == 'lock') {
        url = a_url.lock;
        html = '<span class="notification error_bg">锁定</span>';
        text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息不完整，拒绝通过！</textarea>';
        title = '锁定计划';
    
    }
    if (type == 'unlock') {
        url = a_url.unlock;
        html = '<span class="notification info_bg">活动</span>';
        text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息无误，允许通过！</textarea>';
        title = '激活计划';
       
        if (_status == 0) {
            window.location.href = a_url.status0 + _planid;
            return;
        }
    
    }
      
    box.confirm(text, width, title, function(bool) {
        var add_get = '';
        var log_text = $("#log_text").text();
        __planid = _planid.split(',');
        if (bool) {
            if (type == 'del') {
                $.each(__planid, function(i, val) {
                    $("#planid_" + val).parent().parent().css("backgroundColor", "#faa").hide('normal');
                });
            }
          
            $.ajax(
            {
                dataType: 'html',
                url: url,
                type: 'post',
                data: 'planid=' + __planid + '&log_text=' + log_text + add_get,
                success: function() 
                {
                    $.each(__planid, function(i, val) {
                        $("#planid_" + val).parent().parent().find('.status').html(html);
                    });
                    $(".success").show();
                }
            });
        }
    });
}

$("#uld_unlock,#uld_lock,#uld_del").click(function() {
    var type = $(this).attr("id").replace("uld_", "");
	_status =$(this).parent().attr("status");
	_planid =$(this).attr("planid");
    uld(type, this);
    return false;
});
$("#select_id").click(function() {
    var a = $("#select_id").attr("checked");
    if (a != 'checked')
        a = false;
    $("input[name='planid']").attr("checked", a);
});
$("#choose_sb").click(function() {
    var arr = [];
    var choose_type = $("#choose_type").val();
    if (!choose_type) {
        box.alert('批量操作请选择一个操作对像', 300);
        return;
    }
    var arrChk = $("input[name='planid']:checked");
    
    for (var i = 0; i < arrChk.length; i++) 
    {
        var v = arrChk[i].value;
        arr.push(v);
    
    }
    _planid = arr.join(",");
    uld(choose_type);
});
$('input:radio[name=gradeprice]').on('click', function(option) {
    var v = $(this).val();
    if (v == 1) {
        $("#ds_price").show("slow");
        $("#s_price").hide("slow");
    } else {
        $("#s_price").hide("slow");
        $("#ds_price").show("slow");
    }
    add_plan_vlt.init();
});


$(".get_plancode").click(function() {
    var p = $(this).attr("planid");
     text = '二次点击代码<br><br><textarea  class="input_text span70 textarea_text" name="log_text" id="log_text" style="width: 90%;height:40px"><script src="'+a_url.jumpurl+'effect.php?type=ec&pid='+p+'"></script></textarea><br>效果跟踪代码<br><br><textarea  class="input_text span70 textarea_text" name="log_text" id="log_text" style="width: 90%;height:40px"><script src="'+a_url.jumpurl+'effect.php?type=ef&pid='+p+'"></script></textarea><br>说明：<br>二次点击：检测网民到达广告页后是否有点击网页动作,简称二次点击<br>效果跟踪：跟踪广告效果如点击或是弹出后有没有注册行为或是统计广告到达量';
	
	box.confirm(text,600,'获取代码',function(bool){});
	$("#modeal_ok").hide();
});

$(".e_price").editable({
    columns: [
        {type: "hidden",name: "planid"}, 
        {type: "hidden",name: "olb_price",sun_on_v: "price"}, 
        {type: "text",name: "price",number: true,up: true,help: "修改的价格不能低于广告商的出价"}
    ],
    url: a_url.e_price,
    attr: ["price"]
});
$(".e_priceadv").editable({
    columns: [
        {type: "hidden",name: "planid"}, 
        {type: "hidden",name: "olb_priceadv",sun_on_v: "priceadv"}, 
        {type: "text",name: "priceadv",number: true,up: true}
    ],
    url: a_url.e_priceadv,
    attr: ["priceadv"]
});
$(".e_budget").editable({
    columns: [
        {type: "hidden",name: "planid"}, 
        {type: "text",name: "budget",number: true,up: true}
    ],
    url: a_url.e_budget,
    attr: ["budget"]
});

$(".e_clearing").editable({
    columns: [
        {type: "hidden",name: "planid"}, 
        {type:"select", name:"clearing",option:[
			  {value: "day", text: "日结"},
			   {value: "week", text: "周结"},
			    {value: "month", text: "月结"}
              ]},        
              
    ],
    url: a_url.e_clearing,
    attr: ["clearing"]
});


$(".e_deduction").editable({
    columns: [
        {type: "hidden",name: "planid"}, 
        {type: "text",name: "deduction",number: true,up: true,maxlength:3}      
    ],
    url: a_url.e_deduction,
    attr: ["deduction"]
});

$(".e_info").click(function() {
    var p = $(this).attr("planid");
    var s = $(this).attr("isshow");
    
    if (s == 1) {
        $("#u_info_" + p).show();
        $(this).parent().parent().css("backgroundColor", "#EEF3F8");
        $(this).attr("isshow", 2);
    } else {
        $("#u_info_" + p).hide();
        $(this).parent().parent().css("backgroundColor", "#fff");
        $(this).attr("isshow", 1);
    }
    $('#container_' + p).html(charts.loading);
    setTimeout(function() {
        $.ajax({
            dataType: 'json',
            url: charts.url,
            type: 'post',
            data: charts.data + p,
            success: function(data) {
                
                Axis(p, data.xAxis, data.series);
            }
        });
    }, 200);
    return false;
});
function Axis(id, xAxis, series) {
    $('#container_' + id).highcharts({
        chart: {
            borderWidth: 0,
            borderRadius: 2
        },
        title: {
            text: '一周趋势',
            x: -20 //center
        },
        
        xAxis: {
            categories: xAxis
        },
        yAxis: {
            title: {
                text: '流量统计'
            },
            plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
            min: 0
        },
        tooltip: {
            valueSuffix: '次'
        },
        legend: {
            borderWidth: 0,
            align: 'right',
            x: -10,
            verticalAlign: 'top',
            y: 0,
            floating: true,
            backgroundColor: '#FFFFFF',
            borderColor: '#FFFFFF'
        },
		lang: { numericSymbols: null },
        series: series
    });
}