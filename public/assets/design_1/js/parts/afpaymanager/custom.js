/**
 *#################################################################################################
 *########################################### MANAGE PAYMENT #######################################
 *##################################################################################################
 */
const togglePaymentChannel = (data) => {
    var form = $('#formpaymentrequest');
    var paymentmethodid = data.getAttribute('id');
    
    $('#submitpaymentbtn').addClass('loadingbar');
            
    const url   = data.getAttribute('url');
    const token = data.getAttribute('token');
    const currency = data.getAttribute('currency');
    var btntransaction = $('#submitpaymentbtn').text();
    const request_data = {
        _token      : token,
        currency    : currency,
        orderid     : data.getAttribute('orderid'),
        amount      : data.getAttribute('totalprice'),
    }
    $.ajax({
        type: 'post',
        url:  url,
        data: request_data,
        datatype: 'json',
        beforeSend: function () {
            $('#submitpaymentbtn').text('Process started...').prop('disabled',false);
            form.find('*').prop('disabled', true);
            $(document.body).css({'cursor' : 'wait'});
        },
        success: function (json) {
            if (json.status == 200){
                const result = json.link;
                var link = window.parent.document.createElement('a');
                link.href = result;
                link.target = '_blank';
                link.click();
                $(document.body).css({'cursor' : 'default'});
                $('#submitpaymentbtn').removeClass('loadingbar');
                $('#'+paymentmethodid).prop('checked',false);
                form.find('*').prop('disabled', false);
            }else{
                console.log(json.message)
            }
        },
        complete: function () {
            $('#submitpaymentbtn').removeClass('loadingbar');
            $('#'+paymentmethodid).prop('checked',false);
        },
    });

}
