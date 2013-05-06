//Init functions needed on every page
$(document).ready(function () {
    if($('.tree').length > 0) {
        init_tree();
    }
    init_main_functions();
    initTop();
    initCancel();
    initCustomSelect();
    initDel();
    initFilter();
    initDatePicker();
    initTimePicker();
    initDropdownButton();
    initModalFocus();
    initSaveKeyListener();
    initSidenavSize();
});

//JS-tree
function init_tree() {
    var topLevelTreeElements = "";
    $('.tree > ul > li').each(function() {
        topLevelTreeElements = topLevelTreeElements + $(this).attr('id'); + ",";
    });
    $('.tree').jstree({
        "plugins" : [ "themes", "html_data", "types", "search" ] ,
        "themes" : {
            "theme" : "OMNext",
            "dots" : true,
            "icons" : true
        },
        "core": {
                    "animation": 0,
                    "open_parents": true,
                    "initially_open": [topLevelTreeElements]
                },
        "types" : {
            "types" : {
                //Page
                "page" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -57px"
                    }
                },
                "page_offline" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -74px"
                    }
                },
                //Site
                "site" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -38px"
                    }
                },
                //Settings
                "settings" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -37px"
                    }
                },
                //Image
                "image" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-20px -74px"
                    }
                },
                //Video
                "video" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -55px"
                    }
                },
                //Slideshow
                "slideshow" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-2px -72px"
                    }
                },
                //Files
                "files" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-38px -72px"
                    }
                }
            }
        },
        // Configuring the search plugin
        "search" : {
            "show_only_matches" : true
        }
    });
    $("#treeform").submit(function() {
        $('.tree').jstree('search', $('#treeform #searchVal').val());
        return false;
    });
    $('#treeform #searchVal').keyup(function() {
        $('.tree').jstree('search', $('#treeform #searchVal').val());
    });
    $('.pagestree').jstree({
        "plugins" : [ "themes", "html_data", "dnd", "crrm", "types", "search" ] ,
        "themes" : {
            "theme" : "OMNext",
            "dots" : true,
            "icons" : true
        },"core": {
                    "animation": 0,
                    "open_parents": true,
                    "initially_open": [topLevelTreeElements]
                },
        "types" : {
            "types" : {
                //Page
                "page" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -57px"
                    }
                },
                "page_offline" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -74px"
                    }
                },
                //Site
                "site" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -38px"
                    }
                },
                //Settings
                "settings" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -37px"
                    }
                },
                //Image
                "image" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-20px -74px"
                    }
                },
                //Video
                "video" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -55px"
                    }
                },
                //Slideshow
                "slideshow" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-2px -72px"
                    }
                },
                //Files
                "files" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-38px -72px"
                    }
                }
            }
        },
        "crrm" : {
            "move" : {
                "check_move" : function (m) {
                    var p = this._get_parent(m.o);
                    if(!p) return false;
                    p = p == -1 ? this.get_container() : p;
                    if(p === m.np) return true;
                    if(p[0] && m.np[0] && p[0] === m.np[0]) return true;
                    return false;
                }
            }
        },
        "dnd" : {
            "drop_target" : false,
            "drag_target" : false
        },
        // Configuring the search plugin
        "search" : {
            "show_only_matches" : true
        }
    });
    $("#pagestreeform").submit(function() {
        $('.pagestree').jstree('search', $('#pagestreeform #searchVal').val());
        return false;
    });
    $('#pagestreeform #searchVal').keyup(function() {
        $('.pagestree').jstree('search', $('#pagestreeform #searchVal').val());
    });
    $('.mediatree').jstree({
        "plugins" : [ "themes", "html_data", "dnd", "crrm", "types", "search" ] ,
        "themes" : {
            "theme" : "OMNext",
            "dots" : true,
            "icons" : true
        },
        "types" : {
            "types" : {
                //Page
                "page" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -57px"
                    }
                },
                "page_offline" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -74px"
                    }
                },
                //Site
                "site" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -38px"
                    }
                },
                //Settings
                "settings" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-57px -37px"
                    }
                },
                //Image
                "image" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-20px -74px"
                    }
                },
                //Video
                "video" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-75px -55px"
                    }
                },
                //Slideshow
                "slideshow" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-2px -72px"
                    }
                },
                //Files
                "files" : {
                    "icon" : {
                        "image" : $.jstree._themes + "OMNext/d.png",
                        "position" : "-38px -72px"
                    }
                }
            }
        },
        "crrm" : {
            "move" : {
                "check_move" : function (m) {
                    var p = this._get_parent(m.o);
                    if(!p) return false;
                    p = p == -1 ? this.get_container() : p;
                    if(p === m.np) return true;
                    if(p[0] && m.np[0] && p[0] === m.np[0]) return true;
                    return false;
                }
            }
        },
        "dnd" : {
            "drop_target" : false,
            "drag_target" : false
        },
        // Configuring the search plugin
        "search" : {
            "show_only_matches" : true
        }
    });
    $("#mediatreeform").submit(function() {
        $('.mediatree').jstree('search', $('#mediatreeform #searchVal').val());
        return false;
    });
    $('#mediatreeform #searchVal').keyup(function() {
        $('.mediatree').jstree('search', $('#mediatreeform #searchVal').val());
    });
}

