//이곳은 어디인가? 이곳은 각종 유효성을 담당하거나 폼을 바꾸는 js입니다. 

function pEmailChk(value){//이메일 형식체크
	var mailChk=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if(!mailChk.test(value)){return false;}else{return true;}
}

function pDateChk(value){//날짜 형식체크
	var date = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/gi;
	if(date.test(value)===true){return true;}else{return false;}
}


function pSpecialWordChk(value){//특수문자가 들어있나체크
	var specialRule = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
	if(specialRule.test(value)===true){return true;}else{return false;}
}

function pKoreanChk(value){//한글이 들어있나체크
	var korean = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/gi;
	if(korean.test(value)===true){return true;}else{return false;}
}

function pEnglishChk(value){//영어가 들어있나체크
	var english = /[a-zA-Z]/gi; 
	if(english.test(value)===true){return true;}else{return false;}
}
function pNumberChk(value){//숫자가 들어있나체크
	var numb=/\d/gi;
	if(numb.test(value)){return true;}else{return false;}
}

function pEmptyChk(value){//공백이 있는지 체크
	if(value.search(/\s/) != -1){return true;}else{return false;}
}


function pKoreanRemove(value){//한글제거
	var korean = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/gi;
	return value.replace(korean, "");	
}
function pEnglishRemove(value){//영어제거
	var english = /[a-zA-Z]/gi; 
	return value.replace(english, "");	
}

function pEmptyRemove(value){//공백제거
	return value.replace(/ /gi,"");	
}

function pSpecialWordRemove(value){//특수문자가 들어있나체크
	var specialRule = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
	return value.replace(specialRule, "");	
}
function pNumberRemove(value){//숫자가 들어있나체크
	var numb=/\d/gi;
	return value.replace(numb, "");	
}


function pPhoneMaker(value){//전화번호형식으로 만들기
	return value.replace(/[^0-9]/g, "").replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
}
function pFaxMaker(value){//팩스형식으로 만들기
	return value.replace(/[^0-9]/g, "").replace(/(^02.{0}|^01.{1}|050.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
}

function pBusinessNumMaker(value){//사업자등록번호로 만들기
	return value.replace(/[^0-9]/g, "").replace(/(\d{3})(\d{2})(\d{5})/, '$1-$2-$3');
}

function pDateMaker(value){
	return value.replace(/[^0-9]/g, "").replace(/(\d{4})(\d{2})(\d{2})/g, '$1-$2-$3');
}



