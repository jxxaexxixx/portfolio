$(document).ready(function() {
	var body=$('body');
	$(document).on("click", function(event){
        if (!$(event.target).closest(".j_select_wrap").length) {
          	$(".j_select_wrap").removeClass("active");
        }
    });
    body.on('click','.j_select_box li',function(e){
		e.preventDefault();
		var tmpThis = $(this);
	  	var selectedText = tmpThis.text();
		var selVal = tmpThis.attr('data-value');

	  	tmpThis.closest('.j_select_box').siblings('.j_select_btn').find('span').text(selectedText);
	  	tmpThis.closest('.j_select_box').siblings('.j_select_btn').find('span').attr('data-val', selVal);
	  	tmpThis.closest('.j_select_wrap').removeClass('active');

	  	var aa=tmpThis.closest('.j_select_box').siblings('.j_select_btn').find('span').attr('data-val');
	});
	body.on('click','.j_select_btn',function(){
		$('.j_select_wrap').removeClass('active');
		$(this).closest('.j_select_wrap').toggleClass('active');
	});

	body.on('click','.j_chk_btn',function(){
		var tmpThis = $(this);
		if(tmpThis.hasClass('checked')){
			tmpThis.removeClass('checked');
			tmpThis.find('i').removeClass('xi-check-circle');
			tmpThis.find('i').addClass('xi-radiobox-blank');
		}else{
			tmpThis.addClass('checked');
			tmpThis.find('i').addClass('xi-check-circle');
			tmpThis.find('i').removeClass('xi-radiobox-blank');
		}
	});

	var pClipboard=new ClipboardJS('.p_clipboard_btn');
	pClipboard.on('success', function(e) {
		var clipboardAction = e;
		pToastr('클립보드에 복사되었습니다.','500px');
	});

	$.datepicker.setDefaults({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		prevText: 'Last Month',
		nextText: 'Next Month',
		monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		showMonthAfterYear: true,
		yearSuffix: 'Year',
	});

	body.on('click', 'thead th.p_td_chk', function(){
		var targetTh = $(this);
		var targetTable = targetTh.parents('table.p_datatable');
		if(targetTh.hasClass('p_td_checked')){
			targetTh.removeClass('p_td_checked');
			targetTable.find('td.p_td_checked').removeClass('p_td_checked');
			targetTable.find('tr.p_tr_active').removeClass('p_tr_active');
		}else{
			var tempLen = targetTable.find('td.p_td_chk').length;
			for (var i = 0; i < tempLen; i++) {
				var tempThisTd=$(targetTable.find('td.p_td_chk')[i]);
				if(tempThisTd.hasClass('p_no_mjs')===false){
					tempThisTd.addClass('p_td_checked');
					tempThisTd.parents('tr').addClass('p_tr_active');
				}
			}
			if(targetTable.find('td.p_td_checked').length==targetTable.find('td.p_td_chk').length){
				if(targetTable.find('th.p_td_checked').length===0){
					targetTable.find('th.p_td_chk').addClass('p_td_checked');
				}
			}
		}
	});

	body.on('click', 'tbody td.p_td_chk', function(){
		var targetTd = $(this);
		if(targetTd.hasClass('p_no_mjs')===false){
			var targetTr = targetTd.parent('tr');
			var targetTable = targetTd.parents('table.p_datatable');
			if(targetTd.hasClass('p_td_checked')){
				targetTd.removeClass('p_td_checked');
				targetTr.removeClass('p_tr_active');
				if(targetTable.find('th.p_td_checked').length){
					targetTable.find('th.p_td_checked').removeClass('p_td_checked');
				}
			}else{
				targetTd.addClass('p_td_checked');
				targetTr.addClass('p_tr_active');
				if(targetTable.find('td.p_td_checked').length==targetTable.find('td.p_td_chk').length){
					if(targetTable.find('th.p_td_checked').length===0){
						targetTable.find('th.p_td_chk').addClass('p_td_checked');
					}
				}
			}
		}

	});

	body.on('click', '.p_table tbody tr', function(e){
		if($(e.target).hasClass('p_clipboard_btn') || $(e.target.parentElement).hasClass('p_clipboard_btn')){
		}else{
			var targetTr = $(this);
			var tr = $('tbody tr');
			if($('tbody tr').hasClass('p_tr_active')){
				$('tbody tr').removeClass('p_tr_active');
			}
			targetTr.addClass('p_tr_active');
		}
	});

	var pTableLang={
		"decimal" : "No data available.",
		// "emptyTable" : "dddddd.",
		"info" : "<span class='f_normal p_dt_info'> _START_ - _END_ (총 _TOTAL_ ) </span>",
		"infoEmpty" : "0명",
		// "infoFiltered" : "(전체 _MAX_ 명 중 검색결과)",
		"infoFiltered" : "(Search results out of _MAX_ total entries)",
		"infoPostFix" : "",
		"thousands" : ",",
		// "lengthMenu" : "_MENU_ 개씩 보기",
		"lengthMenu" : '<span class="f_normal p_dt_lengthTxt">Show </span><select class="p_select p_dt_lengthSelect">'+
				'<option value="10">10</option>'+
				'<option value="20">20</option>'+
				'<option value="50">50</option>'+
				'<option value="-1">All</option>'+
				'</select> <span class="f_normal p_dt_lengthTxt">per page</span>',
		"loadingRecords" : "Loading...",
		"processing" : "Querying...",
		"search" : "Search  ",
		// "search" : "",
		"zeroRecords" : "No search results found.",
		"paginate" : {
			"first" : "",
			"last" : "",
			"next" : "",
			"previous" : ""
		},
		"aria" : {
			"sortAscending" : " :  Ascending order",
			"sortDescending" : " :  Descending order"
		}
	}
});

