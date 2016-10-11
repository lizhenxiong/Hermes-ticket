$(function(){
    var validate = $('#createCategoryForm').validate({                
        rules:{
            priority:{
                required:true,
            },
            name:{
                required:true,
                remote: {
                    url: "/admin/category/create/fields/check",     //后台处理程序
                    type: "get",               //数据发送方式
                    dataType: "json",
                    data: {                     //要传递的数据
                        name: function() {
                            return $('#name').val();
                        }
                    }
                }
            },
            delayedTime:{
                required:true,
                remote: {
                    url: "/admin/category/create/fields/check",     //后台处理程序
                    type: "get",               //数据发送方式
                    dataType: "json",
                    data: {                     //要传递的数据
                        delayedTime: function() {
                            return $('#delayedTime').val();
                        }
                    }
                }
            }
        },
        messages: {
            priority: {
                required: "请选择优先级"
            },
            name: {
                required: "请输入类型名",
                remote: "类型名已存在"
            },
            delayedTime: {
                required: "请输入滞留时间 /分钟",
                remote: "滞留时间不得少于10分钟"
            }
        }  
    });
});