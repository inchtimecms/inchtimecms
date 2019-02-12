//去除头尾空格
function trim(element){
    $(element).val($.trim($(element).val()));
}

$(function() {

    $(document).ready(function() {
        //获取当前项目的根URL
        var rootURL = window.location.protocol + "//"+ window.location.host;
        //使用Ajax技术获取所有字段类型值FieldTypeValue类对应的表的所有值，然后解析添加到Select控件
        var getFieldTypeValuesUrl = rootURL + "/admin/field/type/value/findall";

        $.ajax({
            type: "GET",
            url: getFieldTypeValuesUrl,
            success: function(data){
                // console.log(data);
                var valueTypeArray = Array();
                //第一遍查找所有的字段分类
                for(var i=0; i<data.length; i++){
                    if(valueTypeArray[valueTypeArray.length - 1] !== data[i]['fieldTypeValueType']){
                        valueTypeArray.push(data[i]['fieldTypeValueType']);
                    }
                }

                //第二遍组织html代码
                var optgroupText = '';
                for(var i=0; i<valueTypeArray.length; i++ ){
                    optgroupText = optgroupText + '<optgroup label="' + valueTypeArray[i] +'">';
                    for(var j=0; j<data.length; j++){
                        if(valueTypeArray[i] === data[j]['fieldTypeValueType'])
                        {
                            optgroupText = optgroupText + '<option data-name="' + data[j]['fieldValueTypeName'] + '" value="' + data[j]['id'] + '">'
                                +data[j]['fieldValueTypeName']+'</option>';
                        }
                    }
                    optgroupText = optgroupText + '</optgroup>';
                }
                // console.log(optgroupText);
                $("#newContentTypeFieldSelect").append(optgroupText);
            }
        });

        //当选择字字段类型时，动态添加字段的设置参数部分
        var fileAndImgSettingHtml = '<div class="fields_wrapper field_setting">' +
            '                        <div class="form-group m-form__group">' +
            '                            <label>允许的文件扩展名(必填):</label>' +
            '                            <input type="text" class="m-input" onblur="trim(this)" required="required" name="fieldsetting:file_extension">' +
            '                            <span class="m-form__help">用空格隔开后缀名，不包括后缀名前面的点,例如：图像字段输入 jpg png 文件字段输入 doc docx等。</span>' +
            '                        </div>' +
            '                    </div>';

        //当选择字段为小数类型时，动态添加字段设置部分
        var fieldDecimalSettingsHtml = ' <div class="fields_wrapper field_setting">' +
            '                            <div class="form-group m-form__group">' +
            '                                <label>小数点后保留位数(必填):</label>' +
            '                                <input type="number" class="m-input" min="1" onblur="trim(this)" required="required" name="fieldsetting:decimal">' +
            '                            </div>' +
            '                        </div>';

        //当选择字段类型为布尔值时，动态添加字段设置代码
        var fieldBoolSettingsHtml = '<div class="fields_wrapper field_setting">' +
            '                            <div class="form-group m-form__group">' +
            '                                <label>布尔为“真”时显示的标签：</label>' +
            '                                <input type="text" class="m-input" onblur="trim(this)" required="required" name="fieldsetting:bool:true">' +
            '                                <label>布尔为“假”时显示的标签：</label>' +
            '                                <input type="text" class="m-input" onblur="trim(this)" required="required" name="fieldsetting:bool:false">' +
            '                            </div>' +
            '                            <div class="form-group m-form__group bool_example">' +
            '                                <div>布尔值示例：</div>' +
            '                                <label class="field_title m--margin-right-20">上下架（字段标签）:</label>' +
            '                                <input type="radio" checked="checked" class="m-input" value=1 name="bool_example"><label class="true">上架（为真时显示的标签）</label>' +
            '                                <input type="radio" class="m-input" value=0 name="bool_example"><label class="false">下架（为假时显示的标签）</label>' +
            '                            </div>' +
            '                        </div>';
        //下拉选择框变化时 当字段类型为 文件 和 图像时，
        $("#newContentTypeFieldSelect").change(function(){
            var selectOptionName = $(this).find("option:selected").data("name");
            $(".field_setting").remove();
            $("#refContentTypeInput").remove();
            if( selectOptionName === "文件" || selectOptionName === "图像"){ //选择的类别为 图片 文件时，
                $(".m-portlet__body").append(fileAndImgSettingHtml);
            }
            if(selectOptionName === "小数"){
                $(".m-portlet__body").append(fieldDecimalSettingsHtml);
            }
            if(selectOptionName === "布尔值"){
                $(".m-portlet__body").append(fieldBoolSettingsHtml);
            }
        });

        //当字段类型为内容时
        var getContentTypeEntitysUrl = rootURL + "/admin/content/type/entity/getAll_json";
        //当字段类型为分类标签时
        var getTaxonomyTypeEntitysUrl = rootURL + "/admin/taxonomy/type/entity/getAll_json";

        $("#newContentTypeFieldSelect").change(function(){
            var selectOptionName = $(this).find("option:selected").data("name");
            $(".contentTypeInputs").remove();
            if( selectOptionName === "内容"){ //选择的类别为 内容 时，
                $("#refContentTypeInput").remove();
                $(".field_setting").remove();
                addInputs(getContentTypeEntitysUrl);
            }
            if( selectOptionName === "分类标签"){ //选择的类别为 分类标签 时，
                $("#refContentTypeInput").remove();
                $(".field_setting").remove();
                addInputs(getTaxonomyTypeEntitysUrl);
            }
        });


    });

    //当添加字段选择字段类型为 分类标签 / 内容 时，添加字段的设置部分
    //ajax获取所有的分类标签，然后循环生成input checkbox,
    function addInputs(getAllEntitysUrl){
        $.ajax({
            type: "GET",
            url: getAllEntitysUrl,
            success: function(data){
                console.log(data);
                //data 为内容类型 或者 分类标签的 json串，遍历，创建html
                var addCheckboxInputs = '<div class="m--margin-top-10" id="refContentTypeInput">' +
                    '<label>勾选要引用的类型(必选)：</label>';
                for(var i = 0 ; i < data.length ; i++)
                {
                    var currCheckbox = '<input id="input_checkbox_'+ data[i]["id"] +'" ' +
                        'type="checkbox" class="m-padding-left-10" name="field_ref:'+data[i]["id"]+':'+data[i]["typeName"] +'" value="'+ data[i]["typeName"] +'"/> ' +
                        '<label class="m--margin-right-20" for="input_checkbox_'+data[i]["id"]+'">' + data[i]["typeName"] + '</label>';

                    addCheckboxInputs = addCheckboxInputs + currCheckbox;
                }
                addCheckboxInputs = addCheckboxInputs + "</div>";

                $(".m-form__group").append(addCheckboxInputs);
            }
        });
    }

});