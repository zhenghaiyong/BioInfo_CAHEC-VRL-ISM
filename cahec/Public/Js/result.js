var from = 0;
var limit = 500;
var list_css = 0;
var order_change ={
		accession:0,
		name:0,
		isolation_year:0,
		isolation_country:0,
		host:0,
		vrl_type:0,
		vrl_subtype:0,
		vrl_subsubtype:0,
		vrl_subsubsubtype:0,
		length:0,
		gene:0,
};
$(document).ready( function() {
	callbackGetData($("#post_data").val(),"bioentry_id","asc");
	$("#choose_all").click(function() {
		$('input[name="choose"]').attr("checked",this.checked); 
	});
	var $subBox = $("input[name='choose']");
	$subBox.click(function(){
		$("#choose_all").attr("checked",$subBox.length == $("input[name='choose']:checked").length ? true : false);
	});
	 
	$("#download").click(function(){
		var check_list = '';
		$('input[name="choose"]:checked').each(function(){
					check_list += $(this).val() + ',';
		});
		if(check_list == ''){
			alert("Please choose download data!");
		}
		else{
			check_list = check_list.substring(0,check_list.length-1);
			$("#check_list_hidden").val(check_list);
			$("#downLoadForm").attr('action',URL+"/downloadQuery");
			$("#downLoadForm").submit();
		}
	});
	$("#customize").click(function(){
		if($("#downloadFormat p").is(":hidden")){ 
			
			$("#downloadFormat p").show();
			$("#defline").focusEnd();
		}
		else{
			$("#downloadFormat p").hide();
		}
	});
	$(".click_insert").click(function(){
		Insert($(this).attr('id'));
	});
	$("#clear").click(function(){
		$("#defline").attr('value','>');
	});
	$("#reset").click(function(){
		$("#defline").attr('value','>{accession} {genus} {year} {virus_name}');
	});
	$(".display_order").click(function(){
		inilize($(this).attr('id'));
	});
});
function callbackGetData(post_data,orderby,order_line){
	var url = URL+"/result_ajax/";
	$.ajax({
		url: url,
		type: 'POST',
		async: true,
		data:{"post_data":post_data,"orderby":orderby,"order_line":order_line},
		success: function(data){
			var data = JSON.parse(data);
			 info = data.data;
			 display(info);
			 circlingInterval = setInterval(function(){display(info); }, 5000);
			 
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			//alert(XMLHttpRequest.status);
			// alert(XMLHttpRequest.readyState);
			//alert(textStatus);

		},
		complete: function (){
			
		}
	});
}
function display(info){
	var end = Number(from) + Number(limit);
	for(var val=from; val<end;val++){
		if(typeof(info[val]) != "undefined"){
			if(list_css % 2 == 0){
				//background color set in blue.css 
				$("tbody").append("<tr><td align='center' id='result_color_2'><input type='checkbox' name='choose' id='choose' class='choose' value="+info[val]['bioentry_id']+"></td><td align='center' id='result_color_2'><a href='http://www.ncbi.nlm.nih.gov/nuccore/"+info[val]['accession']+"."+info[val]['sequence_version']+"'>"+info[val]['accession']+"</a></td><td align='center' id='result_color_2'>"+info[val]['subfamily']+"</td><td align='center' id='result_color_2'>"+info[val]['genus']+"</td><td align='center' id='result_color_2'>"+info[val]['species']+"</td><td align='center' id='result_color_2'>"+info[val]['virus_name']+"</td><td align='center' id='result_color_2'>"+info[val]['isolation_year']+"</td><td align='center' id='result_color_2'>"+info[val]['isolation_country']+"</td><td align='center' id='result_color_2'>"+info[val]['host']+"</td><td align='center' id='result_color_2'>"+info[val]['typeA']+"</td><td align='center' id='result_color_2'>"+info[val]['typeB']+"</td><td align='center' id='result_color_2'>"+info[val]['subtype']+"</td><td align='center' id='result_color_2'>"+info[val]['subsubtype']+"</td><td align='center' id='result_color_2'>"+info[val]['subsubsubtype']+"</td><td align='center' id='result_color_2'>"+info[val]['gene']+"</td><td align='center' id='result_color_2'>"+info[val]['length']+"</td></tr>");
			}
			else{
				$("tbody").append("<tr><td align='center' id='result_color_1'><input type='checkbox' name='choose' id='choose' class='choose' value="+info[val]['bioentry_id']+"></td><td align='center' id='result_color_1'><a href='http://www.ncbi.nlm.nih.gov/nuccore/"+info[val]['accession']+"."+info[val]['sequence_version']+"'>"+info[val]['accession']+"</a></td><td align='center' id='result_color_2'>"+info[val]['subfamily']+"</td><td align='center' id='result_color_2'>"+info[val]['genus']+"</td><td align='center' id='result_color_2'>"+info[val]['species']+"</td><td align='center' id='result_color_2'>"+info[val]['virus_name']+"</td><td align='center' id='result_color_1'>"+info[val]['isolation_year']+"</td><td align='center' id='result_color_1'>"+info[val]['isolation_country']+"</td><td align='center' id='result_color_1'>"+info[val]['host']+"</td><td align='center' id='result_color_1'>"+info[val]['typeA']+"</td><td align='center' id='result_color_2'>"+info[val]['typeB']+"</td><td align='center' id='result_color_1'>"+info[val]['subtype']+"</td><td align='center' id='result_color_1'>"+info[val]['subsubtype']+"</td><td align='center' id='result_color_1'>"+info[val]['subsubsubtype']+"</td><td align='center' id='result_color_1'>"+info[val]['gene']+"</td><td align='center' id='result_color_1'>"+info[val]['length']+"</td></tr>");
			}
			list_css++;
		}
		
	}
	from = val;
	if(from >= $("#display_count").val()){
		$("#downloadFormat").show();
		if(typeof(circlingInterval) != 'undefined')
			clearInterval(circlingInterval);
	}
}
function inilize(check_order){
	$(".desc").hide();
	$(".asc").hide();
	$("#choose_all").attr("checked",false); 
	var order_line = '';
	if(order_change[check_order]%2 == 0){
		$("#"+check_order+" .desc").hide();
		$("#"+check_order+" .asc").show();
		order_line = 'asc';
		$("#order_line").val("asc");
		
	}
	else{
		$("#"+check_order+" .asc").hide();
		$("#"+check_order+" .desc").show();
		order_line = 'desc';
		$("#order_line").val("desc");
	}
	$("#order").val(check_order);
	order_change[check_order]++;
	if(typeof(circlingInterval) != 'undefined')
		clearInterval(circlingInterval);
	$("#downloadFormat").hide();
	from = 0;
	$("tbody").empty();
	callbackGetData($("#post_data").val(),check_order,order_line);
}
function addInsertVal(id){
	id = id.substring(3,id.length);
	var defline = $("#defline").val();
	$("#defline").attr('value',defline+' {'+id+'}');
}
$.fn.setCursorPosition = function(position){
    if(this.lengh == 0) return this;
    return $(this).setSelection(position, position);
}

$.fn.setSelection = function(selectionStart, selectionEnd) {
    if(this.lengh == 0) return this;
    input = this[0];

    if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
    } else if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
    }

    return this;
}

$.fn.focusEnd = function(){
    this.setCursorPosition(this.val().length);
}
function Insert(str) { 
	str = str.substring(3,str.length);
	var obj = document.getElementById('defline'); 
	if(document.selection) { 
		obj.focus(); 
		var sel=document.selection.createRange(); 
		document.selection.empty(); 
		sel.text = '{'+ str +'}'; 
	} else { 
		var prefix, main, suffix; 
		prefix = obj.value.substring(0, obj.selectionStart); 
		main = obj.value.substring(obj.selectionStart, obj.selectionEnd); 
		suffix = obj.value.substring(obj.selectionEnd); 
		obj.value = prefix +'{'+ str +'}'+ suffix; 
	}
	$("#defline").attr('value', $("#defline").val().replace('}{','} {'));
	obj.focus(); 
} 