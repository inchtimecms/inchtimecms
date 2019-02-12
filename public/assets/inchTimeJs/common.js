$(function() {

    $(document).ready(function() {

        //获取当前项目的根URL
        var rootURL = window.location.protocol + "//"+ window.location.host;

        //$("#newContentTypeMechineAlias"）change事件
        $("#newContentTypeMechineAlias").change(function(){
            contentTypeAliasIsUnique(rootURL);
        });

        //$("#fieldMachineAlias"）change事件
        $("#fieldMachineAlias").change(function(){
            contentFieldAliasIsUnique(rootURL);
        });

        //$("#newTaxonomyTypeMechineAlias") change
        $("#newTaxonomyTypeMachineAlias").change(function(){
            taxonomyTypeAliasIsUnique(rootURL);
        });

    });

    //判断当前内容类型的机器名是否唯一
    function contentTypeAliasIsUnique(rootURL){

        var getCurrentAliasUniqueUrl = rootURL + "/admin/content/type/entity/alias_unique";

        $.ajax({
            type: "POST",
            data: {edited_alias:$("#newContentTypeMachineAlias").val()},
            url: getCurrentAliasUniqueUrl,
            success: function(data){
                if (data=="1"){
                    $(".alias_repeat").remove();
                    $("#newContentTypeMachineAliasLabel").append('<span class="alias_repeat" style="color:red">' +
                        '机器名重复' +'</span>');
                }
                if (data=="0"){
                    $(".alias_repeat").remove();
                }
            }
        });
    }

    //判断当前字段类型的机器名是否唯一
    function contentFieldAliasIsUnique(rootURL){

        var getCurrentAliasUniqueUrl = rootURL + "/admin/field/type/entity/alias_unique";

        $.ajax({
            type: "POST",
            data: {field_alias:$("#fieldMachineAlias").val()},
            url: getCurrentAliasUniqueUrl,
            success: function(data){
                if (data=="1"){
                    $(".alias_repeat").remove();
                    $("#fieldMachineAliasLabel").append('<span class="alias_repeat" style="color:red">' +
                        '机器名重复' +'</span>');
                }
                if (data=="0"){
                    $(".alias_repeat").remove();
                }
            }
        });
    }

    //判断当前分类词汇的机器名是否唯一
    function taxonomyTypeAliasIsUnique(rootURL){

        var getCurrentAliasUniqueUrl = rootURL + "/admin/taxonomy/type/entity/alias_unique";

        $.ajax({
            type: "POST",
            data: {field_alias:$("#newTaxonomyTypeMachineAlias").val()},
            url: getCurrentAliasUniqueUrl,
            success: function(data){
                if (data=="1"){
                    $(".alias_repeat").remove();
                    $("#newTaxonomyTypeMachineAliasLabel").append('<span class="alias_repeat" style="color:red">' +
                        '机器名重复' +'</span>');
                }
                if (data=="0"){
                    $(".alias_repeat").remove();
                }
            }
        });
    }


    function ajaxMethod($method, $url, $params, $callback_success, $callback_error) {
        return $.ajax({
            type: $method,
            cache: false,
            data: $params,
            url:  $url,
            error: $callback_error,
            success: $callback_success
        });
    };

});