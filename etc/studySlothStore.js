/**
 * @author Athanasios Tsagris
 */


const searchInput = document.querySelector('#study_sloth_search');
const addInput = document.querySelector('#study_sloth_add');

const loader = document.querySelector('#study_sloth_loader');
const msg = document.querySelector('#action_msg');


function sendASlothRequest(params) {

    return new Promise(function(resolve, reject) {

        var httpc = new XMLHttpRequest();
        var url = "http://localhost:8000/etc/studySlothDispatcher.php";

        httpc.open("POST", url, true);
        httpc.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //httpc.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        httpc.onload = function() {
            if (httpc.readyState == 4 && httpc.status == 200) {
                resolve(httpc.responseText);
            } else {
                reject({
                    status: httpc.status,
                    statusText: httpc.statusText
                });
            }
        };

        httpc.onerror = function() {
            reject({
                status: httpc.status,
                statusText: httpc.statusText
            });
        };
        httpc.send(params);

    });
}

async function renderSlothRequest(action) {
    loader.style.display = 'block';
    msg.style.display = 'none';
    try {
        let params = '';
        switch (action) {
            case 'search':
                params = 'searchkey=' + searchInput.value;
                break;
            case 'add':
                params = 'addentry=' + addInput.value;
            default:
                break;
        }
        await sendASlothRequest(params)
        .then(res =>{
            console.log(JSON.parse(res).slothResp.msg); 
            const jsonObj = JSON.parse(res);
            return msg.innerHTML = '<span class="text msg_'+jsonObj.slothResp.class+'">'+jsonObj.slothResp.msg+'</span><span class="emoji_msg_'+jsonObj.slothResp.class+ '"></span>';
        })
        .catch((err)=>{});


    } finally {
        loader.style.display = 'none';
        msg.style.display = 'block';
    }

}
