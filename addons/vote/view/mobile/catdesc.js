jQuery.noConflict();
var currPage = totalPage = 0;
var loading = false;

function toDesc(catId){
	location.href = WST.AU("vote://vote/mdesc","catId="+catId);
}

function toSubmit(catId){
    location.href = WST.AU("vote://vote/mitemlist","catId="+catId);
}
