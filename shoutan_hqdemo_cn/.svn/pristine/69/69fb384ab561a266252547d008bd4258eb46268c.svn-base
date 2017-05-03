/**
 * Created with JetBrains PhpStorm.
 * User: BoBo
 * Date: 16.11.13
 * Time: 23:38
 */

$(document).ready(function () {
	var min_date = $('.date-min input').val();
	var max_date = $('.date-max input').val();
	var minDate = null;
	var maxDate = null;
	setDateMinAndMax = function(min,max){
		min_date = $('.date-min input').val();
		max_date = $('.date-max input').val();
		minDate = min||min===null ? min : min_date;
		maxDate = max||max===null ? max : max_date;
		$.datepicker.setDefaults({minDate:minDate, maxDate:maxDate});
	}
	setDateMinAndMax();
	$('body').on('click focus','.date-min input,.date-min .icon-calendar',function(){
		setDateMinAndMax(null);
	})
	$('body').on('click focus','.date-max input,.date-max .icon-calendar',function(){
		setDateMinAndMax(0,null);
	})
	$('body').on('click focus','.date.form_date input,.date.form_date .icon-calendar',function(){
		setDateMinAndMax();
	})
	//quick-preview 快速预览效果
	$('body').on('hover', '.quick-preview,.quick-preview span', function(){
		$("#pre-window").remove();
		var _this = $(this).closest('.quick-preview');
		var data = _this.attr('data-preview');
        if($.trim(data)){
            var div = '<pre id="pre-window">'+data+'</pre>';
            _this.append(div);
			if($('#pre-window').width()>_this.width()){
				$('#pre-window').css('right',0);
			}
        }
	})
	$('body').on('mouseout', '.quick-preview', function(){
		$("#pre-window").remove();
	})
	//modal 点击关闭的时候全部隐藏("data-dismiss"="modal")导致滚动条BUG，后台已关闭20141205
	/* $('body').on('click', '[data-dismiss=modal]', function(){
		$('.modal,.modal-backdrop,.modal-scrollable,.select2-drop-mask').hide();
	}) */
	//针对#ajax-modal的事件加载
    $('body').on('hide', '#ajax-modal', function () {
		$('#select2-drop,.select2-drop-active').hide();
	})
    $('#ajax-modal').on('show', function () {
		//针对modal层重新绑定事件
		if (jQuery().select2) {
			$('#ajax-modal .select2').select2({
				formatNoMatches: function () {
					return "对不起，没有找到匹配项";
				}
			});
		}
		if (jQuery().timepicker) {
			$("#ajax-modal .form_datetime input").datetimepicker({
				timeFormat: "hh:mm:ss",
				changeMonth: true,
				changeYear: true,
				showWeek: false,
				showSecond: true
			});
			
			$("#ajax-modal .form_date input").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				showWeek: false
			});
			$("#ajax-modal .form_time input").timepicker({
				timeFormat: "hh:mm:ss",
				showSecond: true
			});
			$('#ajax-modal .form_datetime .icon-calendar').click(function () {
				$(this).parents('.form_datetime').find('input').timepicker('show');
			});

			$('#ajax-modal .form_datetime .icon-remove').click(function () {
				$(this).parents('.form_datetime').find('input').val('');
			});
			
			$('#ajax-modal .form_time .icon-calendar').click(function () {
				$(this).parents('.form_time').find('input').timepicker('show');
			});

			$('#ajax-modal .form_time .icon-remove').click(function () {
				$(this).parents('.form_time').find('input').val('');
			});
			
			$('#ajax-modal .form_date .icon-calendar').click(function () {
				$(this).parents('.form_date').find('input').timepicker('show');
			});

			$('#ajax-modal .form_date .icon-remove').click(function () {
				$(this).parents('.form_date').find('input').val('');
			});
		}
		if (jQuery().toggleButtons) {
			$('#ajax-modal .success-toggle-button').toggleButtons({
				style: {
					enabled: "success",
					disabled: "info"
				},
				label: {
					enabled: "开",
					disabled: "关"
				}
			});
		}
	});

    //跳转页码 focus的时候选中(实测使用click)
    $('#page_jump_form .jump-page').on('click',function(){
        $(this).select();
    });
	//获取字符实际长度 中文编码算2个字符
    function getCharLength(str) {
        return str.replace(/[\u4e00-\u9fa5]/g,"cn").length;
    };
    //列表即时编辑功能
    function initInstantEdit(){
        $('.instant-edit .tmp-text').each(function(){
            var value = $(this).val();
            var html = '<span style="color:red">'+value+'</span>';
            $(this).closest('.instant-edit').html(html);
        })
    }
    $('body').on('dblclick','.editable',function(){
        initInstantEdit();
        if ($(this).hasClass("instant-edit")){
            var _this = $(this);
        }else{
            var _this = $(this).find('.instant-edit');
        }
        var text = _this.text();
        var len = getCharLength(text);
        len = len>4 ? len : 4;
        var html = '<input type="text" value="'+text+'" style="width:'+len*8+'px;" class="tmp-text" />';
        _this.html(html);
        _this.find('.tmp-text').select();
    })
    $('body').on('blur','.instant-edit .tmp-text',function(){
        var _this = $(this).closest('.instant-edit');
        var id = _this.attr('data-id');
        var field = _this.attr('data-field');
        var value = $(this).val();
        var url = '';
        var search = window.location.search;
        if (search) {
            url = search + '&a=quickedit';
        } else {
            url = '?a=quickedit';
        }
        $.ajax({
            type:'post',
            url:url,
            data:{id:id,field:field,value:value},
            dataType:'json',
            beforeSend:function(){
                $('body').modalmanager('loading');
            },
            success:function(json){},
            complete:function(){
                initInstantEdit();
                $('body').modalmanager('loading');
            }
        })
    })

    //列表排序
    $('.portlet-body .sorting').live('click', function(){
        var sorting = $(this).attr('data-sorting');
        if($(this).hasClass('sorting-asc')){
            var order = 'desc';
            $(this).removeClass('sorting-asc').addClass('sorting-desc');
        }else{
            var order = 'asc';
            $(this).removeClass('sorting-desc').addClass('sorting-asc');
        }
        var search = window.location.search;
        if (search) {
            url = search + '&';
        } else {
            url = '?';
        }
        if(url.indexOf('sorting') >=0){
            url = url.substr(0, url.indexOf('sorting'));
        }
        url += 'sorting='+sorting+'&order='+order;
        window.location.href = url;
    })
    //操作按钮
    $('.list-op-btn').click(function (){
        var check_list = $('.check_list:checked');
        if (check_list.length == 0) {
            custom_alert('您未选中任何记录！');
        } else {
            var op = $(this).attr('list-op-type');
            var op_title = $(this).text();
            //var url = '?a=op';
            var url = '';
            var search = window.location.search;
            if (search) {
                url = search + '&a=op';
            } else {
                url = '?a=op';
            }
            var ids = [];
            check_list.each(function (){
                ids.push($(this).val());
            });

            var data = {
                op: op,
                ids: ids
            };
            custom_confirm('您确定要' + op_title + '您选择的记录吗？', function (){
                $.ajax({
                    url: url,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    success: function (rs){
                        custom_alert(rs.info, rs.url);
                    },
                    error: function (){
                        custom_alert('操作失败，请稍后再试！');
                    }
                });
            });
        }
    });
    //控制分页显示条数
    $('#limit_num').live('change',function(){
        $('#limit_form').submit();
    });
    //初始化高级搜索 是否默认展开
    var check_filter = function(){
        var icon = $(".adv-search-box .portlet-title .tools a");
        var box = $(".adv-search-box .portlet-body");
        if (icon.hasClass("collapse")) {
            box.slideDown(200);
            $("#search_form").fadeOut();
        } else {
            box.slideUp(200);
            $("#search_form").fadeIn();
        }
    }
    check_filter();
    //高级搜索标题点击触发
    $(".adv-search-box .portlet-title").die().live('click', function (e) {
        e.preventDefault();
        var icon = $(".adv-search-box .portlet-title .tools a");
        var box = $(".adv-search-box .portlet-body");
        if (icon.hasClass("collapse")) {
            icon.removeClass("collapse").addClass("expand");
            box.slideUp(200);
            $("#search_form").fadeIn();
        } else {
            icon.removeClass("expand").addClass("collapse");
            box.slideDown(200);
            $("#search_form").fadeOut();
        }
    });

    //筛选状态下 默认展开
    var run_filter = function(){
        var run = window.location.search.indexOf('filter');
        if(run>0){
            var icon = $(".adv-search-box .portlet-title .tools a");
            var box = $(".adv-search-box .portlet-body");
            icon.removeClass("expand").addClass("collapse");
            box.slideDown(200);
            $("#search_form").fadeOut();
        }
    }
    run_filter();

    //高级搜索复选框

    var search_hide = function () {
        if ($(".form-common .adv-search-box .portlet-body").is(":visible")) {
            $("#search_form").hide();
        } else {
            $("#search_form").show();
        }
        $(".form-common .adv-search-box .portlet-title .tools a").live('click', function (e) {
            if ($(".form-common .adv-search-box .portlet-body").is(":visible")) {
                $("#search_form").fadeIn();
            } else {
                $("#search_form").fadeOut();
            }
        });
    };
    search_hide();
    var check_fn = function () {
        var check_item = $(".checkbox_item");
        var check_all = $(".checkbox_all");
        check_all.click(function () {
            var checked = $(this).is(":checked");
            check_item.each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            $.uniform.update(check_item);
            //changePermition(check_item.filter(':checked'), $('.search_state'));
        });
        check_item.click(function () {
            if (check_item.length == check_item.filter(':checked').length) {
                check_all.attr("checked", true);
            } else {
                check_all.attr("checked", false);
            }
            $.uniform.update(check_all);
            //changePermition(check_item.filter(':checked'), $('.search_state'));
        })
    }
    //浏览器初始化的时候执行
    var check_item = $(".checkbox_item");
    var check_all = $(".checkbox_all");
    if (check_item.length == check_item.filter(':checked').length) {
        check_all.attr("checked", true);
    } else {
        check_all.attr("checked", false);
    }
    check_fn();


    //select2下拉框
    $('.select2').select2({
        formatNoMatches: function () {
            return "对不起，没有找到匹配项";
        }
    });

    // toggle_select
    $('.toggle_select').multiSelect();
    $('.toggle_select2').multiSelect({
        selectableOptgroup: true
    });
    $(".ms-container .ms-selectable").prepend('<label style="color: #999" class="control-label">未选择</label>');
    $(".ms-container .ms-selection").prepend('<label style="color: #999" class="control-label">已选择</label>');

   // inputmask
    $.extend($.inputmask.defaults, {
        'autounmask': true
    });
    $(".mask_date").inputmask("m/d", {autoUnmask: true});  //direct mask
    $(".mask_date0").inputmask("d/m/y", {autoUnmask: true});  //direct mask
    $(".mask_date1").inputmask("d/m/y",{ "placeholder": "*"}); //change the placeholder
    $(".mask_date2").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" }); //multi-char placeholder
    $(".mask_phone").inputmask("mask", {"mask": "(999) 999-9999"}); //specifying fn & options
    $(".mask_tin").inputmask({"mask": "99-9999999"}); //specifying options only
    $(".mask_number").inputmask({ "mask": "9", "repeat": 10, "greedy": false });  // ~ mask "9" or mask "99" or ... mask "9999999999"
    $(".mask_decimal").inputmask('decimal', { rightAlignNumerics: false }); //disables the right alignment of the decimal input
    $(".mask_currency").inputmask('€ 999.999.999,99', { numericInput: true });  //123456  =>  € ___.__1.234,56

    $(".mask_currency2").inputmask('€ 999,999,999.99', { numericInput: true, rightAlignNumerics: false, greedy: false}); //123456  =>  € ___.__1.234,56
    $(".mask_ssn").inputmask("999-99-9999", {placeholder:" ", clearMaskOnLostFocus: true }); //default

    /*window resize*/
    $(window).resize(function () {
      var _width = $(window).width();
      if (_width < 1280 && _width >= 980) {
        $("body").addClass("page-sidebar-closed");
      } else if (_width >= 1280) {
        $("body").removeClass("page-sidebar-closed");
      }
    })

    //********************菜单***********************
    //cookie sidebar
    if ($.cookie('page-sidebar-closed') == 1) {
        $('body').addClass('page-sidebar-closed');
    } else {
        $('body').removeClass('page-sidebar-closed');
    }

    if ($('#menu_type').val()) {
        $('.menu_type' + $('#menu_type').val()).show();
    }

    $('#menu_type').change(function () {
        $('.menu_type').hide();
        $('.menu_type' + $(this).val()).show();
    });

    //菜单js选中
    var selectMenu = function (obj) {
        if (obj.hasClass('level0')) {
            obj.children('a').append('<span class="selected"></span>');
        } else {
            obj = obj.parents('li');
            if (obj.length) {
                if (obj.hasClass('level0')) {
                    obj.addClass('active').children('a').children('.arrow').addClass('open').after('<span class="selected"></span>');
                } else {
                    obj.addClass('open');
                    selectMenu(obj);
                }
            }
        }
    };

    selectMenu($('.page-sidebar-menu li.active'));

    //*****************针对lightbox form加载样式****************
    $('.modal').on('show', function () {
        //select2下拉框
        //        $('.select2').select2();
        //RTE
        //        $(this).find('.ckeditor').each(function(){
        //            if($(this).attr('id')){
        //                CKEDITOR.replace($(this).attr('id'));
        //            }else{
        //                var tempID = 'id-'+new Date().getTime()+Math.random();
        //                $(this).attr('id', tempID);
        //                CKEDITOR.replace(tempID);
        //            }
        //        });
	});

    //加载编辑器
    if ($('#container').length) {
        if ($('#container').attr('show_type') == 'little'){
            var editor_options = {
                wordCount: false,
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                elementPathEnabled: false,
                scaleEnabled: true,
                toolbars: [
				['source','undo', 'redo','bold', 'italic', 'underline', 'superscript', 'subscript','removeformat', 'formatmatch', 'pasteplain', '|',
				 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','link', 'unlink', '|',
				 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
				 'insertimage','insertvideo', 'music', 'attachment', 'map']
                ]
            };
        } else {
            var editor_options = {
                scaleEnabled: true,
                savePath: ['News', 'Goods', 'Others'],
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                toolbars: [
                ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                'print', 'preview', 'searchreplace', 'help', 'drafts']
                ]
            };
        }
        
        var editor = UE.getEditor('container', editor_options);
    }
    
    //加载编辑器(2)
    if ($('#container2').length) {
        if ($('#container2').attr('show_type') == 'little'){
            var editor_options = {
                wordCount: false,
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                elementPathEnabled: false,
                scaleEnabled: true,
                toolbars: [
                ['source','undo', 'redo','bold', 'italic', 'underline', 'superscript', 'subscript','removeformat', 'formatmatch', 'pasteplain', '|',
				 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','link', 'unlink', '|',
				 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
				 'insertimage','insertvideo', 'music', 'attachment', 'map']
                ]
            };
        } else {
            var editor_options = {
                scaleEnabled: true,
                savePath: ['News', 'Goods', 'Others'],
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                toolbars: [
                ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                'print', 'preview', 'searchreplace', 'help', 'drafts']
                ]
            };
        }
        
        var editor = UE.getEditor('container2', editor_options);
    }
    //加载编辑器(3)
    if ($('#container3').length) {
        if ($('#container3').attr('show_type') == 'little'){
            var editor_options = {
                wordCount: false,
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                elementPathEnabled: false,
                scaleEnabled: true,
                toolbars: [
                ['source','undo', 'redo','bold', 'italic', 'underline', 'superscript', 'subscript','removeformat', 'formatmatch', 'pasteplain', '|',
				 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','link', 'unlink', '|',
				 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
				 'insertimage','insertvideo', 'music', 'attachment', 'map']
                ]
            };
        } else {
            var editor_options = {
                scaleEnabled: true,
                savePath: ['News', 'Goods', 'Others'],
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                toolbars: [
                ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                'print', 'preview', 'searchreplace', 'help', 'drafts']
                ]
            };
        }
        
        var editor = UE.getEditor('container3', editor_options);
    }

    //加载编辑器(4)
    if ($('#container4').length) {
        if ($('#container4').attr('show_type') == 'little'){
            var editor_options = {
                wordCount: false,
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                elementPathEnabled: false,
                scaleEnabled: true,
                toolbars: [
                ['source','undo', 'redo','bold', 'italic', 'underline', 'superscript', 'subscript','removeformat', 'formatmatch', 'pasteplain', '|',
                 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','link', 'unlink', '|',
                 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                 'insertimage','insertvideo', 'music', 'attachment', 'map']
                ]
            };
        } else {
            var editor_options = {
                scaleEnabled: true,
                savePath: ['News', 'Goods', 'Others'],
                initialStyle: 'p{line-height:1em; font-size: 12px;}',
                toolbars: [
                ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                'print', 'preview', 'searchreplace', 'help', 'drafts']
                ]
            };
        }
        
        var editor = UE.getEditor('container4', editor_options);
    }
    


    //删除js提醒
    $('.del').die().live('click', function (event) {
        event.preventDefault();
        
        if ($(this).attr('data-lock') == 1){
            custom_alert('此数据已被锁定，不能删除！');
            return false;
        }

        var del_url = $(this).attr('href');
        custom_confirm('是否确定删除?', function () {
            $.ajax({
                url: del_url,
                dataType: 'json',
                success: function (rs){
                    $('body').modalmanager('loading');
                    if (rs.url != '') {
                        window.location.href = rs.url;
                    } else {
                        custom_alert(rs.info);
                    }
                },
                beforeSend: function (){
                    $('body').modalmanager('loading');
                }
            });
        });
    });


    //toggle button
    if (jQuery().toggleButtons) {
        $('.success-toggle-button').toggleButtons({
            style: {
                enabled: "success",
                disabled: "info"
            },
            label: {
                enabled: "开",
                disabled: "关"
            }
        });
    }


    //datetime picker
    /* if (jQuery().datepicker) {
        $(".form_datetime input").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            showWeek: false,
            closeText: "关闭",
            prevText: "上一个",
            nextText: "下一个",
            currentText: "今天",
            monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            dayNames: ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
            dayNamesShort: ["日", "一", "二", "三", "四", "五", "六"],
            dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
            weekHeader: "Wo",
            firstDay: 1
        });
    } */
	
	if (jQuery().timepicker) {
        $(".form_datetime input").datetimepicker({
            timeFormat: "hh:mm:ss",
            changeMonth: true,
            changeYear: true,
            showWeek: false,
            showSecond: true,
            closeText: "关闭",
            prevText: "上一个",
            nextText: "下一个",
            currentText: "现在时间",
            monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            dayNames: ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
            dayNamesShort: ["日", "一", "二", "三", "四", "五", "六"],
            dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
        });
        
        $(".form_date input").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            showWeek: false,
            closeText: "关闭",
            prevText: "上一个",
            nextText: "下一个",
            currentText: "今天",
            monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            dayNames: ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
            dayNamesShort: ["日", "一", "二", "三", "四", "五", "六"],
            dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
        });
        $(".form_time input").timepicker({
            timeFormat: "hh:mm:ss",
            showSecond: true
        });
    }

    $("button[type='submit']").die().live('click', function (event) {
        event.preventDefault();

        if ($(this).attr('id') == 'filter'){
            $('#filter_form').submit();
        } else if($(this).attr('id') == 'search') {
            /*if ($('input[name="keyword"]').val() == ''){
             custom_alert('请输入搜索关键字！');
             } else {
             $('form').submit();
             }*/
            $('#search_form').submit();
        }else if($(this).attr('id') == 'jump-btn'){
            $('#page_jump_form').submit();
        }else {
            if ($('.files_upload').length && $('.upfiles_add').length){
                $('.start').trigger('click');
            } else {
                form_update();
            }
        }
    });
    
    //时间控件
    /* $('.form_datetime .icon-calendar').click(function () {
        $(this).parents('.form_datetime').find('input').datepicker('show');
    });

    $('.form_datetime .icon-remove').click(function () {
        $(this).parents('.form_datetime').find('input').val('');
    }); */
	
	$('.form_datetime .icon-calendar').click(function () {
        $(this).parents('.form_datetime').find('input').timepicker('show');
    });

    $('.form_datetime .icon-remove').click(function () {
        $(this).parents('.form_datetime').find('input').val('');
    });
    
    $('.form_time .icon-calendar').click(function () {
        $(this).parents('.form_time').find('input').timepicker('show');
    });

    $('.form_time .icon-remove').click(function () {
        $(this).parents('.form_time').find('input').val('');
    });
    
    $('.form_date .icon-calendar').click(function () {
        $(this).parents('.form_date').find('input').timepicker('show');
    });

    $('.form_date .icon-remove').click(function () {
        $(this).parents('.form_date').find('input').val('');
    });

    //全选checkbox
    $('.check_all').click(function (){
        var check_box_obj = $('.check_list:enabled');
        if ($(this).attr('checked') == 'checked'){
            check_box_obj.attr('checked', 'checked');
            check_box_obj.parent('span').addClass('checked');
        } else {
            check_box_obj.attr('checked', false);
            check_box_obj.parent('span').removeClass('checked');
        }
    });

    $('.check_list').click(function (){
        if ($('.check_list:enabled').length == $('.check_list:checked').length){
            $('.check_all').attr('checked', 'checked');
            $('.check_all').parent('span').addClass('checked');
        } else {
            $('.check_all').attr('checked', false);
            $('.check_all').parent('span').removeClass('checked');
        }
    });

    $('.list_op').click(function (){
        var check_list = $('.check_list:checked');
        if (check_list.length == 0) {
            custom_alert('您未选中任何记录！');
        } else {
            var op = $('.list_op_type').val();
            var op_text = $('.list_op_type option:selected').text();
            //var url = '?a=op';
			var url = '';
            var search = window.location.search;
            if (search) {
                url = search + '&a=op';
            } else {
                url = '?a=op';
            }
            var ids = [];
            check_list.each(function (){
                ids.push($(this).val());
            });
            
            var data = {
                op: op, 
                ids: ids
            };
            custom_confirm('您确定要' + op_text + '您选择的记录吗？', function (){
                $.ajax({
                    url: url,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    success: function (rs){
                        custom_alert(rs.info, rs.url);
                    },
                    error: function (){
                        custom_alert('操作失败，请稍后再试！');
                    }
                });
            });
        }
    });

    //删除附件
    $('.delete_attach').click(function (){
        $(this).parents('tr').detach();
        var id = $(this).attr('rel');
        var model_name = $(this).attr('controller');
        var _model = $(this).attr('model');
        var url = 'index.php?m='+ _model +'&c=Index&a=deleteAttach&id='+id+'&model=' + model_name;
        $.get(url, function (rs){
            if (rs == 1){
                $(this).parents('tr').detach();
            }
        });
    });
    
    //图片预览
    $('.image_view').click(function (){
        $('#show_pic img').attr('src', $(this).attr('rel'));
    });
    $(".image_delete").click(function(){
        $(this).siblings('.image_view').hide();
    });
    
    $(".file_delete").click(function(){
        $(this).siblings('.file_view').hide();
    });
    
    //清空缓存
    $('#clear_cache').click(function (){
        var url = $(this).attr('data-url');
        $.get(url, function (rs){
            if (rs == 1){
                custom_alert('缓存已清除！')
            } else {
                custom_alert('缓存清除失败！')
            }
        });
    });
});


