const google = {
    IGoogleAuth : null,
    IGoogleUser: null,
    get GoogleAuth() {
        return this.IGoogleAuth;
    },
    set GoogleAuth(val){
        this.IGoogleAuth = val;
    },
    get GoogleUser(){
        return this.IGoogleUser;
    },
    set GoogleUser(val){
        this.IGoogleUser = val;
    },
    init: () =>{
        gapi.load('auth2', () => {
            google.GoogleAuth = gapi.auth2.init({ client_id: '42685965557-urskj9esf47q1rfti99dk6ud1kdtp1t4.apps.googleusercontent.com' })
        });
    },
    signIn: () => {
        google.GoogleAuth.signIn({
            scope: 'profile'
        }).then(
            resp => {
                google.GoogleUser = google.GoogleAuth.currentUser.get();
                google.IsLoggedIn();
            }
        ).catch(result => {
            console.log(result);
        })
    },
    IsLoggedIn: () => {
        const token = google.GoogleUser.getAuthResponse().id_token;
        $.ajax({
            url: "processors/registration.req.php",
            type: "post",
            data: { "NCS_ACTION": "loginWithGoogle", "NCS_TOKEN": token },
            beforeSend: () => {
                $("#overlay").show();
                $("#content").hide();
            },
            success: (data) => {
                $("#overlay").hide();
                $("#content").show();
                $('#ajax').html(data);
            },
            error: () => { etoast("Something went wrong with your authentication, please try again later!"); }
        })
    },
    isSignedIn: () => {
        return google.GoogleAuth.getAuthInstance().isSignedIn.get();
    }
    
}
export default google