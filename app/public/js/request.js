function api_request(local, action, callback, post = {}, gets = ""){

    let url = URL + "/api/" + local + "/" + action + "/" + gets;

    $.ajax({
        url: url,
        data: post,
        method: "POST",
    }).done(function(response) {
        if(!response.error){
            nonce = response.nonce;
            callback(response.response);
        } else {
            console.log(response);
            callback(false);
        }
    }).fail(function(err){
        console.log(err);
        callback(false);
    });
    
}