$(function(){
    var validate = $('#distribute-tickets-form').validate({                
        rules:{
            operatorNo:{
                required:true,
                operatorNo:true,
                remote: {
                    url: "/workspace/ticket/transfer/operatorNo/check",     //后台处理程序
                    type: "get",               //数据发送方式
                    dataType: "json",
                    data: {                     //要传递的数据
                        operatorNo: function() {
                            return $('#operatorNo').val();
                        }
                    }
                }
            }                  
        },
        messages: {
            operatorNo: {
                required: "请输入客服编号",
                remote: "客服编号不存在"
            }
        }  
    });

    jQuery.validator.addMethod('operatorNo', function(value, element) {
        var length = value.length;
        var operatorNo = /^TO\d{6}$/
        return this.optional(element) || (length == 8 && operatorNo.test(value));
        }, '客户编号格式错误');
});