function switchWrap(thisBtn, btnClassName, wrapClassName) {
    var thisType = thisBtn.attr('data-type');
    var length = thisBtn.siblings().length + 1;
    var wrap = $('.' + wrapClassName + '');
    var btn = $('.' + btnClassName + '');
    var btnActive = btnClassName + '_active';
    for (var i = 0; i < length; i++) {
        var wrapType = wrap.eq(i).attr('data-type');
        if (wrapType == thisType) {
            wrap.eq(i).removeClass('display_none');
            thisBtn.addClass(btnActive);
        } else {
            wrap.eq(i).addClass('display_none');
            btn.eq(i).removeClass(btnActive);
        }
    }
}

function NowTime(){
	var currentTime = new Date();
	var year = currentTime.getFullYear();
	var month = (currentTime.getMonth() + 1).toString().padStart(2, '0');
	var day = currentTime.getDate().toString().padStart(2, '0');
	var hours = currentTime.getHours().toString().padStart(2, '0');
	var minutes = currentTime.getMinutes().toString().padStart(2, '0');
	var seconds = currentTime.getSeconds().toString().padStart(2, '0');
	var formattedDateTime = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

	return formattedDateTime;
}

function pLoadingOn(){
	if($('.p_loading_wrap').length){
		$('.p_loading_wrap').remove();
	}
	var loadingBox=$('<div class="p_loading_wrap"><div class="p_loading_bg"></div><div class="p_loading_box"><img class="p_loading" src="'+DomainUri+'/source/img/loading.gif" /></div></div>').appendTo("body");
	$('.p_loading_wrap').show();
	$('.p_loading_bg').animate({ opacity: '1' }, 200, 'easeOutQuart', function(){});
	$('.p_loading_box').addClass('p_loading_box_open');
}

function pLoadingOff(){
	if($('.p_loading_wrap').length){
		$('.p_loading_bg').animate({ opacity: '0' }, 200, 'easeInQuart', function(){
			$('.p_loading_wrap').remove();
		});
		$('.p_loading_box').removeClass('p_loading_box_open');
	}
}

function pComma(_num,_len) {//콤마 찍기,_len=소수점 자리수 제한
	var regx=new RegExp(/(-?\d+)(\d{3})/);
	_num=_num + '';//.indexOf is not a function 에러남
	var bExists=_num.indexOf(".", 0);//0번째부터 .을 찾는다.
	var strArr=_num.split('.');
	while(regx.test(strArr[0])){//문자열에 정규식 특수문자가 포함되어 있는지 체크
		strArr[0]=strArr[0].replace(regx, "$1,$2");//정수 부분에만 콤마 달기
	}

	if(bExists > -1){//. 소수점 문자열이 발견되지 않을 경우 -1 반환
		 // 기본 소수점 자율, _len=0일땐 정수만, _len=숫자 소수점자리제한 버전

		// _len=숫자 소수점자리제한 그외 정수만
		if(_len*1>=1){
			_num=strArr[0] + "." + strArr[1].substr(0,_len);
		}else{
			_num=strArr[0];
		}
	}else{ //정수만 있을경우 //소수점 문자열 존재하면 양수 반환
		_num=strArr[0];
	}
	return _num;//문자열 반환
}

