add_user_vlt = {
    init: function() {
        form_class_validator = $("#form_v").validate({
            errorClass: "error",
            highlight: function(element) {
                $(element).closest('div').addClass("f_error");
            },
            unhighlight: function(element) {
                $(element).closest('div').removeClass("f_error");
            },
            rules: {
                type: {
                    required: false
                },
                username: {
                    required: true,
					remote:{  
					　　 type:"POST",
					　　 url:remote_url,  
					　　 data:{
					　	　 username:function(){
							return $("#username_ajax").val();
							}
				　　 		} 
					}
			　　 },
                password: {
                    required: true
                },
                contact: {
                    required: false
                },
                qq: {
                    required: false
                }
            },
            messages: {
                username:{
					required:"用户名不能为空！",
					remote:"用户名已存在"
				},
                password: "该项不能为空"
				 
            },
			errorElement: 'span'
        });
    }
};


add_article_vlt = {
    init: function() {
        form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			ignore: "",
			rules: {
                   title: {
				   	required: true 
                   	}, 
				  content: {
				   	required: true 
                   	}, 
					type: {
				   	required: true 
                   	}
				 },
			messages: {
				title:"标题项不能为空" ,
		 		content:"内容项不能为空" ,
				type:"请选择一个分类" 
			},
	 
			errorElement: 'span',
			errorPlacement: function (error, element) {
			    var eid = element.attr('name');   
				if(eid == "content_ck") {
						$('#content_a_parent').append(error);
				} else {
					error.insertAfter(element);
				}
			}
        });
    }
};


add_gift_vlt = {
    init: function() {
        form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			ignore: "",
			rules: { 
					type: {
				   	required: true 
                   	},
					name:{
					required: true 
					},
					integral:{
					required: true 
					}
				 },
			messages: {
				type:"请选择一个分类" ,
				name:"该项不能为空" ,
				integral:"该项不能为空" 
			},
	 
			errorElement: 'span'
        });
		if(type == 'add')
		$("#imageurl").rules("add",{required : true,messages:{required:"请择一个图片上传"}});
    }
};


add_class_vlt = {
		init: function() {
			form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   classname: {
				   		required: true 
                   	},
					type: {
						required: true 
					}
				 },
			messages: {
				classname:"请正确填写",
				type:"选择一个分类类型"
			},
			errorElement: 'span' 
			 
			});
		}
	};
	
	
add_roles_vlt = {
		init: function() {
			form_roles_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   name: {
				   		required: true 
                   	},
					'acl[]':{
						required: true ,
						minlength: 1
                   	}  
				 },
			messages: {
				name:"请正确填写",
				'acl[]':"请选择一个权限"
			},
			errorElement: 'span',
		   errorPlacement: function (error, element) {
				if (element.is(':checkbox')) {
					var eid = element.attr('name');  
					if(eid == "acl[]") $('#acl_error').html(error).addClass("frc_error");
				} else {
					error.insertAfter(element);
				}
			}
			});
			
		 
		}
	};	
	
	
add_adtpl_vlt = {
		init: function() {
			form_roles_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
				  name: {
				   		required: true 
                   	} 
                   
				 },
			messages: {
				 
				name:"请正确填写"
			},
			errorElement: 'span'
			});
			 
			 $(".statstype").rules("add",{required : true,messages:{required:"选择一个计费模式"}});
			 $(".htmlcontrol").rules("add",{required : true,messages:{required:"最少需要一个控件吧"}});
		}
	};

