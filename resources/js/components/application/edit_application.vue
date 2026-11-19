
<template>
  <div class="container pd-x-0">
    <div
      class="
        d-sm-flex
        align-items-center
        justify-content-between
        mg-b-20 mg-lg-b-25 mg-xl-b-30
      "
    >
      <div>
        <div v-if="errors">
          <p v-for="error in errors" :key="error" class="alert alert-danger">
            {{ error }}
          </p>
        </div>
        <p v-if="success != ''" class="alert alert-success">
          {{ success }}
        </p>

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-5">
            <li class="breadcrumb-item" aria-current="page">
              <a :href="dashboard">CAMM</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              <a :href="applications">Applications</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Edit Applications
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Edit Applications</h4>
      </div>
    </div>

    <div class="row row-sm">
      <div class="col-lg-6">
        <input type="hidden" v-model="form.id" />

        <div class="form-group">
          <label class="d-block">Status *</label>
          <input type="checkbox" id="isActive" :checked="form.checked == 1" />
        </div>

        <div class="form-group">
          <label class="d-block">Description *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.description"
            placeholder="Description"
          />
        </div>

        <div class="form-group">
          <label class="d-block">Server Name *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.server_name"
            placeholder="Server Name"
          />
        </div>

        <div class="form-group">
          <label class="d-block">Database Name *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.database_name"
            placeholder="Database Name"
          />
        </div>

        <div class="form-group">
          <label class="d-block">Database Username *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.database_username"
            placeholder="Database Username"
          />
        </div>

        <div class="form-group">
          <label class="d-block">Database Password *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.database_password"
            placeholder="Database Password"
          />
        </div>
        <div class="form-group">
          <label class="d-block">Login Field Name *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.loginFieldName"
            placeholder="Login Field Name"
          />
        </div>
        <div class="form-group">
          <label class="d-block">Application URL *</label>
          <input
            type="text"
            class="form-control"
            required
            v-model="form.application_url"
            placeholder="Application URL"
          />
        </div>
      </div>

      <div class="col-lg-12 mg-t-30">
        <button
          class="btn btn-primary btn-sm"
          @click.prevent="updateApplication"
        >
          Update
        </button>
        &nbsp;
        <a
          :href="applications"
          class="btn btn-outline-secondary btn-sm"
          type="cancel"
          >Cancel</a
        >
      </div>
    </div>
  </div>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>

<script  type="application/javascript">
export default {
  props: ["application"],
  data() {
    return {
      errors: [],
      success: "",
      applications: this.$env_Url + "/applications/list",
      dashboard: this.$env_Url + "/dashboard",

      form: {
        id: this.application.id,
        description: this.application.description,
        server_name: this.application.server_name,
        database_name: this.application.database_name,
        database_username: this.application.dbusername,
        database_password: this.application.dbpass,
        application_url: this.application.app_url,
        active: true,
        checked: this.application.active,
        loginFieldName : this.application.loginFieldName,
      },
    };
  },

  methods: {
    async updateApplication() {
      this.form.active = document.getElementById("isActive").checked;
      const res = await this.submit("post", "/applications/update", this.form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        this.updmessage();
      } else {
        this.ermessage(res.data.errors);
      }
    },
  },
};
</script>

