$(function() {
  var validate = $("#updateForm").validate({                
    rules:{
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
        var phone = /^1[3|4|5|7|8]\d{9}$/
        if (length == 11 && phone.test(value)) {}
        return this.optional(element) || (length == 11 && phone.test(value));
        }, "手机号码格式错误");
});
