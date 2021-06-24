function toEnd(catId){
	location.href = WST.AU("signup://signup/mend","catId="+catId);
}

function toSubmit(catId){
    location.href = WST.AU("signup://signup/msubmit","catId="+catId);
}