function custom_confirm(title, callBackOk) {
    $('#custom_confirm .modal-body').html(title);
    $('#custom_confirm').modal('show');

    $("#custom_confirm #custom_confirm_ok").die().live('click', function (e) {
        e.preventDefault();
        if (typeof(callBackOk) == "function") {
            callBackOk();
        }
        $("#custom_confirm").modal("hide");
    });

    $("#custom_confirm #custom_confirm_cancel").die().live('click', function (e) {
        e.preventDefault();
        $("#custom_confirm").modal("hide");
    });
}

/**
 * 通用alert
 * @param title 标题
 * @param url 关闭alert后跳转url
 * @param modal_id 关闭alert打开之前的对话框
 */
function custom_alert(title) {
    $('#custom_alert #error_content').html(title);
    $('#custom_alert').modal('show');

    if (arguments[1]) {
        var url = arguments[1];
        $('#custom_alert').on('hidden.bs.modal', function () {
            window.location.href = url;
        });
    }

    if (arguments[2]) {
        var modal_id = arguments[2];
        $("#" + modal_id).modal('hide');
    }
}

function form_update(){
    $('form').ajaxSubmit({
        url: '',
        dataType: 'json',
        error: function (){
            $('body').modalmanager('loading');
            custom_alert('提交失败，请稍后再试！', window.location);
        },
        success: function (rs) {
            $('body').modalmanager('loading');
            if (rs.url != '') {
                custom_alert(rs.info, rs.url);
            } else {
                custom_alert(rs.info);
            }
        },
        beforeSend: function (){
            $('body').modalmanager('loading');
        }
    });
}

