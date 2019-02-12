//商品添加购物车，付款等操作使用的JS代码
(function ($) {
    "use strict";


    //选择销售属性的事件处理
    $('select[name="group1prop-select"]').on("change", function (e) {

        let parents = $(e.target).parents();
        let rightDiv = parents.children("div.col-lg-5");
        let secondSelect = $(rightDiv).find("select[name='group2prop-select']");
        let salePropValueJson = $(rightDiv).find("div.js-sale-props-value").data("value");
        let wrapNum = $(rightDiv).find("div.wrap-num-product");
        $($(rightDiv).find("div.kucun")).remove();

        if (secondSelect.length === 0) {
            //如果没有第二个销售属性，把当前选中属性的价格改上去。
            let currentSelectValue = $(e.target).find("option:selected").val();

            for (let i = 0; i < salePropValueJson.length; i++) {
                if (salePropValueJson[i][currentSelectValue] !== undefined) {
                    let priceElement = $(rightDiv).find("div.discount span");
                    $(priceElement).text("¥:" + salePropValueJson[i][currentSelectValue].price);
                    //添加库存
                    let numbetHtml = '<div class="kucun"><small>库存:</small><small class="kucun-num">' + salePropValueJson[i][currentSelectValue].number + '</small></div>'
                    $(wrapNum).after(numbetHtml);
                    //把input最大值改为库存
                    $($(wrapNum).find("input[type=number]")).attr("max", salePropValueJson[i][currentSelectValue].number);
                }
            }
        }
    });
    //第二组销售属性选择事件
    $('select[name="group2prop-select"]').on("change", function (e) {

        let parents = $(e.target).parents();
        let rightDiv = parents.children("div.col-lg-5");
        let salePropValueJson = $(rightDiv).find("div.js-sale-props-value").data("value");
        let wrapNum = $(rightDiv).find("div.wrap-num-product");
        $($(rightDiv).find("div.kucun")).remove();
        //第一组销售属性的值
        let group1SelectValue = $($(rightDiv).find("select[name='group1prop-select']")).val();
        //第二个销售属性的值，把当前选中属性的价格改上去。
        let currentSelectValue = $(e.target).find("option:selected").val();

        for (let i = 0; i < salePropValueJson.length; i++) {
            if (salePropValueJson[i][group1SelectValue + ':' + currentSelectValue] !== undefined) {
                let priceElement = $(rightDiv).find("div.discount span");
                $(priceElement).text("¥:" + salePropValueJson[i][[group1SelectValue + ':' + currentSelectValue]].price);
                //添加库存
                let numbetHtml = '<div class="kucun"><small>库存:</small><small class="kucun-num">' + salePropValueJson[i][[group1SelectValue + ':' + currentSelectValue]].number + '</small></div>'
                $(wrapNum).after(numbetHtml);
                //把input最大值改为库存
                $($(wrapNum).find("input[type=number]")).attr("max", salePropValueJson[i][[group1SelectValue + ':' + currentSelectValue]].number);
            }
        }

    });

    //添加购物车按钮点击事件
    $("button.js-addcart-detail").on("click", function (e) {
        e.preventDefault();
        let parents = $(e.target).parents();
        let rightDiv = parents.children("div.col-lg-5");
        //用户是否选择过销售属性
        //第一组销售属性的值
        let group1SelectValue = $($(rightDiv).find("select[name='group1prop-select']")).val();
        if (group1SelectValue === "") {
            swal("出错了!", "您还没有选择销售属性,请选择后再添加购物车。", "error");
        }
        //第二组销售属性的值
        let group2SelectValue = $($(rightDiv).find("select[name='group2prop-select']")).val();
        if (group2SelectValue === "") {
            swal("出错了!", "您还没有选择销售属性,请选择后再添加购物车。", "error");
        }
        //判断用户选择的数量大不大于库存
        let number = $(rightDiv).find("div.wrap-num-product input").val();
        let kucun = $(rightDiv).find("div.kucun small.kucun-num").text();
        if (parseInt(number) > parseInt(kucun)) {
            swal("出错了!", "您选择的数量大于库存了,请修改后再添加购物车。", "error");
        }

        //获取表单数据提交ajax请求
        $.ajax({
            type: "POST",
            data: $(rightDiv).find("form.js-add-cart-form").serializeArray(),
            url: $(rightDiv).find("form.js-add-cart-form").data("action")
        }).done(function (response) {
            swal("成功了!", response.message, "success");
        }).fail(function (jqXHR){
            if (jqXHR.responseJSON.message === "用户未登录"){
                window.location.href= jqXHR.responseJSON.loginPath;
            }else{
                swal("出错了!", jqXHR.responseJSON.message, "error");
            }
        });

    });

    /*==================================================================
        [ Cart ]*/
    //显示购物车sidebar取消, 直接转到购物车页面
    // $('.js-show-cart').on('click',function(e){
    //     //AJAX获取购物车中商品
    //     $.ajax({
    //         type: "POST",
    //         url: $(e.target).data("cart"),
    //     }).done(function(response){
    //         console.log(response);
    //         let allCartHtml="";
    //         for (let i=0;i<response.length;i++){
    //             let singleCartItemPrice = 12;
    //             let singleCartItem = '<li class="header-cart-item flex-w flex-t m-b-12">' +
    //                 '                    <div class="header-cart-item-img">' +
    //                 '                        <img src="'+ response[i].productContentImg +'" alt="'+ response[i].productContentTitle +'">' +
    //                 '                    </div>' +
    //                 '                    <div class="header-cart-item-txt p-t-8">' +
    //                 '                        <a href="" class="header-cart-item-name m-b-5 hov-cl1 trans-04">' + response[i].productContentTitle + '</a>' +
    //                 '                        <span class="header-cart-item-info">' + response[i].productChoiceProp +' </span>' +
    //                 '                        <span class="header-cart-item-info">' + response[i].number + 'x ¥'+ singleCartItemPrice +' </span>' +
    //                 '                    </div>' +
    //                 '                </li>';
    //             allCartHtml = allCartHtml + singleCartItem;
    //         }
    //         $("div.header-cart-content ul.header-cart-wrapitem").append(allCartHtml);
    //     });
    //
    //     $('.js-panel-cart').addClass('show-header-cart');
    // });
    //
    // $('.js-hide-cart').on('click',function(){
    //     $("div.header-cart-content ul.header-cart-wrapitem").empty();
    //     $('.js-panel-cart').removeClass('show-header-cart');
    // });

    $('.js-show-cart').on('click',function(e){
        window.location.href= $(e.target).data("cart");
    });



})(jQuery);