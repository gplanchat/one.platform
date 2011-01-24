
(function (ns) {
    ns.Tree = $.one.Class.create({
        __construct: function(element) {
            element = $(element);
            var ns = element.children('dl').first().attr('xmlns:tree');

            var loadChildren = function(id, child){
                var id = $(child).first().attr('tree:id');
                var parent = child;

                $.post('website-child-list-ajax', {website: id}, function(response, status, request) {
                    if (status !== 'success') {
                        return;
                    }

                    var dl = document.createElement('dl');
                    for (item in response) {
                        var dt = document.createElement('dt');
                        var label = document.createTextNode(response[item]);
                        dt.appendChild(label);
                        var attrib = document.createAttribute('title', item);
                        dt.appendChild(attrib);
                        dl.appendChild(dt);

                        var dd = document.createElement('dd');
                        dl.appendChild(dd);
                    }
                    $(parent).next()[0].appendChild(dl);
                    }, 'json');
                };
            
            element.children('dl').first().children('dt').each(loadChildren);
            }
        });
})($.one)