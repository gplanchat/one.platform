
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

        var clientIdentity = '';
        identity.blur(function(){
            });

        $(document).ready(function(){
            if (identity.val() != '') {
                identity.blur();
                password.hover();
            }
            });

        var login = function(form, values, serverSalt){
            $(values).each(function(index, field) {
                switch (field['name']) {
                case 'login[password]':
                    var serverHash = Hash.sha256(Bytemap.Filter.Input.raw(password.val()).append(serverSalt));
                    var clientHash = Hash.sha256(serverHash.append(transferSalt));

                    field['value'] = clientHash.toString();
                    break;

                case 'login[salt]':
                    field['value'] = $('.login.salt', form).val();
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
            };

        form.submit(function(event){
            event.preventDefault();

            var serverSalt = new Bytemap(0);
            var form = $(this);

            clientIdentity = $('.login.identity', $(this)).val();

            $.post(loadIdentityAction, {identity: clientIdentity}, function(response, status, request) {
                if (status !== 'success') {
                    return;
                }

                if (response['exists']) {
                    serverSalt = Bytemap.Filter.Input.base64(response['salt']);
                    login(form, form.serializeArray(), serverSalt);
                }
                }, 'json');
            });
        };
    })($.one);