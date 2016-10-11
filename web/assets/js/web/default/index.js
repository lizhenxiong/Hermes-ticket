$(function(){
    var validate = $("#myform").validate({  
  
        submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form   
            alert("提交表单");
            form.submit();   //提交表单   
        },

        rules:{
            name:{
                required:true
            },
            email:{
                required:true,
                email:true
            },
            mobile:{
                required:true,
                mobile:true
            }               
        }           
    });

    jQuery.validator.addMethod("mobile", function(value, element) {
        var length = value.length;
        var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/
        return this.optional(element) || (length == 11 && mobile.test(value));
        }, "手机号码格式错误");

    $('#upload').HermesUpload({
        url:'',
        success:function( data ) {
            $('#upload-form').append('<input type="text" value="'+data.uri+'">');
        },
        error:function( err ) {
            console.info( err );
        },
        buttonText : '选择文件',
        chunked:true,
        chunkSize:512 * 1024,
        fileNumLimit:5,
        fileSizeLimit:500000 * 1024,
        fileSingleSizeLimit:50000 * 1024,
        accept: {}
    });

    var $impPreview = $('.test-preview')
        $.each($impPreview, function(name, value){
            var viewer = new Viewer(document.getElementById(this.id), {
                url: 'data-original'
            });
    });

    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd  hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-right"
    });

});