add_plan_vlt = {
		init: function() {
			
	 
			jQuery.validator.addMethod("SmallSize", function(value, element,params) {
				var pv = parseFloat($(params).val()); 
				if(!pv) pv = 0;		 								 
				return parseFloat(value)<= pv;
			}, "test值");
			
			jQuery.validator.addMethod("LargeSize", function(value, element,params) {
				var pv = parseFloat($(params).val());
				if(!pv) pv = 0;
				return parseFloat(value)>= pv;
			}, "test值");
			
			form_roles_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
					uid:{required: true },
				   planname: {
				   		required: true 
                   	} ,
					classid: {
                   	 required: true
                	},
					'datatime':  {
						required: true
					},
					'cookie':  {
						required: true
					},
					linkurl:  {
						 required: "#linkurl:checked"
					},
				  'acl[city][province][]':{
				   		required: "#acl0isacl:checked",
						minlength: 1
                   	} ,
					'acl[class][data][]':{
				   		required: "#acl1isacl:checked",
						minlength: 1
                   	} ,
					'acl[week][data][]':{
				   		required: "#acl2isacl:checked",
						minlength: 1
                   	} ,
					'acl[browser][data][]':{
				   		required: "#acl3isacl:checked",
						minlength: 1
                   	} ,
					'acl[mobile][data][]':{
				   		required: "#acl4isacl:checked",
						minlength: 1
                   	} ,
					'classprice_mark[]':{
				   		required: "#gradeprice:checked",
						minlength: 1
                   	},
					'classprice_mark_info[]':{
				   		required: "#gradeprice:checked",
						minlength: 1
                   	},
					'classprice_aff[]':{
				   		required: "#gradeprice:checked",
						minlength: 1,
						number:true
                   	},
					'classprice_adv[]':{
				   		required: "#gradeprice:checked",
						minlength: 1,
						number:true
                   	},
					"siteprice[0]":{
				   		required: "#gradeprice:checked",
						number:true,
						SmallSize:"#priceadv"
                   	} ,
					"siteprice[1]":{
				   		required: "#gradeprice:checked",
						number:true,
						SmallSize:"#priceadv"
                   	} ,
					"siteprice[2]":{
				   		required: "#gradeprice:checked",
						number:true,
						SmallSize:"#priceadv"
                   	} ,
					"siteprice[3]":{
				   		required: "#gradeprice:checked",
						number:true,
						SmallSize:"#priceadv"
                   	} ,
					price:{
				   		required: "#gradeprice:unchecked",
						number:true,
						SmallSize:"#priceadv",
						min:0.0000001
                   	} ,
					priceadv: {
				   		required: true ,
						number:true,
						LargeSize:"#price",
						min:0.0000001
                   	} ,
					budget: {
				   		required: true ,
						digits:true                 
                   	},
					resuid:{
				   		required: "#restrictions:unchecked"
                   	} ,
					limitsiteid:{
				   		required: "#sitelimit:unchecked"
                   	} 
				 },
			messages: {
				 uid:"选择一个广告商",
				 planname:"请正确填写",
				  classid: "选择一个活动分类",
				'datatime': "请正确填写",
				'cookie': "请正确填写",
				'linkurl': "请正确填写",
				 "siteprice[0]":{required:"请正确填写",number:"填写一个整数或是小数",SmallSize:"单价不能大于广告商"},
				 "siteprice[1]":{required:"请正确填写",number:"填写一个整数或是小数",SmallSize:"单价不能大于广告商"},
				 "siteprice[2]":{required:"请正确填写",number:"填写一个整数或是小数",SmallSize:"单价不能大于广告商"},
				 "siteprice[3]":{required:"请正确填写",number:"填写一个整数或是小数",SmallSize:"单价不能大于广告商"},
				 price:{required:"请正确填写",number:"填写一个整数或是小数",SmallSize:"价格不能大于广告商",min:"单价值太小了吧"},
			   'acl[city][province][]':"需要选择一个城市",
			   'acl[class][data][]':"需要选择一个网站类型",
			   'acl[week][data][]':"需要选择一个日程",
			   'acl[browser][data][]':"需要选择一个浏览器",
			   'acl[mobile][data][]':"需要选择一个终端",
			   'classprice_mark[]':"",
			   'classprice_mark_info[]':"",
			   'classprice_aff[]':"",
			   'classprice_adv[]':"",
				priceadv:{required:"请正确填写",number:"填写一个整数或是小数",LargeSize:"单价不能小于站长",min:"单价值太小了吧"},
				budget:"请正确填写一个整数",
				resuid:"请正确填写",
				limitsiteid:"请正确填写"
			},
			errorElement: 'span',
			
			errorPlacement: function (error, element) {
				if (element.is(':radio') || element.is(':checkbox')) {
					var eid = element.attr('name');  
					if(eid == "acl[city][province][]") $('#city_data_error').html(error).addClass("frc_error");
					if(eid == "acl[class][data][]") $('#class_data_error').html(error).addClass("frc_error");
					if(eid == "acl[week][data][]") $('#week_data_error').html(error).addClass("frc_error");
					if(eid == "acl[browser][data][]") $('#browser_data_error').html(error).addClass("frc_error");
					if(eid == "acl[mobile][data][]") $('#mobile_data_error').html(error).addClass("frc_error");
				} else {
					error.insertAfter(element);
				}
			}
			});
			
		
			
			/*
			var gradeprice =  $('input[name="gradeprice"]:checked').val();
			if(gradeprice>0){ 
					 $('#s0price').rules("add",{required : true,number:true,messages:{required:"请正确填写",number:"请填写一个整数或是小数"}});
					 $('#s1price').rules("add",{required : true,number:true,messages:{required:"请正确填写",number:"请填写一个整数或是小数"}});
					 $('#s2price').rules("add",{required : true,number:true,messages:{required:"请正确填写",number:"请填写一个整数或是小数"}});
					 $('#s3price').rules("add",{required : true,number:true,messages:{required:"请正确填写",number:"请填写一个整数或是小数"}});
			}else {
					 $('#price').rules("add",{required : true,number:true,messages:{required:"请正确填写",number:"请填写一个整数或是小数"}});
					 
				}
			 
			var restrictions =  $('input[name="restrictions"]:checked').val();
			if(restrictions>1){ 
					 $('#resuid').rules("add",{required : true,messages:{required:"请正确填写"}});
			} 
			var sitelimit =  $('input[name="sitelimit"]:checked').val();
			if(sitelimit>1){ 
					 $('#limitsiteid').rules("add",{required : true,messages:{required:"请正确填写"}});
			} 
				 */
			
		 
			
		}
	};	
	
	
	
