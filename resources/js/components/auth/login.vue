
<template>

     <div class="content-auth">
        <div class="row no-gutters">

            <div class="col-lg-6">
                <div class="signin-hero"></div>
            </div>

            <div class="col-lg-6">
                <div class="sign-wrapper">
                    <div class="wd-100p">
                        <h3 class="mg-b-3">Log In</h3>
                        <p class="tx-color-03 tx-14 mg-b-40">Welcome to CAMM Admin Portal.<br> Please sign in to continue.</p>
                             <p v-if="errors.length">
                    <ul class="list-group">
                      <li v-for="error in errors" :key="error" class="list-group-item list-group-item-danger">{{ error }}</li>
                    </ul>
                                </p>                   
                            <!-- <div class="alert alert-danger d-none align-items-center" role="alert">
                                <i data-feather="alert-circle" class="mg-r-10"></i> Warning
                            </div>

                            <div class="alert alert-success d-none align-items-center" role="alert">
                                <i data-feather="alert-circle" class="mg-r-10"></i> Success
                            </div> -->
                        <form @submit="login">
                            <div class="form-group">
                                <label for="username"><i class="tx-danger">*</i> Username</label>                                
                                <input type="text" name="username" id="username" v-model="input.username" placeholder="Username" class="form-control" />
                                <span class="text-danger d-none">Text danger</span>
                            </div>

                            <div class="form-group">
                                <label for="password"><i class="tx-danger">*</i> Password</label>
                                <input type="password" name="password" v-model="input.password" placeholder="Password" class="form-control"/>                                
                                <span class="text-danger d-none">Text danger</span>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm">Login</button>
                                &nbsp;
                                <a :href="forgot_password" class="btn btn-info btn-sm">Forgot Password</a>
                                
                            </div>                            
                        
                            <div class="cms-footer mg-t-50">
                                <hr>
                                <p class="tx-gray-500 tx-10">Admin Portal v1.0 • Developed by WebFocus Solutions, Inc. &copy; 2022</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script  type="application/javascript">
const env_Url = process.env.MIX_APP_URL;
export default {
  name: "Login",
  data() {
    return {
      errors: [],
      result: "",
      forgot_password: env_Url + "/auth/forgot_password",
      input: {
        username: "",
        password: "",
        passexpiration: 0,
      },
    };
  },
  methods: {
    login: function (e) {
      this.errors = [];
      if (!this.input.username) {
      this.errors.push("Username required.");
      } else if (!this.input.password) {
        this.errors.push("Password required.");
      } else {
        axios
          .post(env_Url + "/auth/login", this.input)
          .then((response) => {
            if (response.data.result == "Success") {
              
              // localStorage.removeItem('expDay');
              // localStorage.expDay = response.data.expDay;
              // localStorage.removeItem('loggedin');
              window.location.href = env_Url + "/dashboard";
            } else if (response.data.result == "First Login") {
               window.location.href = env_Url + "/auth/change_password";
            } else if (response.data.result == "Password Expired!") {
              this.errors.push("Password Expired! Contact Administrator.");
            } else {
              this.errors.push("Invalid Credential.");
            }
          })

          .catch(function (error) {});
      }

      e.preventDefault();
    },
  },
};
</script>