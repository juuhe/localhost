function uld(type,htmls) {
	var html = '';
   	var width = 500;
	if (type == 'del') {
		url = a_url.del;
		title = '删除广告';
		text = '确定要删除吗？删除后无法恢复!';
	}
	if (type == 'lock') {
		url = a_url.lock;
		html = '<span class="notification error_bg">锁定</span>';
	    text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息不完整，拒绝通过！</textarea>';
		title = '锁定广告';
	
	}
	if (type == 'unlock') {
		url = a_url.unlock;
		html = '<span class="notification info_bg">活动</span>';
	    text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息无误，允许通过！</textarea>';
		title = '激活广告';
			
	}
	
	
			 
	box.confirm(text,width,title,function(bool){ 
		  var add_get = ''; 
		  var log_text = $("#log_text").text(); 
		 __adsid = _adsid.split(','); 
		 if (bool) {
			 if (type == 'del') {
				$.each(__adsid, function(i,val){   
					$("#adsid_"+val).parent().parent().css("backgroundColor", "#faa").hide('normal');
				});  
			 } 
			 
			$.ajax(
			{
				dataType: 'html',
				url: url,
				type: 'post',
				data: 'adsid=' + __adsid  + '&log_text=' + log_text+ add_get,
				success: function() 
				{
					 $.each(__adsid, function(i,val){   
						 $("#adsid_"+val).parent().parent().find('.status').html(html);
					 });   
					$(".success").show();
				}
			});
		}
	});
}

 
 
$("#select_id").click(function(){
 	 var a = $("#select_id").attr("checked");
	 if(a!='checked') a = false;
     $("input[name='adsid']").attr("checked",a);
});

$("#choose_sb").click(function(){
	var arr=[];
	var choose_type = $("#choose_type").val();
	if(!choose_type){
		box.alert('批量操作请选择一个操作对像',300);
		return ;
	}
 	var arrChk=$("input[name='adsid']:checked"); 
     
    for (var i=0;i<arrChk.length;i++)
    {
        var v = arrChk[i].value;
		arr.push(v);
		
    }
	_adsid = arr.join(",");
	uld(choose_type);
});

$("#uld_unlock,#uld_lock,#uld_del").click(function() {
    var type = $(this).attr("id").replace("uld_", "");
	_adsid =$(this).attr("adsid");
    uld(type, this);
    return false;
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
 

$(".e_info").click(function() {
    var p = $(this).attr("adsid");
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
     
    setTimeout(function() {
        $.ajax({
            dataType: 'html',
            url: a_url.view+p ,
            type: 'get',
            success: function(data) {
				 if(!data) data = "<p style='margin-top:50px;'>无预览</p>";
                 $('#view_' + p).html(data);
            }
        });
    }, 200);
    return false;
});
 
 $(".e_adname").editable({
    columns: [
        {type: "hidden",name: "adsid"}, 
        {type: "text",name: "adname",up: true}
    ],
    url: a_url.e_adname,
    attr: ["adname"]
});

 
$(".e_priority").editable({
    columns: [
        {type: "hidden",name: "adsid"}, 
        {type: "text",name: "priority",up: true,help: "输入一个1~10数字"}
    ],
    url: a_url.e_priority,
    attr: ["priority"]
});

 $(".implant_zone").click(function(){
		var  adsid =$(this).attr("adsid");
	    $.ajax(
			{
				dataType: 'html',
				url: a_url.implant_zone,
				type: 'post',
				data: 'adsid=' + adsid ,
				success: function() 
				{
					$(".success").show();
				}
		 });
});
 