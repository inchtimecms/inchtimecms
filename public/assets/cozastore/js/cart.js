(function ($) {
    "use strict";
    $(document).ready(function () {
        //购物车页面加载完毕后,处理当条商品的价格 及 库存显示情况
        $(".table-shopping-cart .table_row").each(function () {
            let choiceProp = $(this).data("choicesaleprop");
            let productprops = $(this).data("productprops");
            let choicePropArray = choiceProp.split(" ");

            if (choicePropArray[1] === "") {
                for (let i = 0; i < productprops.length; i++) {
                    let data = productprops[i][choicePropArray[0]];
                    if (data !== undefined) {
                        $(this).find("td.column-3").text("¥:" + data.price);
                        $(this).find("input.num-product").attr("max", data.number);
                        $(this).find("td.column-4 .small").text("库存:" + data.number);
                        let cartNumber = $(this).find("input.num-product").val();
                        $(this).find("td.column-5 span").text(parseFloat(data.price * cartNumber).toFixed(2));

                    }
                }

            } else {
                for (let i = 0; i < productprops.length; i++) {
                    let data = productprops[i][choicePropArray[0] + ":" + choicePropArray[1]];
                    if (data !== undefined) {
                        $(this).find("td.column-3").text("¥:" + data.price);
                        $(this).find("input.num-product").attr("max", data.number);
                        $(this).find("td.column-4 .small").text("库存:" + data.number);
                        let cartNumber = $(this).find("input.num-product").val();
                        $(this).find("td.column-5 span").text(parseFloat(data.price * cartNumber).toFixed(2));

                    }
                }
            }
        });

        //处理商品总价
        let priceSpan = $(".table-shopping-cart .table_row td.column-5 span")

        let products_price = 0;
        for (let i = 0; i < priceSpan.length; i++) {
            products_price = products_price + parseFloat($(priceSpan[i]).text());
        }

        $("span.products-price span").text( parseFloat(products_price).toFixed(2) );

        //处理加运费后的总价
        $("span.total_price").text($("span.products-price span").text());

        //立即结算按钮点击后弹出对话框
        $("button#check_order").on("click",function(e){
            e.preventDefault();
            swal("说明!", "结算时使用支付宝或微信支付功能为付费功能,请联系微信：3300476467 ", "info");

            //提交Ajax创建结算订单
            var form = $("form#cart-form");
            console.log(form.serializeArray());
            console.log(form.data("action"));
            $.ajax({
                type: "POST",
                data: form.serializeArray(),
                url: form.data("action")
            }).done(function (response) {
                console.log(response);
                // swal("成功了!", response.message, "success");
            }).fail(function (jqXHR){
                // if (jqXHR.responseJSON.message === "用户未登录"){
                //     window.location.href= jqXHR.responseJSON.loginPath;
                // }else{
                //    swal("出错了!", jqXHR.responseJSON.message, "error");
                // }
            });

        });

    });

})(jQuery);