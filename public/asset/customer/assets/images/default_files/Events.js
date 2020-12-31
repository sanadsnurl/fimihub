function mOver(src, bgclr){ 
	if (!src.contains(event.fromElement)){ 
		src.style.cursor = 'hand';
		src.style.color = bgclr;
	} 
}

function mOut(src,bgclr){
	if (!src.contains(event.toElement)){ 
		src.style.cursor = 'default';
		src.style.color = bgclr;
	} 
}

function mOverBg(src, bgclr){ 
	if (!src.contains(event.fromElement)){ 
		src.style.cursor = 'hand';
		src.style.backgroundColor = bgclr;
	} 
}

function mOutBg(src,bgclr){
	if (!src.contains(event.toElement)){ 
		src.style.cursor = 'default';
		src.style.backgroundColor = bgclr;
	} 
}

function mOverCursorOnly(src){ 
	if (!src.contains(event.fromElement)){ 
		src.style.cursor = 'hand';
	} 
}

function mOutCursorOnly(src){
	if (!src.contains(event.toElement)){ 
		src.style.cursor = 'default';
	} 
}

function AddErrorMsg(Msg)
{
	parent.frames["error"].AddError(Msg);
}
