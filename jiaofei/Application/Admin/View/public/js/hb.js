


//常用正则表达式
var REG_TELEPHONE = /^1[34578]\d{9}$/;
var REG_VERIFY = /^\d{4}$/;
var REG_PWD = /^\w{6,12}$/;
var REG_CASH = /^\d{1,5}(\.[0-9]{1,2})?$/;
var REG_CARDNO = /^(\d{16})|(\d{19})$/;
var REG_CHANA = /^[\u4E00-\u9FA5]{2,8}$/;
var REG_BANK = /^.{2,40}$/;
var REG_APPLYNAME = /^.{1,10}$/;
var REG_CARCOLOR = /^.{1,6}$/;



//ajax发送请求获取响应数据
//function hb_ajax(url,datas,sucback,errback,type,async){
//	$.ajax({
//		type:type?type:'POST',
//		url:url,
//		data:datas,
//		dataType:'JSON',
//		timeout:8000,
//		success:sucback,
//		error:errback,
//		async:async?async:true
//	});
//}


//ajax发送请求获取响应数据
function hb_ajax(url,datas,sucback,errback,type,async){
//var emp = $api.getStorage('emp'),hds = {};
//if(emp){
//	hds.token = emp.token;
//}

	zep.ajax({
//		headers:hds,//传送<token></token>
		type:type?type:'POST',
		url:url,
		data:datas,
		dataType:'json',
		timeout:8000,
		success:sucback,
		error:errback,
		async:async?async:true
	});
}


