var  editable = {		
		width: 400,
		height: 100,
		o:{},
		element_data:[],
		data_type:'text',
		post:{},
		msg:{},
		ie6:/MSIE 6\.0/i.test(window.navigator.userAgent) && !/MSIE 7\.0/i.test(window.navigator.userAgent),
		 
		run:function(o){
			editable.o = o;
			editable.element_data= o.element_data;
			editable.data_type = o.obj.attr("data-type");
			editable.submits = o.submits;
			editable.success= o.success;
			editable._show();
			 
		},
		_show: function() { 
			$(".editable").remove();
			var o = editable.o;
			if(!o.title) o.title = "编辑";
			var data_name = editable.element_data; 
			var html='';
			if(editable.data_type == 'text'){
				for (var e in data_name) {	 
					html += '<div class="editable-inp">'+data_name[e].title+'：<input name="'+data_name[e].name+'" id="'+data_name[e].name+'" type="text" class="input-medium" value="'+data_name[e].value+'"></div>';
				}
			}
			var html = '<div class="editable"> <div class="arrow"><span></span></div>' +
			    '<h3 class="editable_title">'+o.title+'</h3>' +
				' <div class="editable_content">' +
				'<div> <div class="control-group"><div><div class="editable-html">'+html+'</div>' +
			    '<div class="editable-buttons"> <button type="submit" class="btn btn-primary editable-submit"><i class="icon-ok icon-white"></i></button><button type="button" class="btn editable-cancel"><i class="icon-remove  icon-white"></i></button> </div>'+
				'</div></div></div></div></div>' ;
			$("body").append(html);
			
			var position = o.position();
			var poptop = position.top - $(".editable").height()-8;
			var sw = o.width()/2;
			var popleft = position.left + sw-$(".editable").width()/2;
			$(".editable").show().css("top", poptop + "px").css("left", popleft + "px");
			$(".editable-cancel").click(function() {  
				$(".editable").hide();
			});
			
			$(".editable-submit").click(function() {
					 editable.submits();
					 
					 box.confirm(editable.msg.html,editable.msg.width,'编辑',function(bool){ 
						if (bool) {
							 $.ajax(
								{
									dataType: 'html',
									url: editable.post.url,
									type: 'post',
									data: editable.post.data,
									success: function(data) 
									{	
										  editable.success(data);
									}
								});
						}
					});
			 });
			
		} 
	} 
	
 
	
 