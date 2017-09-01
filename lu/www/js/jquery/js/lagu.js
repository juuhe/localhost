lga_form_class_validation = {
		init: function() {
			form_class_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("error");
			},
			rules: {
                   classname: {
				   	required: true 
                   	},
					classcommission: {
						digits:true
					},
					commissiontime: {
						digits:true
					},
					classdeduction: {
						digits:true
					}
				 },
			messages: {
				classname:"请正确填写",
				commissiontime:"输入0~100",
				classcommission:"输入0~100",
				classdeduction:"输入0~100"
			}
			});
		}
	};
	
lga_form_level_validation = {
		init: function() {
			form_level_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("error");
			},
			rules: {
                   levelname: {
				   	required: true 
                   	},
					levelcommission: {
						digits:true
					},
					commissiontime: {
						digits:true
					},
					leveldeduction: {
						digits:true
					}
				 },
			messages: {
				levelname:"请正确填写",
				commissiontime:"输入0~100",
				levelcommission:"输入0~100",
				leveldeduction:"输入0~100"
			}
			});
		}
	};
	
 lga_form_article_validation = {
		init: function() { 
			form_level_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('div').addClass("error");
				},
			unhighlight: function(element) {
				$(element).closest('div').removeClass("error");
			},
			ignore: "",
			rules: {
                   title: {
				   	required: true 
                   	}, 
					content_ck: {
				   	required: true 
                   	},
					type: {
				   	required: true 
                   	}
				 },
			messages: {
				title:"请正确填写" ,
				content_ck:"请正确填写" ,
				type:"请选择" 
			}
			});
		}	
	};
	