//Drag and Drop
function init_DragDrop() {
    $('#parts').sortable({
        handle: '.prop_bar',
        cursor: 'move',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        revert: 100,
        //opacity: 0.4
        opacity: 1,
        start: function(e, ui) {
            $('.draggable').css('opacity', ".4");
            $('.ui-sortable-helper .new_pagepart').slideUp("fast");
        },
        stop: function(e, ui) {
            $('.draggable').css('opacity', "1");
        }
    });
}

//Drop down main_actions
function init_main_functions() {
    $(window).scroll(
        function() {
            var scrollTop = $(this).scrollTop();
            if(scrollTop >= 180){
                $('#main_actions_top').addClass('slideDown');
            }

            if(scrollTop < 180){
                $('#main_actions_top').removeClass('slideDown');
            }
        }
    );
}

//Toplink
function initTop() {
    $(".up").click(function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop:0}, 700);
    });
}

//Thumbnails Helper
function initDel() {
    $('.thumbnails .del').hover(
        function() {
            $(this).parent().find('.thumbnail').addClass('warning');
            $(this).parent().find('.helper').html('Delete').addClass('warning');
        },
        function() {
            $(this).parent().find('.thumbnail').removeClass('warning');
            $(this).parent().find('.helper').html('Click to edit').removeClass('warning');
        }
    );
}

function initCancel() {
    $('.close_modal').on('click', function (e) {
        e.preventDefault();
        $(this).parents('.modal').modal('hide');
    });
}

// Toggle Properties
function init_toggleProp() {
    $("#toggle-properties").click(function(e){
        e.preventDefault();
        if ($(this).hasClass('big')) {
            $("#prop_wrp").slideUp("slow").animate({opacity: 0},{queue: false, duration: "slow"}).addClass('small_prop').removeClass('big_prop');
            $(this).removeClass('big').addClass('small').html('Show Properties');



        }
        else {
            $("#prop_wrp").slideDown("slow").animate({opacity: 1},{queue: false, duration: "slow"}).addClass('small_prop').removeClass('big_prop');
            $(this).removeClass('small').addClass('big').html('Hide Properties');
        }
    });
}

//Twipsy
function init_twipsy() {
    $("a[rel=twipsy]").twipsy({live:true});
}

//Custom Select
function initCustomSelect() {
    $('select.chzn-select').chosen();
}



////FILTERS
function initFilter() {
    var checked = $("#filter_on_off").attr("checked");

    if (checked) {
        $(".all").removeClass("active");
        $(".filters_wrp").addClass("active");
    } else {
        $(".all").addClass("active");
    }

    $("#filter_on_off").iphoneStyle({
        checkedLabel: '',
        uncheckedLabel: '',
        resizeHandle: true,
        resizeContainer: true,
        dragThreshold: 0,
        handleMargin: 5,
        handleRadius: 12,
        containerRadius: 5,
        onChange: function (e, value) {
            if(value){
                $(".all").removeClass("active");
                $(".filters_wrp").addClass("active");
            } else {
                $(".all").addClass("active");
                $(".filters_wrp").removeClass("active");
                resetFilters();
            }

        }
    });
}

function createFilter(el, hide, options){
    var line = $(el).parent("li"),
        uniqueid = calculateUniqueFilterId(),
        newitem = $('<li>').attr('class', 'filterline').append($("#filterdummyline").html());

    if(hide === true){
        line.addClass("hidden");
    }

    if(hide === true){
        line.after(newitem);
    } else {
        line.before(newitem);
    }

    newitem.find(".uniquefilterid").val(uniqueid);
    newitem.find(".filterdummy").val(line.find(".filterselect").val());
    updateOptions(newitem.find(".filterdummy"), options);

    if(hide === true){
        newitem.removeClass("hidden");
        line.find("select").val("");
    } else {
        newitem.slideDown();
    }
    return false;
}

function resetFilters(){
    $(".filterline").remove();
    $(".apply_filter").click();
    return false;
}

function removeThisFilter(el){
    if($("#filtermap").find(".filterline").length === 2){
        $(el).parent(".filterline").remove();
        $("#addline").removeClass("hidden");
    } else {
        $(el).parent(".filterline").slideUp(function(){
            $(this).remove();
        });
    }
    return false;
}

function calculateUniqueFilterId(){
    var result = 1;
    $("input.uniquefilterid").each(function(){
        var value = parseInt(jQuery(this).val(), 10);
        if(result <= value){
            result = value + 1;
        }
    });
    return result;
}

