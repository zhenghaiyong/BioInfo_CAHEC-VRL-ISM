var condition_click_time ={
	family:0,
	subfamily:0,
	genus:0,
	species:0,
	virus_name:0,
	host:0,
	typeA:0,
	typeB:0,
	subType:0,
	subsubType:0,
	subsubsubType:0,
	gene:0,
	continent:0,
	from:0,
	to:0,
	flen:0,
	tlen:0
};
var country_term = '';
$(document).ready( function() {
	callbackGene($("#family").val());
	$(".continent_choose").click(function(){
		$("#country_any").attr("checked",false);
		$(".continent_choose").css("color","#174B73");
		search_id = $(this).attr('id').substring(0,$(this).attr('id').length-2);
		$(this).css("color","#F00");
		$(".country_hide").hide();
		$("#"+search_id).show();
		
	});
	$("#family").change(function(){
		callbackGene($(this).val());
	});
	$("#gene").change(function(){
		var left = 0;
		var right = 0;
		var option = $(this).children("option");
		$(this).children("option").each(function(){
			//alert($(this).attr('selected'));
			if($(this).attr('selected') == true){
				left = $(this).attr('left');
				right = $(this).attr('right');
				//alert($(this).attr('left'));
				if(right-1 != left){		
					$("#gene").children("option").each(function(){
						if($(this).attr('left') - left > 0){
							if($(this).attr('right') - right < 0){
							if($(this).attr('selected') == false)
								$(this).attr("selected",true);
							}
						}
					});
				}
			}
		});
	});
	$("#from").jdPicker(); 
	$("#to").jdPicker();
	$("#to").change(function(){
		if($("#from").val() > $(this).val() && $(this).val() != ""){
			alert("to > from! please reset");
			//此处需要给to设置某个时间值，建议将from转化成13.。。时间，再加1 再转化过来？ 时间原因未添加
		}
	});
	$("#tlen").change(function(){
		if($("#flen").val() > $(this).val() && $(this).val() != ""){
			alert("Min > Max! please reset");
			$(this).val($("#flen").val());
		}
	});
	$("#show_result_button").click(
			function(){
				//alert($("#host").val());
				if($("#from").val() > $("#to").val() && $("#to").val() != ""){
					alert("to > from! please reset");
				}
				else if($("#flen").val() > $("#tlen").val() && $("#tlen").val() != ""){
					alert("flen > tlen! please reset");
				}
				else{
					post_data = $("#family").val() +"||"+collect_condition('subfamily') +"||"+collect_condition('genus') +"||"+collect_condition('species') +"||"+collect_condition('virus_name') +"||"+collect_condition('host') +"||"+collect_condition('typeA') +"||"+collect_condition('typeB') +"||"+collect_condition('subType') +"||"+collect_condition('subsubType') +"||"+collect_condition('subsubsubType')+"||"+$("#gene").val()+"||"+collect_condition('country')+"||"+convertNull($("#from").val())+"||"+convertNull($("#to").val())+"||"+convertNull($("#flen").val())+"||"+convertNull($("#tlen").val());
					$("#post_data").val(post_data);
					//alert(post_data);
					$('#form').submit();
				}
				
	});
	$(".condition").click(function(){
		$("#hide_condition").show();
		$(".condition").css("color","#174B73");
		search_id = $(this).attr('id').substring(0,$(this).attr('id').length-2);
		$(this).css("color","#F00");
		if(condition_click_time[search_id] == 0){
			condition_click_time[search_id]++;
			if(search_id == 'continent'){
				$(".display_condition").hide();
				$("#continent").show();
			}
			else{
				callbackData($("#family").val(),search_id);
			}
		}
		else{
			$(".display_condition").hide();
			$("#"+search_id).show();
		}
	});
	$("#hide_condition").click(function(){
		$(".display_condition").hide();
		$(".continent_choose").css("color","#174B73");
		$(".condition").css("color","#174B73");
		$(this).hide();
	});
	
});
function collect_condition(id){
	each_condition = '';
	$("input[name="+id+"]:checked").each(function(){
		if($(this).val() == 0){
			each_condition = 0;
			return false;
		}
		else{
			each_condition += $(this).val()+",";
		}
	});
	if(each_condition != 0){
		each_condition = each_condition.substring(0,each_condition.length-1);
	}
	if(each_condition == '')
		each_condition = 0;
	return each_condition;
}
function convertValue(value){
	if($.inArray("0",value) == -1){
		return value;
	}
	else{
		return '0';
	}
}
function convertNull(value){
	if(value != ''){
		return value;
	}
	else{
		return 0;
	}
}

