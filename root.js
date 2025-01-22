const urlSfpl = `http://192.168.240.162/onedrive/X/Project/P2P-TDS`;
const urlApi = `${urlSfpl}/api`;
const urlApiAuth = `${urlApi}/auth.php`;
const urlApiLogout = `${urlApi}/logout.php`;
const urlApiTds = `${urlApi}/tds.php`;
const urlApiGetTds = `${urlApi}/getTds.php`;
const urlApiHistory = `${urlApi}/history.php`;
const urlApiDeleteTds = `${urlApi}/dlt.php`;
const urlExport = `${urlApi}/exp.php`;

const filename = window.location.pathname.split('/').pop();
const token = localStorage.getItem('token');

(function() {
    // Redirect to login if new
    if (filename !== 'login.html' && (!token || token.trim() === "")) {
        console.log("Redirecting to login...");
        window.location.href = `${urlSfpl}/login.html`;
        return;
    }
    
})();

function getToken() {
    return localStorage.getItem('token');
}