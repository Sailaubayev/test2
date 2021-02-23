window.CONSTS={};
window.CONSTS.delete_url="operations.php?delete=1";
window.CONSTS.move_to="operations.php?move_to=1";
window.CONSTS.changeField="operations.php?change_field=1";
window.CONSTS.newClient="operations.php?new_client=1";
window.CONSTS.newReestr="operations.php?new_reestr=1";
window.CONSTS.getDump="operations.php?get_dump=1";
window.CONSTS.update="operations.php?update=1";
window.CONSTS.reestrToClients="operations.php?reestr_to_client=1";
window.CONSTS.moveToReestr="operations.php?client_to_reestr=1";










$(document).ready(function(){




	/*INIT OPERATIONS*/

	$(document).ready(function() 
	    { 
	        $("#our_clients table").tablesorter({
	        	headers:{
	        		0:{
	        			sorter:false
	        		},
	        		1:{
	        			sorter:false
	        		},
	        		3:{
	        			sorter:false
	        		},
	        		4:{
	        			sorter:false
	        		},
	        		5:{
	        			sorter:false
	        		},
	        		11:{
	        			sorter:false
	        		},
	        		12:{
	        			sorter:false
	        		}
	        	}
	        }); 
	    } 
	); 
    

	$("ul.nav-tabs a[href]").click(function(){
		location.hash=$(this).attr("href")
	})
	if(location.hash){
		$("a[href='"+location.hash+"']").trigger("click");
	}
	else{
		$("a[href='#our_clients']").trigger("click");
	}


	/*INIT OPERATIONS END*/


	/*UTILITY FUNCTIONS*/

	var ajaxFuncs={};
	ajaxFuncs.deleteById=function(ids,table,callback){
		$.post(window.CONSTS.delete_url,{ids:ids,table:table},callback);
	}
	ajaxFuncs.moveTo=function(ids,table,action,callback){
		$.post(window.CONSTS.move_to,{ids:ids,action:action,table:table},callback);
	}
	ajaxFuncs.changeField=function(id,table,field,value,callback){
		$.post(window.CONSTS.changeField,{id:id,table:table,field:field,value:value},callback);
	}
	ajaxFuncs.newOurClient=function(data,callback){
		$.post(window.CONSTS.newClient,data,callback);
	}
	ajaxFuncs.newReestrRecord=function(data,callback){
		$.post(window.CONSTS.newReestr,data,callback);
	}
	ajaxFuncs.getSqlDump=function(data,callback){
		$.get(window.CONSTS.getDump,callback);
	}
	ajaxFuncs.update=function(ids,table,callback){
		$.post(window.CONSTS.update,{ids:ids,table:table},callback);
	}
	ajaxFuncs.reestrToClients=function(ids,callback){
		$.post(window.CONSTS.reestrToClients,{ids:ids},callback);
	}
	ajaxFuncs.moveToReestr=function(ids,callback){
		$.post(window.CONSTS.moveToReestr,{ids:ids},callback);
	}	

	function findCheckedRows(tab){
		var out=[];
		$(tab).find(".action-column input").each(function(){
			if($(this).prop("checked")){
				out.push($(this).parents("tr.data-row").attr("data-row-id"))
			}
		})
		return out;
	}
	/*UTILITY FUNCTIONS END*/



	/*NEW APPLICATIONS HANDLERS*/

	$(".new-apps-actions button.delete").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.deleteById(ids,table,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});


	$(".new-apps-actions button.move-to-our-clients").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.moveTo(ids,table,"our_clients",function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();

		});
	});

	$(".new-apps-actions button.move-to-other-clients").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.moveTo(ids,table,"other_clients",function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});

	/*NEW APPLICATIONS HANDLERS END*/


	/*OUR CLIENTS HANDLERS*/


	$(".sending").click(function(){
		var id=$(this).parents("tr.data-row").attr("data-row-id");
		var table=$(this).parents(".tab-pane").attr("id");
		var chbox=this;
		setTimeout(function(){
			ajaxFuncs.changeField(id,table,"delivery",$(chbox).prop("checked")?1:0,function(){
				console.log('Status of row #'+id+" to "+$(this).prop("checked"));
			})
		},1)
	})


	$(".colorpicker_label").each(function(){
		var button=this;
		$(this).ColorPicker({
			color: $(this).attr("data-color"),
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$(button).css('backgroundColor', '#' + hex);
			},
			onSubmit:function(hsb, hex, rgb){
				var id=$(button).parents("tr.data-row").attr("data-row-id");
				var table=$(button).parents(".tab-pane").attr("id");
				ajaxFuncs.changeField(id, table, "color", "#"+hex, function(){
					$(button).parents("tr.data-row").css('backgroundColor', '#' + hex);
				})

			}
		});
	})

	$(".editable").on("dblclick doubletap",function(){
		var cell=this;
		var value=$(this).find("span").text();
		var id=$(this).parents("tr.data-row").attr("data-row-id");
		var table=$(this).parents(".tab-pane").attr("id");
		var field =$(this).attr("data-name");
		var textarea=$("<textarea></textarea>").html(value);
		textarea.on("focusout",function(){
			value=textarea.val();
			$(cell).find("span").html(value);
			ajaxFuncs.changeField(id,table,field,value,function(){
				textarea.remove();
				$(cell).find("span").show();
			})
		})
	    $(this).find("span").hide();
		$(this).append(textarea);
		textarea.focus();
	});



	$(".our-clients-actions button.move-to-other-clients").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.moveTo(ids,table,"other_clients",function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});

	$(".our-clients-actions button.move-to-reestr").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.moveToReestr(ids,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});

	$(".our-clients-actions button.update").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.update(ids,table,function(){
			window.location.reload();
		});
	});
	$(".our-clients-actions button.delete").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.deleteById(ids,table,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
		});
	});

	$("#new_user_form").submit(function(e){
		e.preventDefault();
		var form=$("#new_user_form");
		console.log(form.serialize());
		ajaxFuncs.newOurClient(form.serialize(),function(){
			window.location.reload();
		})
	});

	$(".get-sql-dump").click(function(){
		window.location=window.CONSTS.getDump;
	})


	/*OUR CLIENTS HANDLERS END*/

	/*OTHER CLIENTS HANDLERS*/

	$(".other-clients-actions button.delete").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.deleteById(ids,table,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
		});
	});
	$(".other-clients-actions button.move-to-our-clients").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.moveTo(ids,table,"our_clients",function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});



	/*OTHER CLIENTS HANDLERS END*/

	/*REESTR HANDLERS*/

	$(".reestr-actions button.delete").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var table=table_el.attr("id");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.deleteById(ids,table,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
		});
	});
	$(".reestr-actions button.move-to-our-clients").click(function(){
		var table_el=$(this).parents(".tab-pane");
		var ids=findCheckedRows(table_el);
		ajaxFuncs.reestrToClients(ids,function(){
			ids.forEach(function(item){
				$(table_el).find("tr.data-row[data-row-id="+item+"]").remove();
			})
			window.location.reload();
		});
	});


	$("#new_reestr_form").submit(function(e){
		e.preventDefault();
		var form=$("#new_reestr_form");
		console.log(form.serialize());
		ajaxFuncs.newReestrRecord(form.serialize(),function(){
			window.location.reload();
		})
	});

	/*REESTR HANDLERS END*/


})