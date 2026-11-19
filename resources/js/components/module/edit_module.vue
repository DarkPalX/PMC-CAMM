
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
              <a :href="modules">Modules</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Edit Module
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Edit Module</h4>
      </div>
    </div>

    <div class="row row-sm">
      <div class="col-lg-6">
        <input type="hidden" v-model="form.id">

        <div class="form-group">
          <label class="d-block">Status *</label>
          <input
            type="checkbox"
            id="isActive"
            :checked="form.checked == 1"
          />
        </div>
        
        <div class="form-group">
          <label class="d-block">Module Description *</label>
          <input
            type="text"
            class="form-control"
            v-model="form.description"
            placeholder="Module Description"
          />
        </div>

      </div>

      <div class="col-lg-12 mg-t-30">
        <button
          class="btn btn-primary btn-sm"
          @click.prevent="updateModule"
        >
          Update
        </button>
        &nbsp;
        <a
          :href="modules"
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
const env_Url = process.env.MIX_APP_URL;

export default {
  props: ["module"],
  data() {
    return {
      errors: [],
      success: "",
      modules: env_Url + "/modules/list",
      dashboard: env_Url + "/dashboard",

      selectedValue: "",    

      form: {
        id: this.module.id,        
        description: this.module.description,        
        active: true,
        checked: this.module.active,        
      },
            
    };
  },  

  methods: {

    async updateModule() {
      this.form.active = document.getElementById('isActive').checked;
      // console.log(this.form.active);
      const res = await this.submit(
        "post",
        "/modules/update",
        this.form,
        {
          headers: {
            "Content-Type":
              "multipart/form-data; charset=utf-8; boundary=" +
              Math.random().toString().substr(2),
          },
        }
      );
      if (res.status === 200) {
        this.updmessage();
      } else {
        this.errors = res.data.errors;
      }
    },

  },
};
</script>
