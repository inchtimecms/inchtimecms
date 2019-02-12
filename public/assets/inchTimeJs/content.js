//文件input，选择完文件之后的事件处理函数，用于处理文件上传
var rootURL = window.location.protocol + "//" + window.location.host;

function fileInputClick(uploadUrl,element) {
    $(element).parent().children(".m-loader").show();

    var formData = new FormData();
    formData.append("fileUpload", element.files[0]);
    formData.append("file_extensions", $(element).attr("accept"));
    formData.append("file_type", $(element).attr("content"));
    formData.append("bool_Field_Upload_File", true);
    $.ajax({
        type: "POST",
        url: uploadUrl,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (res) {
        if (res === "-1") {
            alert("错误: 请上传允许的文件类型.");
            return;
        }
        //隐藏load icon
        $(element).parent().children(".m-loader").hide();

        var fileUrl = rootURL + res.fileSrc;
        //name格式： fieldTypeValueInSql : fieldTypeEntityAlias : fileManagedId : 最后的为新增的 imgAlt/imgTitle
        var hiddenInputName = $(element).attr("name") + ":" + res.fileEntityId;
        //如果当前input为文件
        if ($(element).attr("content") === "upload-file") {

            var ajaxFileHtml = '<div class="uploaded-file">' +
                '<span><i class="flaticon-book"></i></span>' +
                '<a target="_blank" href=" ' + fileUrl + ' ">' + res.fileName + '</a>' +
                '<input type="hidden" class="m-input" name="' + hiddenInputName + '">' +
                '<button type="button" id="' + res.fileEntityId + '" onclick="deleteUploadFile(this);" class="m--margin-left-20 btn btn-secondary btn-sm deleteUploadFile" >删除</button>' +
                '</div>';
            $(element).parent().append(ajaxFileHtml);

            //隐藏 disabled 当前的file input，防止添加内容时，提交此input的数据
            $(element).hide();
            $(element).attr("disabled", "disabled");
        }
        //如果当前input为图像
        if ($(element).attr("content") === "upload-image") {

            var ajaxImageHtml = '<div class="row uploaded-img">' +
                '<div class="col-4">' +
                '<img src="' + res.fileSrc + '">' +
                '</div>' +
                '<div class="col-8">' +
                '<label>输入图片的机读文本:(必填)</label>' +
                '<input class="form-control m-input" required="required" type="text" name="' + hiddenInputName + ':imgAlt" >' +
                '<button type="button" id="' + res.fileEntityId + '" onclick="deleteUploadImage(this);" class="m--margin-top-10 btn btn-secondary btn-sm deleteUploadImage">删除</button>' +
                '</div>' +
                '</div>';
            $(element).parent().append(ajaxImageHtml);

            //隐藏 disabled 当前的file input，防止添加内容时，提交此input的数据
            $(element).hide();
            $(element).attr("disabled", "disabled");
        }

    }).fail(function (res) {

    });
}

//文件上传,删除按钮点击事件
function deleteUploadFile(element) {

    //把input disabled取消
    $(element).parent(".uploaded-file").parent().children("input.form-control").show();
    $(element).parent(".uploaded-file").parent().children("input.form-control").val("");
    $(element).parent(".uploaded-file").parent().children("input.form-control").attr("disabled", false);
    //删除dom元素
    $(element).parent(".uploaded-file").remove();

    //ajax删除上传的文件的库中的行
    $.ajax({
        type: "GET",
        url: rootURL + "/admin/file/managed/entity/file_delete/" + $(element).attr("id"),
        success: function (data) {

        }
    });
}

// 图片上传，删除按钮点击事件
function deleteUploadImage(element) {
    $(element).parent().parent(".uploaded-img").parent().children("input.form-control").show();
    $(element).parent().parent(".uploaded-img").parent().children("input.form-control").val("");
    $(element).parent().parent(".uploaded-img").parent().children("input.form-control").attr("disabled", false);

    //删除dom元素
    $(element).parent().parent(".uploaded-img").remove();

    //ajax删除上传的文件的库中的行
    $.ajax({
        type: "GET",
        url: rootURL + "/admin/file/managed/entity/file_delete/" + $(element).attr("id"),
        success: function (data) {
        }
    });

}

//添加内容页面，当添加内容的input字段为内容类型时，获取当前的字段引用的内容类型，并ajax搜索对应内容类型的结果
function fetchContentTypeEntityList(fetchContentEntityURL,element) {
    if ($(element).val() !== "") {
        $.ajax({
            type: "POST",
            data: {
                field_ref_contentTypeIds: $(element).attr("content"),
                field_ref_contentKeyWords: $(element).val(),
            },
            url: fetchContentEntityURL,
            success: function (data) {
                //遍历到结果后先把原来的结果删除
                $(element).next(".input-choice-wrapper").show();
                $(element).next(".input-choice-wrapper").children("ul").children("li").remove();
                //遍历data json
                for (var i = 0; i < data.length; i++) {
                    var contentItemHtml = '<li class="content-item" onclick="liClick(this);" id="' + data[i]["content_id"] + '">' + data[i]["content_title"] + '</li>';
                    $(element).next(".input-choice-wrapper").children("ul").append(contentItemHtml);
                }
            }
        });
    }
}

//添加内容页面，当添加内容的input字段为分类标签时，获取当前的字段引用的分类标签类型，并ajax搜索对应标签词汇的结果
function fetchTaxonomyEntityList(fetchTaxonomyEntityURL, element) {

    if ($(element).val() !== "") {
        $.ajax({
            type: "POST",
            data: {
                field_ref_taxonomyTypeIds: $(element).attr("content"),
                field_ref_taxonomyKeyWords: $(element).val(),
            },
            url: fetchTaxonomyEntityURL,
            success: function (data) {
                //遍历到结果后先把原来的结果删除
                $(element).next(".input-choice-wrapper").show();
                $(element).next(".input-choice-wrapper").children("ul").children("li").remove();
                //遍历data json
                for (var i = 0; i < data.length; i++) {
                    var taxonomyItemHtml =
                        '<li class="taxonomy-item" onclick="liClick(this);" id="' + data[i]["taxonomy_id"] + '">' + data[i]["taxonomy_word"] + '</li>';
                    $(element).next(".input-choice-wrapper").children("ul").append(taxonomyItemHtml);
                }
            }
        });
    }
}

//下拉结果列表点击事件
function liClick(element) {
    var refItemListHtml = '<li class="ref-li"><i class="flaticon-close" onclick="removeThisRef(this);"></i>' +
        '<span id="' + $(element).attr("id") + '" class="ref-item">' + $(element).text() + '</span></li>';

    //把refItemListHtml添加到refContentList
    $(element).parent("ul").parent(".input-choice-wrapper")
        .parent(".m--margin-top-10").children(".refContentList").show();
    //已添加到列表中的html代码
    var refContentListText = $(element).parent("ul").parent(".input-choice-wrapper")
        .parent(".m--margin-top-10").children(".refContentList").children("ul").text();

    //每次点完li，把input 的val()清空
    $(element).parent("ul").parent(".input-choice-wrapper").prev("input.m-input").val("");
    //然后把id 添加到hidden input 的value里
    var postval = $(element).parent("ul").parent(".input-choice-wrapper")
        .parent(".m--margin-top-10").children("input[type=hidden]").val();

    if (postval !== $(element).attr("id") ) {
        //添加引用的显示及添加id到hidden Input
        $(element).parent("ul").parent(".input-choice-wrapper")
            .parent(".m--margin-top-10").children(".refContentList").children("ul").append(refItemListHtml);

        if (postval === "") {
            postval = postval + $(element).attr("id");
        } else {
            postval = postval + "," + $(element).attr("id");
        }
        $(element).parent("ul").parent(".input-choice-wrapper")
            .parent(".m--margin-top-10").children("input[type=hidden]").val(postval);

    }

    //把下拉的结果列表隐藏了
    $(element).parent("ul").parent(".input-choice-wrapper").hide();
}

//删除当前的引用的列表
function removeThisRef(element) {
    //把hidden里的value也要删除的。先获取id
    var id = $(element).next("span").attr("id");

    //把input hidden里的对应value也删除
    var refHiddenInput = $(element).parent("li").parent("ul").parent(".refContentList").next("input[type=hidden]");
    var inputValue = refHiddenInput.val();
    if (inputValue.indexOf(id) !== -1) {
        var newInputValue = inputValue.replace(id + ",", "");
        newInputValue = newInputValue.replace("," + id, "");
        newInputValue = newInputValue.replace(id, "");
        refHiddenInput.val(newInputValue);
    }

    $(element).parent(".ref-li").remove();

}

//分类标签input 添加词汇button 事件
function addTaxonomyBtnClick(element) {
    //获取input中的词汇，
    var taxonomyWords = $(element).parent(".m--margin-top-10").children("input.field_ref_taxonomyType").val();
    $(element).parent(".m--margin-top-10").children("input.field_ref_taxonomyType").val("");
    if (taxonomyWords !== "") {
        //获取第一个分类标签的id
        var taxonomyEntityIds = $(element).parent(".m--margin-top-10").children("input.field_ref_taxonomyType").attr("content");
        var theTaxonomyEntityId = taxonomyEntityIds.split(",");

        var addTaxonomyEntityURL = rootURL + "/admin/taxonomy/entity/ajax_add_taxonomy/" + theTaxonomyEntityId[0];

        $.ajax({
            type: "POST",
            data: {
                postTaxonomyWords: taxonomyWords
            },
            url: addTaxonomyEntityURL,
            success: function (data) {
                //把refItemListHtml添加到refContentList
                $(element).parent(".m--margin-top-10").children(".refContentList").show();
                //把结果添加到列表中。
                var taxonomyWordsIdArray = new Array();
                for (var i = 0; i < data.length; i++) {

                    var refItemListHtml = '<li class="ref-li"><i class="flaticon-close" onclick="removeThisRef(this);"></i>' +
                        '<span id="' + data[i]["taxonomyEntity_Id"] + '" class="ref-item">' + data[i]["taxonomyEntity_word"] + '</span></li>';
                    //已添加到列表中的html代码，如果代码中不包含新增的则添加新增的
                    var refContentListText = $(element).parent(".m--margin-top-10").children(".refContentList").children("ul").text();
                    if (refContentListText.indexOf(data[i]["taxonomyEntity_word"]) === -1) {
                        $(element).parent(".m--margin-top-10").children(".refContentList").children("ul").append(refItemListHtml);
                    }

                    //把分类词汇id写入 hidden input values中
                    taxonomyWordsIdArray.push(data[i]["taxonomyEntity_Id"]);

                }

                var taxonomyWordsIdString = taxonomyWordsIdArray.join(",");
                $(element).parent(".m--margin-top-10").find("input[type=hidden].field_ref_taxonomyType").val(taxonomyWordsIdString);
            }
        });
    }

}

//create record for attachment
function sendFile(uploadUrl,file, el) {
    data = new FormData();
    data.append("fileUpload", file);
    data.append("bool_Content_Body_Img", true);
    data.append("file_extensions", "image/jpeg,image/gif,image/png");
    $.ajax({
        type: "POST",
        url: uploadUrl,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            // 这里可能要根据你的服务端返回的上传结果做一些修改哦
            $(el).summernote('editor.insertImage', response.fileSrc, response.fileName);
        },
        error: function (error) {
            alert('图片上传失败');
        },
        complete: function (response) {
        }
    });
}

