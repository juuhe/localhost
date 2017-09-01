$(".add_pay").click(function(){
	box.form("#add_pay_form","手动结算");
	
	$('input:radio[name=alone]').on('click', function(option) {   
	
	$('#username_ajax').val(1); 
	    var v = $(this).val();
        if (v == 1) {
            $("#to_username").hide("slow");
        } else {
            $("#to_username").show("slow");
        }
	});
	
	$("#post_pay_but").click(function(){
		var touser  = $('#pay_add_username_ajax').val(); 
		var alone = $('input:radio[name="alone"]:checked').val();   
		var clearingType  = $("input[type=checkbox][@name='clearingType[]'][checked]").length;
		 if(!touser && alone == 0){
			alert("请输入会员名称！");		
			return false;
		 }  
		if(clearingType<1){		
			alert("请选择结算款项");		
			return false;
		 }
		 
		  if(alone == 0){
			 $.post(a_url.remote_user,{username:touser}, function(data){	 
				if( data == "false" ){
					 document.forms["fclearing"].submit();
				 }else{
					alert('没有这个会员');
				 }
			 });
		}else {
			 document.forms["fclearing"].submit();
		}
	
	});

});

function uld(type,pay_id,m,u) {
	var html = '';
   	var width = 400;
	if (type == 'del') {
		url = a_url.del;
		title = '删除';
		text = '确定要删除吗？删除后无法恢复!';
	}
	if (type == 'one') {
	 url = a_url.post_payment;
	 var text = u+" 的佣金<font color='red'>"+m+"</font>已付，确认结算";
	 title = '已清款结算';
	} 
	if (type == 'pay') {
	 url =  a_url.post_payment;
	 var text = "确认批量结算";
	 title = '已清款结算';
	} 
	
	box.confirm(text,width,title,function(bool){ 
		 pay_id = pay_id.split(',');
		 if (bool) {
			 if (type == 'del') {
				$.each(pay_id, function(i,val){   
					$("#pay_"+val).parent().parent().css("backgroundColor", "#faa").hide('normal');
				});  
			 } 
			$.ajax(
			{
				dataType: 'html',
				url: url,
				type: 'post',
				data: 'id=' + pay_id ,
				success: function(data) 
				{
					 if(data=='ok'){
						 $.each(pay_id, function(i,val){     
								$("#pay_"+val).parent().parent().find('.status').html("已支付");
								$("#pay_"+val).parent().parent().css("backgroundColor", "#9adf8f").css("color", "#fff");
								$("#pay_"+val).parent().parent().next().hide();
	
						 }); 
						 if(send_email){
							$.getJSON(a_url.send_email+pay_id);
						 }
					 }else {
							alert("操作失败或是没有权限") 
						}
				}
			});
		}
	});
}


$("#do_excel").click(function(){
	box.form("#do_excel_html","导出数据");
	
	$("#post_do_excel_but").click(function(){
		 document.forms["down_execl_form"].submit();
	});		
});	

$(".del").click(function(){
	uld(this.className,this);
});

$("#choose_sb").click(function(){
	var arr=[];
	var choose_type = $("#choose_type").val();
	if(!choose_type){
		box.alert('批量操作请选择一个操作对像',300);
		return ;
	}
 	var arrChk=$("input[name='id']:checked"); 
     
    for (var i=0;i<arrChk.length;i++)
    {
        var v = arrChk[i].value;
		arr.push(v);
		
    }
	pay_id = arr.join(","); 
	uld(choose_type,pay_id);
});

$("#select_id").click(function(){
 	 var a = $("#select_id").attr("checked");
	 if(a!='checked') a = false;
     $("input[name='id']").attr("checked",a);
});

$(".p_info").click(function() {
    var u = $(this).attr("pid");
    var s = $(this).attr("isshow");
    if (s == 1) {
        $("#u_info_" + u).show();
        $(this).parent().parent().css("backgroundColor", "#EEF3F8");
        $(this).attr("isshow", 2);
    } else {
        $("#u_info_" + u).hide();
        $(this).parent().parent().css("backgroundColor", "#fff");
        $(this).attr("isshow", 1);
    }
    return false;
});
