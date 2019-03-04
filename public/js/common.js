$(function () {
    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

    $('.father').on('click',function () {
        var el = $(this).parents(".panel-success").find(".panel-border .icheckbox_flat-green");
        var ck = $(this).parents(".panel-success").find(".children");
        if($(this).find($(":checkbox")).prop('checked')){
            //取消勾选子菜单
            el.removeClass('checked');
            ck.prop('checked',false)
        } else {
            //勾选所有子菜单
            el.addClass('checked');
            ck.prop('checked',true);
        }
    })

    $('.son').on('click',function () {
        $(this).parents(".panel-success").find(".icheckbox_flat-green:eq(1)").addClass('checked');
        $(this).parents(".panel-success").find(".father").prop("checked",true);
    })
});
/**
 * 表单验证
 */
$().ready(function() {
    $('.select_from').validate({
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().parent());
        }
    });

    $("#reset").click(function() {
        $(':input')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    });

    $("#changepwdForm").validate({
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().parent());
        },
        rules: {
            old_pass: {
                required: true,
                minlength: 5
            },
            new_pass: {
                required: true,
                minlength: 5
            },
            confirm_pass: {
                required: true,
                minlength: 5,
                equalTo: "#new_pass"
            },
        },
        messages: {
            old_pass: {
                required: "请输入旧密码！",
                minlength: "您的密码必须至少有5个字符长"
            },
            new_pass: {
                required: "请输入新密码！",
                minlength: "您的密码必须至少有5个字符长"
            },
            confirm_pass: {
                required: "请再次输入新密码！",
                minlength: "您的密码必须至少有5个字符长",
                equalTo: "您输入的确认密码不正确，请重新输入！"
            },
        }
    });
});