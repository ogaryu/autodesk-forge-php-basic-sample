/**
 * Created by ryuji on 2016/10/18.
 */


var ClientRequestData = {

    setData : function(data) {

        if(typeof data == 'object'){
            this.data = JSON.stringify(data);
        }

        this.data = data;
        
        $('#client-request-body').html(this.data);
    },

    getData : function() {
        return this.data;
    }
};

var ServerResponseData = {
    
    setData : function(data) {

        if(typeof data == 'object'){
            this.data = JSON.stringify(data);
        }
        
        this.data = data;

        $('#server-response-body').html(this.data);
    },

    getData : function() {
        return this.data;
    }
};