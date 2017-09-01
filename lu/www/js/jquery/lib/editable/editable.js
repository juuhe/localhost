 (function($){
    $.fn.editable = function(options){
			var new_val=[],_this;
			var number = function(a) {
                return /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(a)
            }
			var ajax = function(url,data) {
               $.ajax(
									{
										dataType: 'html',
										url: url,
										type: 'post',
										data:  data ,
										success: function(data) 
										{	  
											 if(data == 'ok'){
												  _this.html(new_val.join(",")) ;
												  new_val=[];
												  var columns = options.columns;
												  for (var e in columns) { 
													  if(columns[e].up === true){   
														  _this.attr(columns[e].name,$("#editable_"+columns[e].name).val());
													  }
													  if(columns[e].sun_on_v){
														  _this.attr(columns[e].name,$("#editable_"+columns[e].sun_on_v).val());
													  }
												  }
												  $(".success").show();
												  $(".editable").remove();
											  }else {
												    new_val=[];
												 	alert("更新失败或权限不够"); 
												 }
										}
									});
            }
			var ihtml = function(html){
				    var html = '<div class="editable"> <div class="arrow"><span></span></div>' +
					'<h3 class="editable_title">编辑</h3>' +
					' <div class="editable_content">' +
					'<div> <div class="control-group"><div><div class="editable-html">'+html+' </div>' +
					'<div class="editable-buttons"> <button type="submit" class="btn btn-primary editable-submit"><i class="icon-ok icon-white"></i></button><button type="button" class="btn editable-cancel"><i class="icon-remove  icon-white"></i></button> </div>'+
					'</div></div></div></div></div>' ;
					$("body").append(html);
					var position = _this.position();
					var poptop = position.top - $(".editable").height()-8;
					var sw = _this.width()/2;
					var popleft = position.left + sw-$(".editable").width()/2;
					$(".editable").show().css("top", poptop + "px").css("left", popleft + "px");
					
			}
			$(this).on('click',function(){
				 _this= $(this);
				$(".editable").remove();
			 
					var html = '',columns = options.columns;
					for (var e in columns) {
						
						if(columns[e].type=='hidden'){
						  html += '<input name="editable_'+columns[e].name+'" id="editable_'+columns[e].name+'" type="hidden"';
						   if(columns[e].value){
							  html += 'value="'+columns[e].value+'"';
						  }else  if(columns[e].sun_on_v){
							  	 html += 'value="'+$(this).attr(columns[e].sun_on_v)+'"';
						   }else {  
							  html += 'value="'+($(this).attr(columns[e].name)?$(this).attr(columns[e].name):'')+'"';
							  }
						  
						  html += '> ';
						}else if(columns[e].type=='select'){
							  html += '<div class="editable-inp"><select name="editable_'+columns[e].name+'">';
							  	for (var s in columns[e].option) {
									html += '<option value="'+columns[e].option[s].value+'"';
									
									if($(this).attr(columns[e].name) == columns[e].option[s].value){
										html +=' selected ';	
									}
									
									html +='>'+columns[e].option[s].text+'</option>';									
									 
								} 
							  
							   html += '</select></div> ';
							
						}else{
						  html += '<div class="editable-inp">';
						 
						  if(columns[e].title){
							  html += columns[e].title+"：";
						  }
						  
						  html +='<input name="editable_'+columns[e].name+'" id="editable_'+columns[e].name+'" type="'+columns[e].type+'" class="input-medium"';
						  if(columns[e].max){
							  html += 'max="'+columns[e].max+'"';
							 
						  }
						  if(columns[e].maxlength){
							   html += 'maxlength="'+columns[e].maxlength+'"';
						  }
						  if(columns[e].min){
							  html += 'min="'+columns[e].min+'"';
						  }
						  if(columns[e].number){
							  html += 'number="'+columns[e].number+'"';
						  }
						  
						  if(columns[e].value){
							  html += 'value="'+columns[e].value+'"';
						  }else {  
							  html += 'value="'+($(this).attr(columns[e].name)?$(this).attr(columns[e].name):'')+'"';
							  }
						  
						   html += '>';
						  
						  if(columns[e].help){
							  html += '<div class="help">'+columns[e].help+'</div>';
					    	}
						
						  html +='</div>';
						}	 
						
					}
					ihtml(html);
					
					$(".editable-submit").click(function() {
							
							var p =[],msg=[]; 
							var ipt = $(".editable-html")
							.find("input")
							.each(function(i,e){ 
								p.push($(e).attr("name").replace("editable_", "")+"="+$(e).val());
								if($(e).attr("number") =='true' && !number($(e).val()) ){
									msg.push("格式不正确，输入一个数字");
									$(e).focus();
									return false;
								}	 
								if(jQuery.inArray($(e).attr("name").replace("editable_", ""), options.attr)>-1){
									new_val.push($(e).val());
								}
							}); 
							
							
							var ipt = $(".editable-html")
							.find("select")
							.each(function(i,e){ 
								p.push($(e).attr("name").replace("editable_", "")+"="+$(e).val());
								  
								if(jQuery.inArray($(e).attr("name").replace("editable_", ""), options.attr)>-1){
									new_val.push($(e).find("option:selected").text());
								}
							}); 
							
							
	  
							if(msg.length>0){
								alert(msg.join("\r\n"));	
								return false;
							}
							
							var statu = confirm("确认更新数据吗？");
							if(!statu){
								return false;
							}
							 ajax( options.url,p.join("&"));
							
					});
				 
				
				 
				$(".editable-cancel").click(function() {  
					$(".editable").remove();
				});
				return false;
			})
 	};
})(jQuery);
