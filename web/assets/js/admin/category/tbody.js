$(document).ready(function(){
    $('.list-table .td.name>i').click(function() {
        var $parentNode = $(this).closest('.row');
        if ($parentNode.hasClass('row-expand')) {
            $parentNode.removeClass('row-expand').addClass('row-collapse');
            $(this).removeClass('fa-caret-down').addClass('fa-caret-right');
            $parentNode.next('ul.list-table').find('>li').slideDown();
        } else if ($parentNode.hasClass('row-collapse')) {
            $parentNode.removeClass('row-collapse').addClass('row-expand');
            $(this).removeClass('fa-caret-right').addClass('fa-caret-down');
            $parentNode.next('ul.list-table').find('>li').slideUp();
        }
    });
});