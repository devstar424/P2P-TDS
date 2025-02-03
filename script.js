let boolApiRunning = false;

async function postApi(objBody,url,onSuccess,onError,loader2 = null) {
    if(boolApiRunning){
        return;
    }
    try {
        boolApiRunning = true;
        objBody.token = getToken();
        console.log(objBody);
        const options = {method: 'POST', body: JSON.stringify(objBody)};
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        //const data = await response.text();
        console.log(data);
        boolApiRunning = false;
        if(data.res == "s"){
            onSuccess(data.data);
        }else if (data.res == "ft") {
            logout();
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        onError(error);
        console.error('Err: ', error);
    }
}
function logout(){
    localStorage.removeItem('p2ptdstoken');
    localStorage.removeItem('adminName');
    postApi({},urlApiLogout,(data) =>{
        window.location.href = `${urlSfpl}/login.html`;
    }, (error) => {
        alert(error);
    });
}

HTMLInputElement.prototype.setDateTime = function(timestamp) { 
    this.value = timestamp.replace(' ', 'T').slice(0, -3);
}
HTMLInputElement.prototype.getDateTime = function() {
    return this.value.replace('T', ' ') + ':00'; 
}