
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
              <a :href="announcements">Announcements</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Add New Announcement
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Add New Announcement</h4>
      </div>
    </div>

    <div class="row row-sm">
      <div class="col-lg-6">
        <div class="form-group">
          <label class="d-block">Title *</label>
          <input
            type="text"
            class="form-control"
            v-model="form.title"
            placeholder="Title"
          />
        </div>

        <div class="form-group">
          <label class="d-block">Date Range *</label>
          <div class="row row-sm pb-0">
            <div class="col-6 mb-2">
              <!-- <div
                class="input-group input-medium date date-picker"
                data-date="{{ today() }}"
                data-date-format="yyyy-mm-dd"
                data-date-viewmode="years"
              >
                <input
                  required
                  type="date"
                  v-model="form.date_from"
                  class="form-control"
                />
                
              </div> -->
              <!-- <Calendar id="data_from" v-model="form.date_from" :showTime="true" :showIcon="true" /> -->
              <Datepicker v-model="form.date_from"></Datepicker>
            </div>

            <div class="col-6 mb-2">
              <!-- <Calendar id="data_to" v-model="form.date_to" :showTime="true" :showIcon="true" /> -->
              <Datepicker v-model="form.date_to"></Datepicker>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="d-block">Recipients *</label>
          <MultiSelect
            v-model="form.recipient"
            :options="users"
            optionLabel="username"
            placeholder="Select Recipients"
            style="width: 100%"
          />
        </div>
        <div class="form-group">
          <label class="d-block">Message *</label>
          <textarea
            class="form-control"
            rows="3"
            v-model="form.message"
            placeholder="Message"
          ></textarea>
        </div>
      </div>

      <div class="col-lg-12 mg-t-30">
        <button
          class="btn btn-primary btn-sm"
          @click.prevent="createAnnouncement"
        >
          Submit
        </button>
        &nbsp;
        <a
          :href="announcements"
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
  data() {
    return {
      errors: [],
      success: "",
      announcements: env_Url + "/announcements/list",
      dashboard: env_Url + "/dashboard",
      users: [],
      form: {
        title: "",
        date_from: "",
        date_to: "",
        recipient: [],
        message: "",
      },
     
    };
  },
  created() {
    this.fetchUsers();
  },

  methods: {
    async createAnnouncement() {
      const res = await this.submit("post", "/announcements/store", this.form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        // window.location.href = env_Url + "/announcements/list",
        // this.success = "Announcement Successfully created";
        this.smessage();
        this.form.title = "";
        this.form.date_from = "";
        this.form.date_to = "";
        this.form.recipient = "";
        this.form.message = "";
      } else {
        this.errors = res.data.errors;
      }
    },
    async fetchUsers() {
      const res = await this.getDataFromDB("get", "/announcements/recipients");
      this.users = res.data;
    },
  },
};
</script>
