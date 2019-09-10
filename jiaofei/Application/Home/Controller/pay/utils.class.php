<?php

class utils {
	const id="partner1";
	const aes_key="AD42F6697B035B7580E4FEF93BE20B4D";
	const sign_key="weiweixiao";
	const notifyUrl = "http://127.0.0.1/xykOrder.php";
	const returnUrl = 'https://notify.zhizhixao.com/return.html';
	const creatrOrderUrl="http://api.weiweixiao.net/pay_api/order_create";
	const selectOrderUrl="http://api.weiweixiao.net/pay_api/order_query";
	const cancleOrderUrl="http://api.weiweixiao.net/pay_api/order_cancel";
}
?>