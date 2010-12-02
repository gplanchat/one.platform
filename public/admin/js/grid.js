
(function(ns){
    ns.Grid = $.one.Class.create({
        __construct: function(element) {
            element = $(element);

            var pager    = $('.page', element);
            var limit    = $('.limit', element);
            var headings = $('.headings .nobr a', element);
            var lines    = $('.grid tr.line', element);

            var redirect = function(e) {
                var params = [
                    {
                        name:  'p',
                        value: pager.val()
                    }, {
                        name:  'n',
                        value: limit.val()
                    }];

                headings.each(function(index, element) {
                    element = $(element);
                    if (element.hasClass('sort-arrow-desc')) {
                        params[params.length] = {
                            name: 'sort[' + element.attr('name') + ']',
                            value: 'desc'
                            };
                    } else if (element.hasClass('sort-arrow-asc')) {
                        params[params.length] = {
                            name: 'sort[' + element.attr('name') + ']',
                            value: 'asc'
                            };
                    }
                    });

                document.location = (new $.one.Url('admin')).toString(params);
                }

            pager.change(function(e){
                e.preventDefault();

                redirect();
                });

            limit.change(function(e){
                e.preventDefault();

                redirect();
                });

            headings.click(function(e){
                e.preventDefault();

                var element = $(this);
                if (element.hasClass('sort-arrow-desc')) {
                    element.removeClass('sort-arrow-asc');
                    element.removeClass('not-sort');
                    element.addClass('sort-arrow-desc');
                } else if (element.hasClass('sort-arrow-asc')) {
                    element.removeClass('sort-arrow-asc');
                    element.removeClass('sort-arrow-desc');
                    element.addClass('not-sort');
                } else {
                    element.removeClass('sort-arrow-desc');
                    element.removeClass('not-sort');
                    element.addClass('sort-arrow-asc');
                }
                redirect();
                });
    
            lines.click(function(e) {
                e.preventDefault();

                document.location = $('.action a.default', $(this)).attr('href');
                });
            }
        });

    ns.Grid;
})($.one)