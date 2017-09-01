add_plan_vlt.init();
$("#uid").on('change', function (option) {
    var v = $(this).find("option:selected").text();
    v = v.split('￥');
    if (v[1] < 100) {
        $('#u_text').html('<font color="#FF0000">提示：当前广告商的余额低于100元</font>');
    }
});
$('input:radio[name=gradeprice]').on('click', function (option) {
 
    var v = $(this).val();
    gp(v);
});
 
 
function gp(v) {
 
    var plantype = $("#plantype").val();
    if (plantype == "cps") {
        if (v == 0) {
            $("#a_price").show("");
            $("#a_price_v").show("");
            $('#s_price_f_cps').hide("");
        }
        if (v == 1) {
            $("#a_price").hide("");
            $('#s_price_f_cps').show("");
            $("#a_price_v").hide("");
        }
        if (v == 2) {
            $("#a_price").hide("");
            $('#s_price_f_cps').hide("");
            $("#a_price_v").hide("");
        }
    } else {
        if (v == 0) {
            $("#a_price").show("");
            $("#s_price").hide("");
        } else {
            $("#a_price").hide("");
            $("#s_price").show("");
        }
    }
 
    add_plan_vlt.init();
 
}
 
$('input:radio[name=expire_date]').on('click', function (option) {
    var v = $(this).val();
    if (v == 'no') {
        $("#expire_year,#expire_month,#expire_day").removeAttr("disabled");
    } else {
        $("#expire_year,#expire_month,#expire_day").attr("disabled", "disabled");
    }
});
 
$('input:radio[name=restrictions]').on('click', function (option) {
    var v = $(this).val();
    if (v == 1) {
        $("#resuids").hide("");
    } else {
        $("#resuids").show("");
    }
    add_plan_vlt.init();
});
 
 
$('input:radio[name=sitelimit]').on('click', function (option) {
    var v = $(this).val();
    if (v == 1) {
        $("#limitsiteids").hide("");
    } else {
        $("#limitsiteids").show("");
    }
 
});
 
$(
    'input:radio[name="acl[city][isacl]"],input:radio[name="acl[siteclass][isacl]"],input:radio[name="acl[week][isacl]"],input:radio[name="acl[browser][isacl]"],input:radio[name="acl[mobile][isacl]"]')
    .on('click', function (option) {
    var v = $(this).val();
    if (v == 'all') {
        $(this).next().next().next().next().hide();
        $(this).next().next().next().hide();
    } else {
        $(this).next().next().show();
        $(this).next().next().next().show();
    }
    add_plan_vlt.init();
});
 
 
$(".region >div").bind('mouseover', function () {
    if ($(this).next(".p_city").find('li').length > 2) {
        var v = $(this).find('input').val();
        $(this).addClass("hover");
        position = $(this).position();
        poptop = position.top;
        popleft = position.left + 66;
        $(this).next().show().css("top", poptop + "px").css("left", popleft + "px");
    }
 
}).bind('mouseleave', function () {
    $(this).next().hide();
    $(this).removeClass("hover");
});
$(".region >div").next().bind('mouseover', function () {
    $(this).prev().addClass("hover");
    $(this).show();
}).bind('mouseleave', function () {
    $(this).hide();
    $(this).prev().removeClass("hover");
});
 
$(".region >div>input").click(function () {
 
    var a = $(this).attr("checked");
    a = !a ? false : true;
    $(this).parent().next().find(':input').each(function () {
 
        $(this).attr("checked", a);
    });
});
 
 
$(".city_in").click(function () {
    var a = $(this).attr("checked");
    a = !a ? false : true;
    var v = $(this).attr("id");
    id = v.split('_');
 
    if ($(".city_in:checked").length >= 1) {
        a = true;
    } else {
        a = false;
    }
 
    $("#province_" + id[1]).attr("checked", a);
});
 
 
 
$(".week").click(function () {
    var a = $(this).attr("checked");
    a = !a ? false : true;
 
 
    $(this).parent().parent().parent().find(':input:gt(0)').each(function () {
        $(this).attr("checked", a);
    });
});
 
$(".week_in").click(function () {
    var a = $(this).attr("checked");
    a = !a ? false : true;
 
    if ($(this).parent().parent().parent().find(".week_in:checked").length >= 1) {
        a = true;
    } else {
        a = false;
    }
 
    $(this).parent().parent().parent().find("input:first").attr("checked", a);
});
 
$('input:radio[name="linkon"]').on('click', function (option) {
    var v = $(this).val();
    if (v == 'y') {
        $("#linkurl_s").show();
    } else {
        $("#linkurl_s").hide();
    }
 
});
 
 
 
