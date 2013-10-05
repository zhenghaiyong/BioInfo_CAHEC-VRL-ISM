

var oNotifier=new Notifier;utils.addEvent(window,"load",function(){var elForm=document.forms["query_form"];var elTable=document.getElementById("builder-table");var elSelectLines=document.getElementById("select-lines");var elSonlyLines=document.getElementById("sonly-lines");var elSplusLines=document.getElementById("splus-lines");var aCollapseCbx=elForm["collapse"];var elMessage=document.getElementById("message");var a=elForm["sequence"];for(var i=0;i<a.length;i++){if(a[i].checked){sCurrentSeq=a[i].value;break;}}
CheckWarning();function CheckWarning(){if(!elMessage)return;if(x_CheckWarning()){elMessage.style.display="";}else{elMessage.style.display="none";}
function x_CheckWarning(){var a=$C("line-ctrl");if(!a)return false;var b=false;var sInfluenzaType="";var sSegment="";var sInfluenzaTypePrev="";var sSegmentPrev="";for(var i=0;i<a.length;i++){var x=a[i].name.split("_");var sPrefx="query_"+x[1]+"_";if(elSonlyLines&&elForm[sPrefx+"acc_list"]!=undefined){elSonlyLines.disabled="disabled";}
if(!a[i].checked)continue;sInfluenzaType=(elForm[sPrefx+"type"]?elForm[sPrefx+"type"].value:"");sSegment=(elForm[sPrefx+"segment"]?elForm[sPrefx+"segment"].value:"");if(sInfluenzaType=="any"||sSegment=="any")return true;if(sInfluenzaTypePrev!=""){if(sInfluenzaType!=sInfluenzaTypePrev)return true;if(sSegment!=sSegmentPrev)return true;}
sInfluenzaTypePrev=sInfluenzaType;sSegmentPrev=sSegment;}
return false;}}
oNotifier.setListener(oNotifier,"delete-lines",function(x,bFlag,z){var aLineCbx=$C("line-ctrl");if(!aLineCbx){return;}
var b=true;for(var i=0;i<aLineCbx.length;i++){if(bFlag){if(aLineCbx[i].checked){b=false;continue;}}else{if(!aLineCbx[i].checked){b=false;continue;}}
var eTbody=aLineCbx[i].parentNode.parentNode.parentNode;utils.removeChildren(eTbody);elTable.removeChild(eTbody);eTbody=null;}
if(b){var el=document.getElementById("builder");if(el)
el.parentNode.removeChild(el);elTable=null;}
ShowResultsButtons(b);aLineCbx=$C("line-ctrl");if(aLineCbx.length==1){if(elSelectLines){var el=elSelectLines.parentNode;if(el)el.removeChild(elSelectLines);}
var aTotals=$C("total");for(var i=0;i<aTotals.length;i++){aTotals[i].innerHTML="&nbsp;";}}else{oNotifier.Notify(null,"update-count");}});oNotifier.setListener(oNotifier,"seq-changed",function(x,y,z,el){if(el.value==sCurrentSeq)return;elForm["cmd"].value="";var s=document.location.href.split("?");elForm.action=s[0];if(!elTable){oNotifier.Notify(null,"cmd_add","");return;}
if(confirm("You are about to query another sequence type.\n"+"Your current Query Builder will be cleared if you start a new query.\n\n"+"Click \"OK\" to start new query or \"Cancel\" to go back")){oNotifier.Notify(this,'delete-lines');sCurrentSeq=el.value;elForm.submit();}else{var a=elForm["sequence"];for(var i=0;i<a.length;i++){if(a[i].value==sCurrentSeq){a[i].checked=true;break;}}}});oNotifier.setListener(oNotifier,"select-lines",function(x,y,z,elCbx){GroupCbxMaster($C("line-ctrl"),elCbx);oNotifier.Notify(null,"update-count",true);});oNotifier.setListener(oNotifier,"select-line",function(x,y,z,elCbx){GroupCbx(elCbx,$C("line-ctrl"),elSelectLines);oNotifier.Notify(null,"update-count",true);});oNotifier.setListener(oNotifier,"sonly-lines",function(x,y,z,elCbx){GroupCbxMaster($C("sonly-line"),elCbx);ApplyFilters(elCbx);});oNotifier.setListener(oNotifier,"splus-lines",function(x,y,z,elCbx){GroupCbxMaster($C("splus-line"),elCbx);ApplyFilters(elCbx);});oNotifier.setListener(oNotifier,"sonly-line",function(x,y,z,elCbx){GroupCbx(elCbx,$C("sonly-line"),elSonlyLines);oNotifier.Notify(elCbx,"xfilter");});oNotifier.setListener(oNotifier,"splus-line",function(x,y,z,elCbx){GroupCbx(elCbx,$C("splus-line"),elSonlyLines);oNotifier.Notify(elCbx,"xfilter");});oNotifier.setListener(oNotifier,"check-splus-sonly",function(x,y,z,elCbx){var elForm=document.forms["query_form"];if(elCbx.name=="splus")
elForm["sonly"].checked=false;else if(elCbx.name=="sonly")
elForm["splus"].checked=false;else if(elCbx.name=="reqseg_full")
elForm["reqseg_plus"].checked=false;else if(elCbx.name=="reqseg_plus")
elForm["reqseg_full"].checked=false;});oNotifier.setListener(oNotifier,"xfilter",function(x,data,z,el){var oForm=document.forms["query_form"];var sQuery="sequence="+GetRadioValue("sequence");var sGenomeSet=GetRadioValue("genomeset");if(sGenomeSet&&sGenomeSet!=""){sQuery+="&genomeset="+sGenomeSet;}
if(aCollapseCbx&&aCollapseCbx.checked){sQuery+="&collapse=on";}
sQuery+="&qcnt=";var a,iNum;if(data){iNum=data;}else{a=el.name.split("_");iNum=a[1];}
if(iNum>0){var elTbody=document.getElementById("line_"+iNum);var elInput=utils.getFirstChild(utils.getFirstChild(utils.getFirstChild(elTbody)));while(elInput=utils.getNextSibling(elInput)){if(elInput.type!="hidden")continue;a=elInput.name.split("_");var name="";for(var i=2;i<a.length;i++){name+=(i==2?"":"_")+a[i];}
sQuery+=name+"=\""+escape(elInput.value)+"\"`";}
var sPrefix="query_"+iNum+"_";if(!oForm[sPrefix+"acc_list"]){sQuery+=(oForm[sPrefix+"sonly"]&&oForm[sPrefix+"sonly"].checked?"sonly=\"on\"`":"");sQuery+=(oForm[sPrefix+"splus"]&&oForm[sPrefix+"splus"].checked?"splus=\"on\"`":"");a=["swine","niaid","lab"];for(var i=0;i<a.length;i++){var x=oForm[sPrefix+a[i]];sQuery+=a[i]+"=\""+x.options[x.selectedIndex].value+"\"`";}}
var elCnt=document.getElementById(sPrefix+"count_td");var oData;var oDp=new RemoteDataProvider;oDp.onSuccess=function(oObj){var b=false;try{eval("oData ="+oObj.responseText);}catch(e){b=true;}
if(b){elCnt.innerHTML="n/a";oNotifier.Notify(null,"update-count");return;}
var iCnt=parseInt(oData.count);oForm[sPrefix+"count"].value=iCnt;elCnt.innerHTML=iCnt;var elCntGenomeSets=document.getElementById(sPrefix+"count_genome_sets_td");if(elCntGenomeSets){iCnt=parseInt(oData.count_genome_sets);elCntGenomeSets.innerHTML=iCnt;oForm[sPrefix+"count_genome_sets"].value=iCnt;}
utils.removeClass(elTbody,"busy");oNotifier.Notify(null,"update-count");};oDp.onError=function(oObj){elCnt.innerHTML="N/A";utils.removeClass(elTbody,"busy");oNotifier.Notify(null,"update-count");};utils.addClass(elTbody,"busy");oDp.sUrl="?";oDp.Get(sQuery);}});oNotifier.setListener(oNotifier,"collapse",function(x,y,z,elCbx){if(elCbx.checked){document.getElementById("message2").style.display="";}else{document.getElementById("message2").style.display="none";}
ApplyFilters(elCbx);});oNotifier.setListener(oNotifier,"update-count",function(x,bNoTotals){var el=document.getElementById("builder-table");if(!el)return;var elTotal=document.getElementById("total-count");var elTotalSets=document.getElementById("total-sets");var elTotalSelected=document.getElementById("total-count-selected");var elTotalSetsSelected=document.getElementById("total-sets-selected");var elTrTotal;var elTrTotalSelected;if(elTotal){elTrTotal=utils.getParent(utils.getParent(elTotal));elTrTotalSelected=utils.getParent(utils.getParent(elTotalSelected));}
var sQuery="sequence="+GetRadioValue("sequence");var sGenomeSet=GetRadioValue("genomeset");if(sGenomeSet&&sGenomeSet!=""){sQuery+="&genomeset="+sGenomeSet;}
if(aCollapseCbx&&aCollapseCbx.checked){sQuery+="&collapse=on";}
var sQueryTotal=sQuery+"&qcnt=";var sQueryTotalSelected=sQueryTotal;var oForm=document.forms["query_form"];var iQueryCount=0;var iQuerySelectedCount=0;el=utils.getFirstChild(el);while(el=utils.getNextSibling(el,"TBODY")){var sQ="";var elInput=utils.getFirstChild(utils.getFirstChild(utils.getFirstChild(el)));var bIsSelected=elInput.checked;do{if(elInput.type!="hidden")continue;var aa=elInput.name.split("_");var name="";for(var i=2;i<aa.length;i++){name+=(i==2?"":"_")+aa[i];}
sQ+=name+"=\""+escape(elInput.value)+"\"`";}while(elInput=utils.getNextSibling(elInput));var a=el.id.split("_");var sPrefix="query_"+a[1]+"_";if(!oForm[sPrefix+"acc_list"]){sQ+=(oForm[sPrefix+"sonly"]&&oForm[sPrefix+"sonly"].checked?"sonly=\"on\"`":"");sQ+=(oForm[sPrefix+"splus"]&&oForm[sPrefix+"splus"].checked?"splus=\"on\"`":"");a=["swine","niaid","lab"];for(var i=0;i<a.length;i++){var x=oForm[sPrefix+a[i]];sQ+=a[i]+"=\""+x.options[x.selectedIndex].value+"\"`";}}
sQ+=(aCollapseCbx&&aCollapseCbx.checked?" collapse=\"on\"":"");sQueryTotal+=sQ+"|";iQueryCount++;if(bIsSelected){sQueryTotalSelected+=sQ+"|";iQuerySelectedCount++;}}
console.info(sQueryTotal)
console.info(iQueryCount,iQuerySelectedCount);if(iQueryCount<=1)return;if(iQueryCount>1&&!bNoTotals){console.info("Update total");UpdateTotals(sQueryTotal,elTotal,elTotalSets,elTrTotal);}
if(iQuerySelectedCount==0){elTotalSelected.innerHTML="0";if(elTotalSetsSelected){elTotalSetsSelected.innerHTML="0";}}else if(iQuerySelectedCount>0){console.info("Update selected");if(iQueryCount==iQuerySelectedCount){console.info("Update selected - by copy");var timer;function x_WaitForTotal(){timer=setTimeout(function(){if(utils.hasClass(elTrTotal,"busy")){x_WaitForTotal();}else{clearTimeout(timer);timer=null;elTotalSelected.innerHTML=elTotal.innerHTML;utils.removeClass(elTrTotalSelected,"busy");if(elTotalSetsSelected){elTotalSetsSelected.innerHTML=elTotalSets.innerHTML;}}},1000);}
x_WaitForTotal();}else{UpdateTotals(sQueryTotalSelected,elTotalSelected,elTotalSetsSelected,elTrTotalSelected);}}
CheckWarning();});oNotifier.setListener(oNotifier,"genomeset",function(){ApplyFilters();});oNotifier.setListener(oNotifier,"cmd_add",function(x,cmd,z,el){elForm["cmd"].value=cmd;var s=document.location.href.split("?")[0].split("#")[0];elForm.action=s+(cmd=="add_acc"?"#accform":"#mainform");elForm.submit();});oNotifier.setListener(oNotifier,"cmd_show",function(x,cmd,z,el){elForm["cmd"].value=cmd;var s=document.location.href.split("?")[0].split("#")[0];elForm.action=s;var cbdr=elForm["defline_remember"];if(cbdr&&cbdr.checked)
elForm["defline_saved"].value=elForm["defline"].value
if(!(window.navigator.userAgent.indexOf("AppleWebKit")!=-1&&window.navigator.userAgent.indexOf("Windows")!=-1)){elForm.target="_blank";setTimeout(function(){elForm.target="_self";},500);}
elForm.submit();elForm["cmd"].value="";});oNotifier.setListener(oNotifier,"permalink",function(x,cmd,z,el){var s="?";for(var i=0;i<document.query_form.length;i++){var e=document.query_form.elements[i]
if(!e.name||!e.type)
continue;switch(e.type){case"button":case"submit":break;case"radio":case"checkbox":if(e.checked)
s=s+escape(e.name)+"="+escape(e.value)+"&";break;case"select-multiple":for(var n=0;n<e.options.length;n++)
if(e.options[n].selected)
s=s+escape(e.name)+"="+escape(e.options[n].value)+"&";break;default:s=s+escape(e.name)+"="+escape(e.value)+"&";}}
el.href=s;});oNotifier.setListener(oNotifier,"multiselect_change",function(x,cmd,z,el){MultiSelectChanged(el);});oNotifier.setListener(oNotifier,"reqseg_click",function(x,cmd,z,el){if(el==document.query_form.reqseg_all){var rsegs=document.getElementsByName("reqseg");for(var i=0;i<rsegs.length;i++){if(rsegs[i].value!="complete"&&rsegs[i].disabled==false&&rsegs[i].parentNode.style.display!='none')
rsegs[i].checked=document.query_form.reqseg_all.checked;}}else{document.query_form.reqseg_all.checked=false;}});oNotifier.setListener(oNotifier,"download_change",function(x,cmd,z,el){if(elForm["defline"]){var defline=default_deflines[elForm["download-select"].value];var elDefline=document.getElementById("defline");if(defline){utils.removeClass(elDefline,"hidden");}
else{utils.addClass(elDefline,"hidden");}}});oNotifier.setListener(oNotifier,"defline_check",function(x,cmd,z,el){var defline=elForm["defline"].value;var elDefline=document.getElementById(el.href.substr(el.href.lastIndexOf("#")+1));if(utils.hasClass(elDefline,"collapsed")){utils.removeClass(elDefline,"collapsed");elForm["defline"].focus();}
else{utils.addClass(elDefline,"collapsed");}
elForm["defline"].value="";elForm["defline"].value=defline;});oNotifier.setListener(oNotifier,"defline_insert",function(x,instext,z,el){var inpDefline=elForm["defline"];var instext=el.title;if(document.selection){inpDefline.focus();var sel=document.selection.createRange();if(sel.text=="")
instext=instext+" ";sel.text=instext;}
else{var len=inpDefline.value.length;var start=inpDefline.selectionStart;var end=inpDefline.selectionEnd;if(inpDefline.value.substring(start-1,start)=="}")
instext=" "+instext;if(inpDefline.value.substring(end,end+1)=="{")
instext=instext+" ";inpDefline.value=inpDefline.value.substring(0,start)+instext+inpDefline.value.substring(end,len);inpDefline.selectionStart=start+instext.length;inpDefline.selectionEnd=start+instext.length;inpDefline.focus();}});oNotifier.setListener(oNotifier,"defline_clear",function(x,instext,z,el){elForm["defline"].value=">";elForm["defline"].focus();});oNotifier.setListener(oNotifier,"defline_default",function(x,instext,z,el){elForm["defline"].value=default_deflines[elForm["download-select"].value];elForm["defline"].focus();elForm["defline_remember"].checked=true;});function UpdateTotals(sQuery,elTotal,elTotalSets,elTr){console.info(sQuery);var oDp=new RemoteDataProvider;var oData;oDp.onSuccess=function(oObj){var b=false;try{eval("oData ="+oObj.responseText);}catch(e){b=true;}
if(b){oDp.onError(oObj);return;}
utils.removeClass(elTr,"busy");console.info(oData);elTotal.innerHTML=parseInt(oData.count);if(elTotalSets){elTotalSets.innerHTML=parseInt(oData.count_genome_sets);}
oNotifier.Notify(null,"update-count-selected");};oDp.onError=function(oObj){elTotal.innerHTML="n/a";if(elTotalSets){elTotalSets.innerHTML="n/a";}};utils.addClass(elTr,"busy");oDp.sUrl="?";oDp.Get(sQuery);}
function ApplyFilters(elCbx){var el=document.getElementById("builder-table");if(!el)return;el=utils.getFirstChild(el);while(el=utils.getNextSibling(el,"TBODY")){var iNum=el.id.split("_")[1]
oNotifier.Notify(elCbx,"xfilter",iNum);}}
function GroupCbxMaster(aCbxs,aMaster){if(aCbxs.length==undefined){aCbxs.checked=aMaster.checked;}else{for(var i=0;i<aCbxs.length;i++){aCbxs[i].checked=aMaster.checked;if(aCbxs[i].name.indexOf("line")>-1){var elTbody=getParentElement(aCbxs[i],"tbody");if(aCbxs[i].checked){utils.addClass(elTbody,"highlight");}else{utils.removeClass(elTbody,"highlight");}}}}}
function GroupCbx(elCbx,aCbxs,aMaster){var elTbody=getParentElement(elCbx,"tbody");if(elCbx.name.indexOf("_line")>1){if(elCbx.checked){utils.addClass(elTbody,"highlight");}else{utils.removeClass(elTbody,"highlight");}}
if((!elCbx.checked)&&aMaster){aMaster.checked=false;return;}
for(var i=0;i<aCbxs.length;i++){if(aCbxs[i].checked)continue;if(aMaster)aMaster.checked=false;return;}
if(aMaster)aMaster.checked="on";var sName=elCbx.name;if(sName.indexOf("splus")>1){var elToUncheck=elForm[sName.replace("splus","sonly")];if(elToUncheck)elToUncheck.checked=false;}
if(sName.indexOf("sonly")>1){var elToUncheck=elForm[sName.replace("sonly","splus")];if(elToUncheck)elToUncheck.checked=false;}}
function GetRadioValue(sName){var aEl=elForm[sName];if(aEl){if(aEl.length){for(var i=0;i<aEl.length;i++){if(aEl[i].checked){return aEl[i].value;}}}else{return aEl.value;}}}
oNotifier.Notify(null,"update-count");if(elTable){var aLinks=elTable.getElementsByTagName("a");for(var i=0;i<aLinks.length;i++){if(utils.hasClass(aLinks[i],"details")){utils.addEvent(aLinks[i],"click",function(e){e.preventDefault();var elTbody=getParentElement(this,"tbody");if(utils.hasClass(elTbody,"collapsed")){utils.removeClass(elTbody,"collapsed");utils.addClass(elTbody,"showed");}else{utils.removeClass(elTbody,"showed");utils.addClass(elTbody,"collapsed");}});}}}
var a=$C("line-ctrl");ShowResultsButtons(a.length==0);var b=true;for(var i=0;i<a.length;i++){var elTbody=getParentElement(a[i],"tbody");if(a[i].checked){utils.addClass(elTbody,"highlight");}else{utils.removeClass(elTbody,"highlight");b=false;}}
if(elSelectLines)elSelectLines.checked=b;var elSegment=elForm["segment"];if(elSegment&&elSegment.selectedIndex==-1)elSegment.selectedIndex=0;});function ShowResultsButtons(bShow)
{x_ShowResultsButtons(document.getElementById("cmd_get_accession_group"),bShow);x_ShowResultsButtons(document.getElementById("cmd_get_query_group"),bShow);function x_ShowResultsButtons(el,bShow){if(!el)return;if(bShow){el.style.display="";}else{el.style.display="none";}}}
function getParentElement(obj,tag)
{while(obj.nodeName.toLowerCase()!=tag)obj=obj.parentNode;return obj;}
function VirusChanged(el,sSegment)
{MultiSelectChanged(el);if(sSegment=="R")sSegment="P";var selcount=0;for(var n=0;n<el.options.length;n++)
if(el.options[n].selected)selcount++;if(selcount>1)var sType='any';else var sType=el.options[el.selectedIndex].value;var aSegments=segment[sSegment.toLowerCase()][sType];var elSegment=document.forms["query_form"]["segment"];if(elSegment){utils.removeChildren(elSegment);var elOpt=document.createElement("option");elOpt.value="any";elOpt.selected="selected";elOpt.innerHTML="any";elSegment.appendChild(elOpt);for(var i=0;i<aSegments.length;i++){var elOpt=document.createElement("option");elOpt.value=aSegments[i].v;elOpt.innerHTML=aSegments[i].t;elSegment.appendChild(elOpt);}}
var rsegs=document.getElementsByName("reqseg");for(var i=0;i<rsegs.length;i++)
{var showseg=false;for(var n=0;n<aSegments.length;n++)
if(rsegs[i].value==aSegments[n].v)
{rsegs[i].nextSibling.innerHTML=aSegments[n].t;rsegs[i].disabled=false;rsegs[i].parentNode.style.display='inline';showseg=1;}
if(!showseg&&rsegs[i].value!='complete')
{rsegs[i].checked=false;rsegs[i].disabled=true;rsegs[i].parentNode.style.display='none';}}
document.query_form.reqseg_all.checked=false;}
function MultiSelectChanged(el)
{for(var n=1;n<el.options.length;n++)
if(el.options[n].selected)
{el.options[0].selected=false;break;}};function YourSequence()
{var w=700;var h=300;var x=(screen.width-w)/2;var y=(screen.height-h)/2;var par="dependent=yes,location=no,menubar=no,personalbar=no,resizable=no,status=no,toolbar=no,screenX="+x
+",width="+w+",screenY="+y+",height="+h;win=window.open("?add_fasta=1","FASTA",par);}
function fixSelectFocus(el)
{if(window.opera)return;var s=el.selectedIndex;if(document.all){el.options[s].selected=false;el.options[s].selected=true;return;}
el.options[s].selected=false;el.focus();el.blur();el.options[s].selected=true;}
function cmdGet()
{var elForm=document.forms["query_form"];if(!(window.navigator.userAgent.indexOf("AppleWebKit")!=-1&&window.navigator.userAgent.indexOf("Windows")!=-1)){elForm.target="_blank";setTimeout(function(){elForm.target="_self";},500);}
return true;}
function showhide_href(el,cn)
{var element=document.getElementById(el.href.substr(el.href.lastIndexOf("#")+1));utils.toggleClass(element,cn||"hid");var inpt=document.getElementById("sh_"+el.href.substr(el.href.lastIndexOf("#")+1));if(inpt)
inpt.value=!utils.hasClass(element,cn||"hid");}