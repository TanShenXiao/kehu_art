jQuery.noConflict();
var currPage = totalPage = 0;
var loading = false;

function toDesc(catId){
	location.href = WST.AU("vote://vote/wdesc","catId="+catId);
}

function toSubmit(catId){
    location.href = WST.AU("vote://vote/witemlist","catId="+catId);
}