function callbackData(family_id,id){
	var url = URL+"/displaySearch/family_id/" + family_id+"/id/"+id;
	$.ajax({
		url: url,
		type: 'GET',
		async: true,
		//dataType: 'json',
		success: function(data){
			$(".query_change").empty();
			$(".query_change").append("<option value='0' selected>any</option>");
			var data = JSON.parse(data);
			data = data.data;
			displayquery(id,data);
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
function callbackGene(family_id){
	var url = URL+"/geneSelect/family_id/" + family_id;
	$.ajax({
		url: url,
		type: 'GET',
		async: true,
		//dataType: 'json',
		success: function(data){
			$("#gene").empty();
			$("#gene").append("<option value='0' selected>any</option>");
			var data = JSON.parse(data);
			displaygene(data.data);
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

function displayquery(id,data){
	display_id = id;
	var display_info = "<table><tr>";
	$(".display_condition").hide();
	$("#"+display_id).show();
	$("#display_country").hide();
	if(data == ''){
		display_info += "<td><input type='checkbox' name="+id+" id='any"+id+"' value='0' checked='checked' ><label for='any"+id+"'>any</label></td>";
	}
	else{
		data.unshift('any');
		for(var i=1; i<=data.length;i++){
			if(i == 1){
				display_info += "<td><input type='checkbox' name="+id+" id='any"+id+"' value='0' checked='checked' ><label for='any"+id+"'>any</label></td>";
			}
			else if(i%4 == 0){
				display_info += "<td><input type='checkbox' name="+id+" id ='"+id+data[i-1][id]+"' value='"+data[i-1][id]+"'><label for='"+id+data[i-1][id]+"'>"+data[i-1][id]+"</label></td></tr><tr>";
			}
			else{
				display_info += "<td><input type='checkbox' name="+id+" id ='"+id+data[i-1][id]+"' value='"+data[i-1][id]+"'><label for='"+id+data[i-1][id]+"'>"+data[i-1][id]+"</label></td>";
			}
		}
	}
		display_info += "</tr></table>";
		$("#"+display_id).append(display_info);
}


function displaygene(gene){
	if(gene != ''){
		for(var i=0; i<gene.length;i++){
			switch (gene[i]['level']){
				case '0':
					//$("#gene").append("<option value="+gene[i]['gene_id']+"selected>"+gene[i]['name']+"</option>");
					$("#gene").append("<option value='"+gene[i]['name']+"@@"+gene[i]['parent_id']+"@@"+gene[i]['level']+"@@id:"+gene[i]['gene_id']+"' left="+gene[i]['left_value'] +" right="+gene[i]['right_value']+">"+gene[i]['name']+"</option>");
					break;
				case '1':
					$("#gene").append("<option value='"+gene[i]['name']+"@@"+gene[i]['parent_id']+"@@"+gene[i]['level']+"@@id:"+gene[i]['gene_id']+"'  left="+gene[i]['left_value'] +" right="+gene[i]['right_value']+" id=gene_"+gene[i]['gene_id']+">&nbsp;&nbsp;"+gene[i]['name']+"</option>");
					break;
				case '2':
					$("#gene_"+gene[i]['parent_id']).after("<option value='"+gene[i]['name']+"@@"+gene[i]['parent_id']+"@@"+gene[i]['level']+"@@id:"+gene[i]['gene_id']+"'  left="+gene[i]['left_value'] +" right="+gene[i]['right_value']+" id=gene_"+gene[i]['gene_id']+">&nbsp;&nbsp;&nbsp;&nbsp;"+gene[i]['name']+"</option>");
					break;
				case '3':
					$("#gene_"+gene[i]['parent_id']).after("<option value='"+gene[i]['name']+"@@"+gene[i]['parent_id']+"@@"+gene[i]['level']+"@@id:"+gene[i]['gene_id']+"' left="+gene[i]['left_value'] +" right="+gene[i]['right_value']+" id=gene_"+gene[i]['gene_id']+">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+gene[i]['name']+"</option>");
					break;
				default:
					break;
					
			}
		}
	}
			
}