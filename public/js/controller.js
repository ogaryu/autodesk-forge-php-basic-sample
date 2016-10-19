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
        var formActionStr = formActionUrl.substr(1);
        var formMethod = form.attr('method');

        if(formMethod == undefined || formMethod == ''){
            formMethod = 'GET';
        }
        
        if(formData == undefined || formData == ''){
            formData = {action: formActionStr};
        }
        
        if(formActionUrl.indexOf('{') != -1){
            
            var matches = formActionUrl.match(/{(.*?)}/g);

            for (i=0; i<matches.length; i++) {
                var paramValue = $(matches[i].match(/\{(.+)\}/)[1]).val();
                formActionUrl.replace(matches[i], paramValue);
            }
            
            console.log(formActionUrl);
            
            //var actionUrlParam = formActionUrl.substring(formActionUrl.indexOf('#'), formActionUrl.indexOf('}'));
        }
        
        var clientRequestData = {
            URL: formActionUrl,
            Type: formMethod,
            ContentType: 'application/json',
            Data: form.serialize()
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
                
                console.log(data);

                if(formActionStr == 'login'){

                    ServerResponseData.setData(data);

                    var authWindow = AuthenticationWindowView(data['url'], "Autodesk Login", 800, 400);
                    authWindow.onload = function() {};
                    authWindow.onbeforeunload = function(){

                        return ;
                    }
                }
                else if(formActionStr == 'logout'){
                    
                    //document.location.reload(true);
                    ServerResponseData.setData(data);
                }
                else if(formActionStr == 'userprofile'){
                    
                    ServerResponseData.setData(data);
                }
                else if(formActionStr == 'hubs'){

                    ServerResponseData.setData(data);
                }
                else if(formActionStr == 'projects'){
                    
                    ServerResponseData.setData(data);
                }
                else if(formActionStr == 'items'){
                    $("#items-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#items-body").slideDown();
                    setAjaxForm();
                    setViewer();
                }
                else if(formActionStr == 'issues'){
                    $("#issues-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#issues-body").slideDown();
                    setAjaxForm();
                }
            }
        });
    });
};

AjaxFormController();