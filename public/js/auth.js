
(function(ns){
    ns.User = {};

    var Security = $.one.Security;
    var Bytemap = Security.Bytemap;
    var Hash = Bytemap.Hash;

    ns.User.login = function(form, fields) {
        form = $(form);

        var identity = $('.login.identity', form);
        var password = $('.login.password', form);
        var transferSalt = Bytemap.Filter.Input.base64($('.login.salt', form).val());
        var loadIdentityAction = $('input#load_identity', form).val();

        var serverSalt = new Bytemap(0);
        var clientIdentity = '';
        identity.blur(function(){
            if (clientIdentity == $(this).val()) {
                return;
            }
            clientIdentity = $(this).val();
            var result = $.post(loadIdentityAction, {identity: clientIdentity}, function(response, status, request) {
                if (status !== 'success') {
                    return;
                }

                if (response['exists']) {
                    serverSalt = Bytemap.Filter.Input.base64(response['salt']);
                }
                }, 'json');
            });

        $(document).ready(function(){
            if (identity.val() != '') {
                identity.blur();
                password.hover();
            }
            });

        form.submit(function(event){
            event.preventDefault();

            var form = $(event.target);
            var values = form.serializeArray();

            $(values).each(function(index, field) {
                switch (field['name']) {
                case 'login[password]':
                    var serverHash = Hash.sha256(Bytemap.Filter.Input.raw(password.val()).append(serverSalt));
                    var clientHash = Hash.sha256(serverHash.append(transferSalt));

                    field['value'] = clientHash.toString();
                    break;

                case 'login[stealth_salt]':
                    field['value'] = transferSalt.toString(Bytemap.Filter.Output.base64);
                    break;

                case 'login[identity]':
                case 'success':
                case 'error':
                    break;

                default:
                    delete values[index];
                }
                });

            $.post(form.attr('action'), values, function(response, status, request) {
                if (status !== 'success') {
                    return;
                }

                if (response.error === false) {
                    document.location = response.redirect;
                }
                }, 'json');
            });
        };
    })($.one);