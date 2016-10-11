$(function(){
    var validate = $('#updateFaqForm').validate({                
        rules: {
            category: {
                required:true,
            },
            question: {
                required:true,
                remote: {
                    url: "/admin/faq/update/fields/check",
                    type: "get",
                    dataType: "json",
                    data: {
                        id: function() {
                            return $('#updateFaqForm').data('id');
                        },
                        question: function() {
                            return $('#question').val();
                        }
                    }
                }
            },
            answer: {
                required:true,
            }
        },
        messages: {
            category: {
                required: "请选择问题分类",
            },
            question: {
                required: "请输入问题标题",
                remote: "问题标题已存在，请更改！"
            },
            answer: {
                required: "请输入问题答复",
            }
        }  
    });
});