//销售属性第一组input框点击时
function propValueClick(element) {
    $(element).next(".list-group").show();
}

//销售属性第一组下拉列表的值点击时
function propValueLiClick(element) {

    var propValue = $(element).text();
    var divlistgroup = $(element).parent(".list-group");

    var itemDiv = divlistgroup.parent(".col-4").parent(".row").parent(".sale-prop-group-1-item");

    var valInputs = itemDiv.parent(".sale-prop-group-1").find("input.sale-prop-group-1-item-value");

    for (var i = 0; i < valInputs.length; i++) {
        var currInpuVal = $(valInputs[i]).val();
        if (currInpuVal.indexOf(propValue) !== -1) {
            divlistgroup.hide();
            alert("您已经选择过这条属性了,请换其他属性。");
            return;
        }
    }
    //把选中的属性加入input 并给选择属性input添加name属性
    divlistgroup.prev(".sale-prop-group-1-item-value").val(propValue);
    var currRowNum = $(".sale-prop-group-1 .list-group").index($(element).parent(".list-group"));
    divlistgroup.prev(".sale-prop-group-1-item-value").attr("name", "group1[prop][" + currRowNum + "][name]");
    //给当前行的checkbox 添加value 属性方便 删除table中对应的那行row
    itemDiv.find(".sale-prop-group-1-item-checkbox").val(propValue);
    //给当前行的 备注input 添加 name属性
    itemDiv.find(".sale-prop-group-1-item-commit").attr("name", "group1[prop][" + currRowNum + "][commit]");
    //给当前行的 hidden 图片 input 添加name
    itemDiv.find(".upload-large-img-result-input").attr("name", "group1[prop][" + currRowNum + "][largeimage]");
    itemDiv.find(".upload-small-img-result-input").attr("name", "group1[prop][" + currRowNum + "][smallimage]");
    itemDiv.find(".upload-img-entity-id").attr("name", "group1[prop][" + currRowNum + "][imgfileId]");

    divlistgroup.hide();

    var newValueInputs = itemDiv.parent(".sale-prop-group-1").find("input.sale-prop-group-1-item-value");

    for (var j = 0; j < newValueInputs.length; j++) {
        var inputVal = $(newValueInputs[j]).val();
        if (inputVal === "") {
            return;
        }
    }


    $(".sale-prop-group-1").append(itemDiv.prop("outerHTML"));
    var lastItemDiv = $(".sale-prop-group-1 .sale-prop-group-1-item").last();

    //给当前行的checkbox 添加value 属性方便 删除table中对应的那行row
    lastItemDiv.find(".sale-prop-group-1-item-checkbox").val();
    //给当前行的 备注input 添加 name属性
    lastItemDiv.find(".sale-prop-group-1-item-value").removeAttr("name");
    lastItemDiv.find(".sale-prop-group-1-item-commit").removeAttr("name");
    //给当前行的 hidden 图片 input 添加name
    lastItemDiv.find(".upload-large-img-result-input").removeAttr("name");
    lastItemDiv.find(".upload-small-img-result-input").removeAttr("name");
    lastItemDiv.find(".upload-img-entity-id").removeAttr("name");


    itemDiv.find("label.m-checkbox").show();
    itemDiv.find("input.sale-prop-group-1-item-checkbox").attr("checked", "checked");

    //删除onclick事件,选中后不可更改，只能取消再次添加
    valInputs.removeAttr("onclick");

    //查找当前table中有几行了
    //如果没有找到第二组销售属性,则直接添加row 到 table
    if ($(".sale-prop-group-2").length === 0) {
        var currSalePropRowNum = $(".sale-props-table table tbody tr.sale-prop-row").length;
        var tableRow =
            '<tr class="sale-prop-row">' +
            '    <td><input class="form-control m-input" name="sale-prop['+ currSalePropRowNum +'][group1][name]" type="text" value="' + propValue + '" readonly="readonly" >' +
            '    </td>' +
            '    <td><input class="form-control m-input sale-prop-row-number" name="sale-prop['+ currSalePropRowNum +'][' + propValue + '][number]" type="number" min="0" ></td>' +
            '    <td><input class="form-control m-input sale-prop-row-price" name="sale-prop['+ currSalePropRowNum +'][' + propValue + '][price]" onkeyup="value=value.replace(/[^\\d{1,}\\.\\d{1,}|\\d{1,}]/g,\'\')" onblur="toDecimal2(this, 2)" type="text"> </td>' +
            '    <td><input class="form-control m-input sale-prop-row-code" name="sale-prop['+ currSalePropRowNum +'][' + propValue + '][code]" type="text" onkeyup="value=value.replace(/[\\W]/g,\'\') " ></td>' +
            '</tr>';

        $(".sale-props-table table tbody").append(tableRow);
    }

    //如果第二组属性中选中的项大于1
    var group2checked = $(".sale-prop-group-2").find("input.sale-prop-group-2-checkbox:checked");
    if (group2checked.length > 0) {
        //获取第二组所有的属性值
        for (var i = 0; i < group2checked.length; i++) {
            var group2ItemValue = $(group2checked[i]).val();
            var currSalePropRowNum = $(".sale-props-table table tbody tr.sale-prop-row").length;
            var tableRowHtml =
                '<tr class="sale-prop-row">' +
                '    <td><input class="form-control m-input" name="sale-prop['+ currSalePropRowNum +'][group1][name]" type="text" value="' + propValue + '" readonly="readonly" ></td>' +
                '    <td><input class="form-control m-input" name="sale-prop['+ currSalePropRowNum +'][group2][name]" type="text" value="' + group2ItemValue + '" readonly="readonly" ></td>' +
                '    <td><input class="form-control m-input sale-prop-row-number" name="sale-prop['+ currSalePropRowNum +'][' + propValue + ':' + group2ItemValue + '][number]" type="number" min="0" ></td>' +
                '    <td><input class="form-control m-input sale-prop-row-price" name="sale-prop['+ currSalePropRowNum +'][' + propValue + ':' + group2ItemValue + '][price]" onkeyup="value=value.replace(/[^\\d{1,}\\.\\d{1,}|\\d{1,}]/g,\'\')" onblur="toDecimal2(this, 2)" type="text"></td>' +
                '    <td><input class="form-control m-input sale-prop-row-code" name="sale-prop['+ currSalePropRowNum +'][' + propValue + ':' + group2ItemValue + '][code]" type="text" onkeyup="value=value.replace(/[\\W]/g,\'\')" ></td>' +
                '</tr>';
            $(".sale-props-table table tbody").append(tableRowHtml);
        }
    }

}