function pUncomma(_num,_len) {//콤마 풀기
	_num=_num + '';//
	_num = "" + _num.replace(/,/gi, ''); // 콤마 제거
	_num = _num.replace(/(^\s*)|(\s*$)/g, ""); // trim()공백,문자열 제거
	_num=_num + '';//.indexOf is not a function 에러남
	var bExists=_num.indexOf(".", 0);//0번째부터 .을 찾는다.
	var strArr=_num.split('.');
	if(bExists > -1){//. 소수점 문자열이 발견되지 않을 경우 -1 반환
		// _len=숫자 소수점자리제한 그외 정수만
		if(_len*1>=1){
			_num=strArr[0] + "." + strArr[1].substr(0,_len);
		}else{
			_num=strArr[0];
		}
	}else{ //정수만 있을경우 //소수점 문자열 존재하면 양수 반환
		_num=strArr[0];
	}
	return _num*1;
}

function pIsVisible(EL,wrap){
	var $elem=EL;
	var $window=wrap;
	var docViewTop=$window.scrollTop();
	var docViewTop=$window.offset().top;
	var docViewBottom=docViewTop + $window.height();
	var elemTop=$elem.offset().top;
	var elemBottom=elemTop + $elem.height();
	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}


function pToastr(msg,uri){
	toastr.options = {
		// "closeButton": true,
		"closeButton": false,
		"debug": false,
		"progressBar": true,
		"positionClass": "toast-bottom-center",
		"showDuration": "200",
		"hideDuration": "200",
		"preventDuplicates" : true,
		// "timeOut": 300000,
		"timeOut": 3000,
		"extendedTimeOut": 6000,
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut",
		"onclick": function(){
		}
	};
	toastr.info('<span class="f_normal">'+msg+'</span>', '');
}

function pAlert(txt,width,action) {//
	var backgroundDismissVal=true;
	if(width==undefined){
		width='300px'
	}
	if (typeof action === "function") {
			backgroundDismissVal=action;
	}else{
		action="";
	}
	$.alert({
		title: '안내',
		content: '<span class="p_confirm_span">'+txt+'</span>',
		boxWidth:width,
		backgroundDismiss:backgroundDismissVal,
		buttons: {
			'confirm': {
				text: '닫기',
				btnClass: '',
				keys:['enter'],
				action:action
			}
		},
	});
}


function pTableInitComplete(thisTable,thSearchStatus,goUrlDetail) {//
	var thisTableEL = '#'+thisTable[0]['id'] ;
	if($.fn.DataTable.isDataTable(thisTableEL)){
		if(thSearchStatus=='y'){
			pTableThSearchInitComplete(thisTable);
		}
		if($(thisTableEL+'_filter input').length){
			$(thisTableEL+'_filter input').addClass('p_dt_searchInput').attr('placeholder','Enter a search term');
		}

		if(goUrlDetail){
			var renderidx =goUrlDetail*1;
			var dataTable =$('#'+thisTable[0]['id']).DataTable();
			if(renderidx){
			    var pos = dataTable.column(0).data().indexOf(renderidx+'');
			    if (pos >= 0) {
			        var page = Math.floor(pos / dataTable.page.info().length);
			        dataTable.page(page).draw(false);
    				var rowIdx = dataTable.cell( function ( idx, data, node ) {
            			if(idx.column===0 && data == renderidx){
            				// console.log(idx);
            				// console.log(data);
            				return true;
            			}else{
            				return false;
            			}
    				}).index().row;
    				dataTable.rows( rowIdx ).nodes().to$().children('.p_td_rowNumber').trigger('click');
			    }
			}
		}
	}
	$(thisTableEL).siblings('.dataTables_filter,.dataTables_length').addClass('p_incidental');
	$(thisTableEL).wrap('<div class="p_table_wrap overflowScrollbar"></div>');
	$(thisTableEL).parents('.p_table_wrap').siblings('.p_incidental').wrapAll('<div class="p_incidental_wrap"></div>');
}

