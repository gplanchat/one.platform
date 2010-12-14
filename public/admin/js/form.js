(function(ns){
    $(document).ready(function(){
        $('.wysiwyg').tinymce({
            theme: "advanced",
            plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            elements: 'nourlconvert',
            convert_urls: false
            });
        });
    })($.one);