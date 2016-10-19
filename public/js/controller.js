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
            Data: form.serialize()
        };

        ClientRequestData.setData(clientRequestData);
        
        console.log(formData);
        
        $.ajax({
            type: formMethod,
            url : formActionUrl,
            data : formData,
            async: false,
            contentType: 'application/json',
            dataType: 'json',
            success : function(data){
                
                console.log(data);

                if(formRequestAction == 'login'){

                    ServerResponseData.setData(data);

                    var authWindow = AuthenticationWindowView(data['url'], "Autodesk Login", 800, 400);
                    authWindow.onload = function() {};
                    authWindow.onbeforeunload = function(){

                        return ;
                    }
                }
                else if(formRequestAction == 'logout'){
                    
                    //document.location.reload(true);
                    ServerResponseData.setData(data);
                }
                else if(formRequestAction == 'userprofile'){
                    
                    ServerResponseData.setData(data);
                }
                else if(formRequestAction == 'hubs'){

                    ServerResponseData.setData(data);
                }
                else if(formRequestAction == 'projects'){
                    
                    ServerResponseData.setData(data);
                }
                else if(formRequestAction == 'items'){
                    $("#items-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#items-body").slideDown();
                    setAjaxForm();
                    setViewer();
                }
                else if(formRequestAction == 'issues'){
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