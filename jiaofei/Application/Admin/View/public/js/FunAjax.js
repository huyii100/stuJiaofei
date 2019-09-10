//封装ajax请求
function funajax(Url,datas,fun){
	$.ajax({
    	type:'post',
    	url:Url,
    	async:true,
    	data:datas,
    	dataType : 'json',
    	success:fun,
    	error:function(){
			console.log('系统故障 未能请求后台');
    	}
    });
}