add_ad_vlt = {
		init: function() {
			form_roles_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
				  planid: {
				   		required: true 
                   },
				   adtplid: {
				   		required: true 
                   } ,
				   url: {
				   		required: true,
						 url2: true
                   } ,
				    
				   imageurl:{
				   		images_file: true
				   },
				   urlfile:{
				   		required: true
				   },			 
				   
				   headline:{
				   		required: true
				   },
				   description:{
				   		required: true
				   },
				   dispurl:{
				   		required: true
				   }
				 },
			messages: {
				 planid:"请选择一个计划",
				 adtplid:"请选择一个广告类型",
				 url:{required:"广告地址不能为空",url2:"请填写一个正确url"},
				 htmlcode:"请正确填写",
				 imageurl:"请选择一个图片",
				 urlfile:"请正确填写",
				 htmlfile:"请正确填写",
				 headline:"请正确填写",
				 description:"请正确填写",
				 dispurl:"请正确填写"
			},
			errorElement: 'span'
        });
    }
};

add_site_vlt = {
    init: function() {
        form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   username: {
                    required: true,
					remote:{  
					　　 type:"POST",
					　　 url:remote_url,  
					　　 data:{
					　	　 username:function(){
								return $("#username").val();
							}
				　　 	   } 
					}
			　　 }, 
				    siteurl: {
				   		required: true 
                   	}, 
					sitename: {
				   		required: true 
                   	}
					,sitetype: {
				   		required: true 
                   	}
				 },
			messages: {
				username:{
					required:"用户名不能为空！",
					remote:"没有这个站长，请重新输入"
				},
		 		siteurl:"网站域名不能为空" ,
				sitename:"网站名称不能为空" ,
				sitetype:"请选择一个网站类型" 
			},
	 
			errorElement: 'span',
			 
        });
    }
};


add_group_vlt = {
		init: function() {
			form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   groupname: {
				   		required: true 
                   	} 
				 },
			messages: {
				groupname:"请正确填写"
			},
			errorElement: 'span' 
			 
			});
		}
	};


add_group_vlt = {
		init: function() {
			form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   groupname: {
				   		required: true 
                   	} 
				 },
			messages: {
				groupname:"请正确填写"
			},
			errorElement: 'span' 
			 
			});
		}
	};
	
	
		
add_specs_vlt = {
		init: function() {
			form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("f_error");
			},
			rules: {
                   width: {
				   		required: true 
                   	} ,height: {
				   		required: true 
                   	} ,'sort': {
				   		required: true 
                   	} 
				 },
			messages: {
				width:"请正确填写",
				height:"请正确填写",
				sort:"请正确填写"
			},
			errorElement: 'span' 
			 
			});
		}
	};	
	
	
add_adminuser_vlt = {
    init: function() {
        form_class_validator = $("#form_adm").validate({
            errorClass: "error",
            highlight: function(element) {
                $(element).closest('div').addClass("f_error");
            },
            unhighlight: function(element) {
                $(element).closest('div').removeClass("f_error");
            },
            rules: {
             
                username: {
                    required: true
			　　 },
                password: {
                    required: true
                } 
            },
            messages: {
                username:"用户名不能为空！" ,
                password: "用户密码不能为空"
				 
            },
			errorElement: 'span'
        });
    }
};	


add_import_vlt = {
    init: function() {
        form_class_validator = $("#form_b").validate({
            errorClass: "error",
            highlight: function(element) {
                $(element).closest('div').addClass("f_error");
            },
            unhighlight: function(element) {
                $(element).closest('div').removeClass("f_error");
            },
            rules: {
                planid: {
                    required: true
			　　 },
			    postdata:  {
						 required: "#datatype_t:checked"
				 },
				 files: {
						 required: "#datatype_f:checked"
				 }
            },
            messages: {
                planid:"选择一个计划！" ,
                postdata: "导入数据不能为空",
				 files: "选择一个文件"
				 
            },
			errorElement: 'span'
        });
    }
};	