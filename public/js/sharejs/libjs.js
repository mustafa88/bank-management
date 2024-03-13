/**
 *
 */
function animationNewElement(thisElement){
    $(thisElement).addClass('add-animation');
    setTimeout(() => {
        $(thisElement).removeClass('add-animation');
    }, 2000);
}

/**
 *
 * @param msg
 * @param style
 */
function notify(msg,style) {
    if(style===undefined){
        style = "info";
    }
    $.notify(msg,style);
}


/**
 *
 * @param url url ajax
 * @param formName form name of page
 * @returns {*}
 * @constructor
 */
function SendToAjax(url ,type ,formName ,dataObj){

    //formName===undefined?'myform':formName;
    if(formName===undefined || formName==null){
        formName = 'myform';
    }
    if(type===undefined){
        type = 'POST';
    }
    /**
     headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            "content-type": "application/json",
            "accept": "application/json",
        },
     */

    /**
     dataObj = {};
     dataObj['statos']= $("#statos").val();
     dataObj['lid']= $("#lid").val()
     dataObj['mesima']= $("#mesima").val();
     dataObj['tmp']= $("#tmp").val();
     if(dataplus!=undefined){
		dataObj = {...dataObj ,...dataplus};
	}
     data  = $.param(dataObj);
     */
    let resultAjax;
    let dataFrom = $("#"+formName ).serialize();
    if(dataObj!== undefined){
        dataObj  = $.param(dataObj);
        if(dataFrom!=''){
            dataFrom = dataFrom + "&" + dataObj
        }else{
            dataFrom = dataObj;
        }
    }

    //alert(dataFrom)
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        type: type,
        //contentType: "application/json",
        //accept: "application/json",
        url: url,
        dataType: 'json',
        data: dataFrom,
        //processData: false,
        //contentType: false,
        //cache: false,
        async: false,
        success: function (data) {
            //console.log(data);
            resultAjax = data;
            //alert(data.status);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(JSON.parse(xhr.responseText));
            let responseText = JSON.parse(xhr.responseText);
            let message = responseText["message"];
            let errors = responseText["errors"];
             console.log(responseText);
            notify(message,'error');
            $.each( errors, function( key, value ) {
                notify( value ,'error');
            });
        }

    });
    return resultAjax;
}
/**
function SendToAjax_old(dataplus) {
    let data;
    //data =  $("#eam").serialize();

    dataObj = {};
    dataObj['statos'] = $("#statos").val();
    if (dataplus != undefined) {
        dataObj = {...dataObj, ...dataplus};
    }
    data = $.param(dataObj);
    //console.log(data);
    //alert(data);
    //return;
    var JSONobject;
    $.ajax({
        url: "namer/EditDegemTbl_ajax.php",
        type: "POST",
        dataType: 'json',
        data: data,
        async: false,
        success: function (data) {
            JSONobject = data;
            return JSONobject;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('ERROR MSG 123');
            return '';
        }
    });
    return JSONobject;
}**/
