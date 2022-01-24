<template>
  <div>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Login</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="email" v-model="data.email" class="form-control form-control-user"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" v-model="data.password" class="form-control form-control-user"
                                                placeholder="Password">
                                        </div>
                                        <button type="button" @click.prevent="login" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {
        email: '',
        password: ''
      },
      isLoggedIn: false,
    }
  },
  methods: {
    async login() {
      if(this.data.email.trim()=='') return this.error('Email is required');
      if(this.data.password.trim()=='') return this.error('Password is required');
      
      const res = await this.callApi('post', '/admin/login', this.data);
      if (res.status === 200) {
          this.success(res.data.msg);
          window.location = '/dashboard';
      } else {
        if(res.status === 401) {
            this.info(res.data.msg);
        } else if (res.status==422) {
            for(let i in res.data.errors){
                this.error(res.data.errors[i][0]);
            }
        } else {
            this.error('Something went wrong!');
        }
      }
    }
  }
}
</script>