//销售属性第一组的checkbox点击事件
function propCheckBoxClick(element) {
    var items = $(".sale-prop-group-1").find("div.sale-prop-group-1-item");
    if (element.checked === false && items.length > 1) {
        var groupItemDiv = $(element).parent(".m-checkbox").parent(".col-1").parent(".row").parent(".sale-prop-group-1-item");
        groupItemDiv.remove();
        //查找 table中对应的那行 一块删除了
        var currRowValue = $(element).val();
        var thatInput = $(".sale-props-table table tbody").find("input[value=" + currRowValue + "]");
        var thatRowElement = thatInput.parent("td").parent("tr");
        thatRowElement.remove();
    }

}

//销售属性 上传图片按按钮 点击处理函数
function btnPropsImgClick(element) {
    if ($(element).parent(".col-3").parent(".row").find("input.sale-prop-group-1-item-value").val() === "") {
        alert("请先选择销售属性");
        return;
    }
    var fileInput = $(element).prev("input[type=file]");
    fileInput.click();
}

//销售属性 封面图片上传 input,change事件函数
function uploadPropsImg(uploadUrl, element) {
    console.log(element);
    var formData = new FormData();
    formData.append("fileUpload", element.files[0]);
    formData.append("file_extensions", $(element).attr("accept"));
    formData.append("bool_Sale_Prop_Img", true);

    $.ajax({
        type: "POST",
        url: uploadUrl,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (res) {
        if (res === "-1") {
            alert("错误: 请上传允许的图片类型.");
        } else {
            //隐藏上传图片按钮
            $(element).next(".upload-img-btn").hide();
            //把结果写入img
            var resultDiv = $(element).parent(".col-3").children("div.prop-img-result");
            resultDiv.children("img").attr("src", res.fileSrc);
            resultDiv.children("img").attr("title", res.fileName);
            resultDiv.children("img").attr("alt", res.fileName);
            resultDiv.children("button").attr("data-uploadimgid", res.fileEntityId);
            //获取当前是第几个图片input
            var currUploadImgInputIndex = $(".sale-prop-group-1 .upload-file-hidden-input").index(element) + 1;
            //把 imgSrc 填入 upload-img-result-input
            $(element).parent(".col-3").children(".upload-small-img-result-input").attr("value", res.fileSrc);
            $(element).parent(".col-3").children(".upload-large-img-result-input").attr("value", res.fileOriginSrc);
            $(element).parent(".col-3").children(".upload-img-entity-id").attr("value",res.fileEntityId);
            //显示img
            resultDiv.show();

        }

    });
}

//销售属性 删除封面图片
function deletePropsImg(element) {
    var uploadImgFileEntityId = $(element).data("uploadimgid");
    //1.AJAX删除 图片.
    $.ajax({
        type: "GET",
        url: rootURL + "/admin/file/managed/entity/file_delete/" + uploadImgFileEntityId,
    }).done(function (res) {
//                console.log(res);
        if (res === "-1") {
            alert("错误: 请稍侯再试.");
            return;
        }
        if (res === "0") {
            $(element).parent(".prop-img-result").hide();
            $(element).parent(".prop-img-result").prev(".upload-img-btn").show();

            //把图片input的name 和 value清空，把图片src清空
            $(element).parent(".prop-img-result").parent(".col-3").children(".upload-small-img-result-input").val("");

            $(element).parent(".prop-img-result").parent(".col-3").children(".upload-large-img-result-input").val("");

        }
    });
}

//第二组销售属性 checkbox 点击事件
function group2checkboxClick(element) {
    //如果第一组属性没有任何属性选中 则提醒
    if ($(".sale-prop-group-1 input.sale-prop-group-1-item-checkbox:checked").length === 0) {
        alert("您还没有选择第一组销售属性，请选选择第一组属性。");
        element.checked = false;
        return;
    }
    //如果当前checkbox为没选中状态
    if (element.checked === false) {
        //删除table中的所有有第二组属性的行
        var currRowValue = $(element).val();
        var thatInput = $(".sale-props-table table tbody").find("input[value=" + currRowValue + "]");
        var thatRowElement = thatInput.parent("td").parent("tr");
        thatRowElement.remove();

    }
    //如果当前行为选中状态，添加行到table
    if (element.checked === true) {
        //获取第一组所有的属性值
        var group1ValueInputs = $(".sale-prop-group-1").find("input.sale-prop-group-1-item-checkbox:checked");
        for (var i = 0; i < group1ValueInputs.length; i++) {
            var group1ItemValue = $(group1ValueInputs[i]).val();
            var currCheckboxValue = $(element).val();
            var currSalePropRowNum = $(".sale-props-table table tbody tr.sale-prop-row").length;
            var tableRowHtml =
                '<tr class="sale-prop-row">' +
                '    <td><input class="form-control m-input" name="sale-prop['+ currSalePropRowNum +'][group1][name]" type="text" value="' + group1ItemValue + '" readonly="readonly" ></td>' +
                '    <td><input class="form-control m-input" name="sale-prop['+ currSalePropRowNum +'][group2][name]" type="text" value="' + currCheckboxValue + '" readonly="readonly" ></td>' +
                '    <td><input class="form-control m-input sale-prop-row-number" name="sale-prop['+ currSalePropRowNum +'][' + group1ItemValue + ':' + currCheckboxValue + '][number]" type="number" min="0" ></td>' +
                '    <td><input class="form-control m-input sale-prop-row-price" name="sale-prop['+ currSalePropRowNum +'][' + group1ItemValue + ':' + currCheckboxValue + '][price]" onkeyup="value=value.replace(/[^\\d{1,}\\.\\d{1,}|\\d{1,}]/g,\'\')" onblur="toDecimal2(this, 2)" type="text"></td>' +
                '    <td><input class="form-control m-input sale-prop-row-code" name="sale-prop['+ currSalePropRowNum +'][' + group1ItemValue + ':' + currCheckboxValue + '][code]" type="text" onkeyup="value=value.replace(/[\\W]/g,\'\')" ></td>' +
                '</tr>';
            $(".sale-props-table table tbody").append(tableRowHtml);
        }
    }

}

//批量修改销售属性按钮
function batBtnClick(srcElement, dstElement) {
    var srcValue = $(srcElement).val();
    $(dstElement).each(function () {
        $(this).val(srcValue);
    });
}

//制保留num位小数，如：2，会在2后面补上00.即2.00
function toDecimal2(element, num) {
    x = $(element).val();
    var src = parseFloat(x);
    if (isNaN(src)) {
        return false;
    }
    var pow = Math.pow(10, num);
    var f = Math.round(x * pow) / pow;
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;
        s += '.';
    }
    while (s.length <= rs + num) {
        s += '0';
    }
    //return s;
    $(element).val(s);
}
