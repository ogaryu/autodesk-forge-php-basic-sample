/**
 * Created by ryuji on 2016/10/18.
 */

var AjaxFormController = function() {

    $(document).off('submit', '.ajaxform');

    $(document).on('submit', '.ajaxform', function(e){

        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();
        var formActionUrl = form.attr('action');
        var formRequestAction = form.attr('data-request-action');
        var formMethod = form.attr('method');

        if(formMethod == undefined || formMethod == ''){
            formMethod = 'GET';
        }
        
        if(formData == undefined || formData == ""){
            formData = {};
        }
        
        if(formActionUrl.indexOf('{') != -1){
            var matches = formActionUrl.match(/{(.*?)}/g);

            for (i=0; i<matches.length; i++) {
                var paramValue = $(matches[i].match(/\{(.+)\}/)[1]).val();
                formActionUrl = formActionUrl.replace(matches[i], paramValue);
            }
        }
        
        var clientRequestData = {
            URL: formActionUrl,
            Type: formMethod,
            ContentType: 'application/json',
            Data: formData
        };

        ClientRequestData.setData(clientRequestData);
        
        $.ajax({
            type: formMethod,
            url : formActionUrl,
            data : formData,
            async: false,
            contentType: 'application/json',
            dataType: 'json',
            success : function(data){

                if(formRequestAction == 'login'){

                    ServerResponseData.setData(data['url']);

                    var authWindow = AuthenticationWindowView(data['url'], "Autodesk Login", 800, 400);
                    authWindow.onload = function() {};
                    authWindow.onbeforeunload = function(){

                        return ;
                    }
                }
                else if(formRequestAction == 'logout'){
                    
                    //document.location.reload(true);
                    ServerResponseData.setData(data['content']);
                }
                else if(formRequestAction == 'userprofile'){
                    ServerRequestData.setData(data['request']);
                    ServerResponseData.setData(data['content']);
                }
                else if(formRequestAction == 'hubs'){
                    ServerRequestData.setData(data['request']);
                    ServerResponseData.setData(data['content']);
                }
                else if(formRequestAction == 'projects'){
                    ServerRequestData.setData(data['request']);
                    ServerResponseData.setData(data['content']);
                }
                else if(formRequestAction == 'items'){
                    ServerRequestData.setData(data['request']);
                    ServerResponseData.setData(data['content']);

                    SetUpViewerOpenEvent();
                }
            }
        });
    });
};

function Get3LeggedToken (onGetAccessToken) {

    var xmlHttp = null;
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", '/token', false);
    xmlHttp.send(null);

    var token = xmlHttp.responseText;
    
    if (token != '') console.log('3 legged token (User Authorization): ' + token);
    
    return token;
}

AjaxFormController();
