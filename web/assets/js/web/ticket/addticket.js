$(function(){
    var validate = $("#addticketform").validate({                
        rules:{
            title:{
                required:true
            },
            name:{
                required:true
            },
            email:{
                required:true,
                email:true
            },
            phone:{
                required:true,
                phone:true
            }
        }           
    });

    jQuery.validator.addMethod("phone", function(value, element) {
        var length = value.length;
        var phone = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/
        return this.optional(element) || (length == 11 && phone.test(value));
    }, "手机号码格式错误");

    $('#upload').HermesUpload({
        success:function( id, data ) {
            var id = parseInt(id.substr(id.length-1, 1))+1;
            var name = 'attachment'+id;
            $('.img-inputs').append('<input class="hidden" name="'+name+'" type="text" value="'+'files/'+data.uri+'">');
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
    });
});
