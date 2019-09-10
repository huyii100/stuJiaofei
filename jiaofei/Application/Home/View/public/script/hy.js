




 


    var toast = new auiToast();
    
    
    function hy_toast(msg,type){
      
        switch (type) {
            case "success":
                toast.success({
                    title:msg,
                    duration:2000
                });
                break;
            case "fail":
                toast.fail({
                    title:msg,
                    duration:2000
                });
                break;
            case "custom":
                toast.custom({
                    title:"提交成功",
                    html:'<i class="aui-iconfont aui-icon-laud"></i>',
                    duration:2000
                });
                break;
            case "loading":
                toast.loading({
                    title:"加载中",
                    duration:2000
                },function(ret){
                    console.log(ret);
                    setTimeout(function(){
                        toast.hide();
                    }, 3000)
                });
                break;
            default:
                // statements_def
                break;
        }
    }