function updateOptions(el, options){
    var val = $(el).val();
    val = val.replace('.',  '_');
    var uniqueid = $(el).parent(".filterline").find(".uniquefilterid").val();
    $(el).parent(".filterline").find(".filteroptions").html($('#filterdummyoptions_'+ val).html());
    $(el).parent(".filterline").find("input, select").each(function(){
        var fieldName = $(this).attr("name");
        if (fieldName.substr(0, 7) != "filter_") {
            $(this).attr("name", "filter_" + $(this).attr("name"));
        }
    });
    $(el).parent(".filterline").find(".filteroptions").find("input:not(.uniquefilterid), select").each(function(){
        $(this).attr("name", $(this).attr("name") + "_" + uniqueid);
        if($(this).hasClass("datepick")){
            $(this).datepicker(options);
        }
    });
}

function initDatePicker() {
    // http://www.eyecon.ro/bootstrap-datepicker/
    if($('.form_datepicker').length > 0) {
        $(".form_datepicker").datepicker({'format': 'dd/mm/yyyy'});
    }
}

function initTimePicker() {
    // http://jdewit.github.com/bootstrap-timepicker/
    if($('.form_timepicker').length > 0) {
        $(".form_timepicker").timepicker({
            'showMeridian': false,
            'minuteStep': 1,
            'defaultTime': 'value'
        });
    }
}

function initDropdownButton() {
    var $el = $('.main_actions .btn.dropdown-toggle');

    if($el.is(':nth-child(2)')) {
        $el.css({
            '-webkit-border-top-right-radius': 0,
            '-webkit-border-bottom-right-radius': 0,
            '-moz-border-radius-topright': 0,
            '-moz-border-radius-bottomright': 0,
            'border-top-right-radius': 0,
            'border-bottom-right-radius': 0
        });
    }

    $el.on('click', function() {
        var offset = $el.offset().left - $('.main_actions').offset().left - $('.main_actions .dropdown-menu').outerWidth() + $(this).outerWidth();
        $el.next('.dropdown-menu').css('left', offset);
    });
}

// UI SORTABLE
$(function() {
    var sortableClicked = false;
    $('.sortable').mousedown(
        function() {
            sortableClicked = true;
            var scope = $(this).data('scope');
            $('.sortable[data-scope~=' + scope + ']')
                .addClass('connectedSortable')
                .sortable('option', 'connectWith', '.connectedSortable');
            $('.sortable:not([data-scope~=' + scope + '])')
                .sortable('disable')
                .sortable('option', 'connectWith', false)
                .parent().addClass('region-disabled');
            $('.template-block-content').not('.sortable')
                .parent().addClass('region-disabled');
        }
    );
    $('body').mouseup(
        function() {
            if (sortableClicked) {
                // Enable all sortable regions again
                $('.sortable')
                    .sortable('enable')
                    .sortable('option', 'connectWith', false)
                    .parent().removeClass('region-disabled');
                $('.template-block-content').not('.sortable')
                    .parent().removeClass('region-disabled');
                sortableClicked = false;
            }
        }
    );
    $( ".sortable" ).sortable({
        connectWith: ".connectedSortable",
        placeholder: "sortable-placeholder",
        tolerance:"pointer",
        revert: 300
    }).disableSelection();
});

function initSidenavSize() {
    $('#adjust_sidebar').on('click', function () {
        $('#main_content, #sidebar').toggleClass('full_view');
        $(this).toggleClass('sidebar_hidden');
    });
}

function initModalFocus() {
    $('.modal').on('shown', function () {
        var inputs = jQuery(this).find('input[type=text]');
        if(inputs.length>0) {
            inputs[0].focus();
        } else {
            var btns = jQuery(this).find('.btn-primary, .btn-danger');
            if(btns.length>0){
                btns[0].focus();
            } else {
                btns = jQuery(this).find('.btn');
                if(btns.length>0){
                    btns[0].focus();
                } else {
                    btns = jQuery(this).find('button');
                    if(btns.length>0){
                        btns[0].focus();
                    }
                }
            }
        }
    });
}

function performSaveAction() {
    var btns = jQuery('.btn-save');
    if(btns.length>0) {
        btns[0].click();
    }
}

function initSaveKeyListener() {
    var code;
    if(document.all){
        document.onkeyup = function() {
            if (window.event.ctrlKey) {
                if (window.event.keyCode == 83) {
                    performSaveAction();
                    return false;
                }
            }
        };
    } else {
        document.onkeydown = document.onkeypress = function (evt) {
            // check voor ctrl-s key
            if (evt.ctrlKey) {
                if (evt.keyCode)
                    code = evt.keyCode;
                else if (evt.which)
                    code = evt.which;
                if (code=="117" || code == "83") {
                    performSaveAction();
                    return false;
                }
            }
        };
    }
}