//tab
$('.tab-btn').on('click', function (option) {
 
    if ($("#form_b").validate().form()) {
        $('.tab-btn').removeClass("tab-state-active");
        $(this).addClass("tab-state-active");
        $('.p_tab').hide("");
        var sid = $(this).attr('id');
        $('.' + sid).show("");
        //$.cookie('s_tab',sid); 
        var t = $(this).text();
        $('.' + sid).find("h3").hide("");
        $(".tab_heading").html(t);
    }
 
});
 
$('#tab_nf').on('click', function (option) {
    if ($('.p_tab').is(":hidden")) {
        $('.p_tab').show("slow");
        $('#f_submit').show("");
        $('#f_button').hide("");
        $(this).html("选项模式")
    } else {
        $('.p_tab').hide("");
        $('.p_cg').show("");
        $('#f_submit').hide("");
        $('#f_button').show("");
        $(this).html("整页模式")
    }
});
 
 
$('#plantype').on('change', function (option) {
    var v = $(this).val();
    psh(v);
});
 
function psh(v) {
    $('.keys').hide("");
    if (v == "cps" || v == "cpa" || v == "cpas") {
        $('.keys').show("");
    }
    if (v == "cps") {
        $('#price_text1').html("固定分成");
        $('#price_text2').html("按类目分成");
        $('#label_price_text3').html("站长佣金");
        $('#label_price_text1').html("广告商佣金");
        $('#price_text3,#price_text4').html(" %");
        $('.div_cps,#price_texts').show("");
        $('#label_price_text5').html("按类提成");
        //$('#s_price_f_cps').show("");  
    } else if (v == "cpas") {
        $('#label_price_text1').html("广告商CPA单价");
        $('#label_price_text2').html("站长CPA单价分级");
        $('#label_price_text3').html("站长CPA单价");
        $('#label_price_text4').html("站长分级CPA单价");
        $('#label_price_text5').html("CPS分成");
        $('.div_cps').show("");
        $('#s_price_f_cps').show("");
    } else {
        $('#price_text1').html("不分等级");
        $('#price_text2').html("分网站等级");
 
        $('#label_price_text1').html("广告商单价");
        $('#label_price_text2').html("站长单价分级");
        $('#label_price_text3').html("站长单价");
        $('#label_price_text4').html("站长分级单价");
        $('#label_price_text5').html("按类目分成");
 
        $('#price_text3,#price_text4').html(" 元");
        $('.div_cps,#price_texts').hide("");
        $('#s_price_f_cps').hide("");
    }
}
$('#f_button').on('click', function (option) {
    if ($("#form_b").validate().form()) {
        var o = $('.tab-state-active');
        $('.tab-btn').removeClass("tab-state-active");
        var no = o.next();
        no.addClass("tab-state-active");;
        var sid = no.attr('id');
        $('.p_tab').hide("");
        $('.' + sid).show("");
        var t = no.text();
        $('.' + sid).find("h3").hide("");
        $(".tab_heading").html(t);
        if (o.attr('id') == 'p_ys') $('#f_submit').show("");
        if (o.attr('id') == 'p_dx') $('#f_button').hide("");
        return false;
    }
});
 
 
 
$(".newbtn").bind("click", function () {
    //alert($("input:checked"));
    $(".c_f_f").append(
        '<tr><td><div><input name="classprice_mark[]" type="text" class="input_text span30 classprice {validate:{ required:true,email:true }}" style="width:90px" value="" /></div></td><td><div><input name="classprice_mark_info[]" type="text" class="input_text span30 classprice" style="width:120px" value="" /></div></td><td><div><input name="classprice_aff[]" type="text" class="input_text span30 classprice" style="width:50px" value="" /> %</div></td><td><div><input name="classprice_adv[]" type="text" class="input_text span30 classprice" style="width:50px" value="" /> %</div></td><td><div><input name="classprice_memo[]" type="text" class="input_text span30 classprice" style="width:110px" value="" /><a href="javascript:;" class="delbtn"> 删</a></div> </td></tr>');
 
    $(".delbtn").bind("click", function () {
        $(this).parent().parent().parent().remove();
        add_plan_vlt.init();
    });
    add_plan_vlt.init();
});
 
 
$(".delbtn").bind("click", function () {
    $(this).parent().parent().parent().remove();
    add_plan_vlt.init();
});
 
psh($('#plantype').val());
 
$('.p_tab').show("slow");
$('#f_submit').show("");
$('#f_button').hide("");