// 分页跳转
function jumpPage(){
    var _p = parseInt($('#page_jump_form .jump-page').val());
    var p = _p>1 ? _p : 1;
    var search = window.location.search;
    if (search) {
        url = search + '&';
    } else {
        url = '?';
    }
    if(url.indexOf('p=') >=0){
        url = url.substr(0, url.indexOf('p='));
    }
    url += 'p='+p;
    window.location.href = url;
    return false;
}
//isNULL函数
function isNULL(arg){
	return $.trim(arg)==='' || arg===null || arg===undefined;
}
//inArray函数
function inArray(e, a){
	for(var i in a){
		if(a[i]==e){
			return true;
		}
	}
	return false;
}

/********************************************************
 * Description: open new windows
 * Function name: newWindow()
 * @param: string doc URL of the new windows
 * @param: string hite height of windows
 * @param: string wide width of windows
 * @param: string bars 1- scroll bar = YES 0- scroll bar = NO
 * @param: string resize 1- resize = YES 0- resize = NO
 * returnValue: instance of new windows
 *************************************************************/
function newWindow(doc, hite, wide, bars, resize, newwindow) {
	var winNew = newwindow || "_blank";
	var opt = "toolbar=0,location=0,directories=0,status=1,menubar=0,left=0,top=0,";  //no display of toolbar, location, directories, status bar and menu bar.
	opt += ("scrollbars=" + bars + ",");
	opt += ("resizable=" + resize + ",");
	opt += ("width=" + wide + ",");
	opt += ("height=" + hite);
	winHandle = window.open(doc, winNew, opt);  //windows instantiation
	try {
		winHandle.closed;
	} catch (e) {
		alert(openError);
	}
	return;
}