function pTableThSearchInitComplete(thisTable){
	//데이터테이블 th 검색창 만들기
	$('#'+thisTable[0]['id']+' thead th').each( function () {
		var thisTh = $(this);
		var thisTitle = thisTh.text();
		if(thisTh.is('.p_th_search_false, .p_th_search_f, .p_td_chk, .p_td_del, .p_td_move')){
			thisTh.html('<span>'+thisTitle+'</span>');
		}else if(thisTh.is('.p_th_search_date')){
			thisTh.addClass('p_th_search').html( '<div class="p_th_input_box"><span class="p_th_input_txt">'+thisTitle+'</span><input class="p_th_input p_th_input_date" type="text" /></div>' );
		}else if(thisTh.is('.p_th_search_sel')){

			thisTh.html('<span>'+thisTitle+'</span>');
			if(thisTh.attr('data-opop') && thisTh.attr('data-opop').length>3){
				var tmpOpop=thisTh.attr('data-opop');
				var tmpOpopArr = tmpOpop.split(',');
				if(Array.isArray(tmpOpopArr)===true){
					thisTh.empty();
					var tmpOpopData='';
					for (var i = 0; i < tmpOpopArr.length; i++) {
						var tmpTxt=tmpOpopArr[i];
						if (tmpTxt == 'All' || tmpTxt == 'junmo' || tmpTxt == '전체' || tmpTxt == 'ALL'){
							tmpOpopData+='<option value="">'+tmpTxt+'</option>';
						}else{
							tmpOpopData+='<option value="'+tmpTxt+'">'+tmpTxt+'</option>';
						}
					}
					thisTh.addClass('p_th_search').html( '<div class="p_th_input_box"><span class="p_th_input_txt">'+thisTitle+'</span><select class="p_th_input p_th_input_sel">'+tmpOpopData+'</select></div>' );
					thisTh.removeAttr('data-opop');
				}
			}
		}else{
			thisTh.addClass('p_th_search').html( '<div class="p_th_input_box"><span class="p_th_input_txt">'+thisTitle+'</span><input class="p_th_input" type="text" /></div>' );
		}
	});

	thisTable.api().columns().every( function () {
		var thisColumns = this;
		$('.p_th_input', this.header()).on('keyup change clear cut paste', function(){
			var thisThat=this;
			(function(thisThat){
				setTimeout(function() {
					var thisThatFn=$(thisThat);
					if(thisThatFn.hasClass('p_th_input_sel')===false){
						if(thisThat.value.length>0){
							if(thisThatFn.next('.p_th_input_clear').length===0){
								thisThatFn.after('<a class="p_th_input_clear" href="javascript:void(0)">X</a>');
							}
						}else{
							if(thisThatFn.next('.p_th_input_clear').length){
								thisThatFn.next('.p_th_input_clear').remove();
							}
						}
						//데이터테이블 th 검색창 값있을때 x 튀어나오는데 클릭하면 인풋벨류 비워버림
						$('.p_th_input_clear').on('click', function(e){
							e.stopPropagation();
							var thisInput = $(this).prev('.p_th_input');
							thisInput.val('').trigger('change');
						});
					}
					if (thisColumns.search()!==thisThat.value){
						if(thisThatFn.hasClass('p_th_input_sel')){
							// thisColumns.search(thisThat.value).draw();
							// thisColumns.search('^' + thisThat.value + '$', true, false).draw();
							if (thisThat.value) {
	    					    thisColumns.search('^' + thisThat.value + '$', true, false).draw();
	    					} else {
	    					    thisColumns.search('').draw();
	    					}
						}else{
							thisColumns.search(thisThat.value).draw();
								
						}
					}
				}, 100);
			}(thisThat));
		});
	});
	//데이터테이블 th 검색창 클릭했을때 오더레이블 무시
	$('.p_th_input').on('click', function(e){
		e.stopPropagation();
		$(this).focus();//colReorder 이것때문에 강제로 포커스를 줘야함 20211014
	});
	if($('.p_th_input_date').length){
		$('.p_th_input_date').datepicker({
			//
		});
	}
}



function pOsChk() {
	//모바일인지 아닌지
	var mobile = (/iphone|ipad|ipod|android/i.test(navigator.userAgent.toLowerCase()));
	if (!mobile) { return false; }
	//안드로이드인지 아이오에스인지
	var osChk = navigator.userAgent.toLowerCase();
	if (osChk.indexOf("android") > -1) {
		return "android";
	} else if (osChk.indexOf("iphone") > -1 || osChk.indexOf("ipad") > -1 || osChk.indexOf("ipod") > -1) {
		return "ios";
	} else {
		return false;
	}
}

const pOs = pOsChk();

var iosClient = {};
iosClient.webToApp = (funcName, paramObj) => {
	window.webkit.messageHandlers[funcName].postMessage(paramObj);
}

function pGetAppData(funcName, paramObj) {
	if (pOs == 'ios') {
		iosClient.webToApp(funcName, paramObj);
	} else if (pOs == 'android') {
		paramObj = JSON.stringify(paramObj);
		var thisReturn = webToApp[funcName](paramObj);
	}
}

function ChatAttach() { pGetAppData('chatAttach', 'hi') };
