(function () {
    var loading = false;
    $(document.body).infinite().on("infinite", function() {
        if(loading) return;
        loading = true;
        setTimeout(function() {
            $("#list-1").append('<div class="order-item">'+
                '<div class="weui_cells">'+
                '<div class="weui_cell">'+
                '<div class="weui_cell_hd" style="margin-top: -5px;">'+
                '<img src="images/huati_18.png" alt="icon" style="width:30px;border-radius: 30px;margin-right:10px;display:block">'+
                '</div>'+
                '<div class="weui_cell_bd weui_cell_primary" style="color:#9d9d9d;">'+
                '道 <span style="padding:0px 7px;margin-left:5px;font-size: 12px; background:#f8bf4d;color:#fff;display: inline-block;">LV<b style="font-weight: normal;">1</b></span>'+
                '<div style="margin-top: 10px;"><span class="gray" style="font-size: 14px;color:#777;">说的好啊！</span></div>'+
                '</div>'+
                '<div class="weui_cell_ft gray">'+
                '<san>4楼</san>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '<div class="weui_cells dj">'+
                '<a class="weui_cell" href="javascript:;">'+
                '<div class="weui_cell_bd weui_cell_primary">'+
                '<p style="color:#d3d3d3;display: inline-block;">刚刚</p><span style="color:#7285a9;margin-left:10px;">删除</span>'+
                '</div>'+
                '<div class="weui_cell_ft">'+
                '<img src="images/tiyu_11.png" alt="">'+
                '<img src="images/tiyu_13.png" alt="">'+
                '<img src="images/tiyu_15.png" alt="">'+
                '</div>'+
                '</a>'+
                '</div>'+
                '</div>');
            loading = false;
        }, 2000);
    });
})();

$("span:contains('取消')").click(function(){
    $('.modal').css('display','none');
})

window.onload = function() {
    var from = sessionStorage.getItem("from");
    if(from == 'pageA') {
        //balabala  要触发的点击事件  $('#xxx').click()
        sessionStorage.setItem("from",""); //销毁 from 防止在b页面刷新 依然触发$('#xxx').click()
        var timer=setInterval(function(){
            $('.modal').css('display','block');
            clearInterval(timer);
        },500);
    }
}