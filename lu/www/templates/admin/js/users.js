
$(".e_money").editable({
    columns: [
        {type: "hidden",name: "uid"}, 
        {type: "hidden",name: "moneytype"}, 
        {type: "hidden",name: "olb_money",sun_on_v: "money"}, 
        {title: "金额",type: "text",name: "money",number: true,up: true}, 
        {title: "说明",type: "text",name: "payinfo"}
    ],
    url: a_url.e_money,
    attr: ["money"]

});

$(".e_deduction").editable({
    columns: e_deduction_columns,
    url: a_url.e_deduction,
    attr: deduction_attr
});

$(".e_group").editable({
    columns: e_group_columns,
    url: a_url.e_group,
    attr: ["groupid"]
});

$("#uld_unlock,#uld_lock,#uld_del").click(function() {
    var type = $(this).attr("id").replace("uld_", "");
    uld(type, this);
    return false;
});

function uld(type, e) {
    var html = '';
    var width = 500;
    if (type == 'del') {
        url = a_url.del;
        title = '删除会员';
        text = '确定要删除吗？删除后无法恢复!';
    }
    if (type == 'lock') {
        url = a_url.lock;
        html = '<span class="notification error_bg">锁定</span>';
        text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息不完整，拒绝通过！</textarea>';
        title = '锁定会员';
    }
    if (type == 'unlock') {
        url = a_url.unlock;
        html = '<span class="notification info_bg">活动</span>';
        text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">信息无误，允许通过！</textarea>';
        title = '激活会员';
    }
    
    
    
    box.confirm(text, width, title, function(bool) {
        var log_text = $("#log_text").text();
        if (e)
            uids = $(e).attr("uid");
        _uids = uids.split(',');
        if (bool) {
            if (type == 'del') {
                $.each(_uids, function(i, val) {
                    $("#uid_" + val).parent().parent().css("backgroundColor", "#faa").hide('normal');
                });
            } else {
            
            }
            $.ajax(
            {
                dataType: 'html',
                url: url,
                type: 'post',
                data: 'uid=' + _uids + '&log_text=' + log_text,
                success: function() 
                {
                    $.each(_uids, function(i, val) {
                        $("#uid_" + val).parent().parent().find('.status').html(html);
                    });
                    $(".success").show();
                }
            });
        }
    });
}

$("#select_id").click(function() {
    var a = $("#select_id").attr("checked");
    if (a != 'checked')
        a = false;
    $("input[name='uid']").attr("checked", a);
});

$("#choose_sb").click(function() {
    var arr = [];
    var choose_type = $("#choose_type").val();
    if (!choose_type) {
        box.alert('批量操作请选择一个操作对像', 300);
        return;
    }
    var arrChk = $("input[name='uid']:checked");
    
    for (var i = 0; i < arrChk.length; i++) 
    {
        var v = arrChk[i].value;
        arr.push(v);
    
    }
    uids = arr.join(",");
    uld(choose_type);
});


$(".e_info").click(function() {
    var u = $(this).attr("uid");
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


