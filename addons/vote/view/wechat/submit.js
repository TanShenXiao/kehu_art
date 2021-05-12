function toEnd(catId){
	location.href = WST.AU("signup://signup/wend","catId="+catId);
}

function toSubmit(catId){
    location.href = WST.AU("signup://signup/wsubmit","catId="+catId);
}
