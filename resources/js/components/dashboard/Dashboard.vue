
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
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item active" aria-current="page">
              Dashboard
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Welcome! {{ user.name }}</h4>
      </div>
    </div>

    <div class="row row-xs">
      <div
        class="col-6 col-sm-4 col-md-3 mb-3"
        v-for="application in applications"
        :key="application.id"
      >
        <div class="card card-select">
          <input id="1" type="checkbox" v-model="application.id" />
          <div class="card-header">
            <img
              class="mx-auto d-block w-100"
              :src="this.$env_Url + '/assets/images/app.png'"
            />
          </div>
          <div class="card-body">
            <span class="font-weight-bold">{{ application.description }}</span>
          </div>
          <div class="card-footer">
            {{ apps[application.id] }}
            <div
              class="flex justify-content-between mt-2"
              v-for="applog in loggedUsers"
              :key="applog.username"
              v-tooltip="'Logout user'"
              @click="logoutUser(applog.username, application.description)"
            >
              <div v-if="applog.app == application.id">
                <i data-feather="log-out"></i>
                <span class="small">
                  {{ applog.username }} : {{ applog.dateAccessed }}</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- col -->

      <!-- col -->
      <div class="d-none d-xl-block wd-100p ht-10"></div>
    </div>
    <!-- row -->
  </div>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
  <ConfirmDialog></ConfirmDialog>
</template>
<script>
export default {
  props: ["loggedinusers", "user", "applications"],
  data() {
    return {
      displayModal: false,
      expDay: 0,
      displayAnnouncement: false,
      announcements: null,
      loggedUsers : this.loggedinusers,
      apps: {},
      form: {
        user: "",
        app: "",
      },
    };
  },
  mounted() {},
  methods: {
    logoutUser(user, app) {
      this.form.user = user;
      this.form.app = app;
      this.$confirm.require({
        message: "Logout " + user + " in " + app + "?",
        header: "Activate Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: async () => {
          const res = await this.submit(
            "post",
            "/accessedapp/logoutUser",
            this.form
          );
          if (res.status === 200) {
            this.logoutmessage();
            this.fetchLoggedInUsers();
          } else {
            this.ermessage();
          }
        },
        // reject: () => {
        //   this.$toast.add({
        //     severity: "error",
        //     summary: "Cancelled",
        //     detail: "You have cancelled the process",
        //     life: 3000,
        //   });
        // },
      });
    },
    async fetchLoggedInUsers() {
      const res = await this.getDataFromDB("get", "/users/getLoggedIn");
      this.loggedUsers = res.data;
    },
  },
};
</script>