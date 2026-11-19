
<template>

        
  <div class="col-12">
    <Message severity="warn" :closable="false"> <p class="m-0">
      You only have {{ expday }} day/s left to change your password before it expires.
    </p></Message>
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
  </div>

  <div
    class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4"
    v-for="application in applications"
    :key="application.id"
  >
    <div class="card">
      <div class="card-header">
        <img
          class="mx-auto d-block w-100"
          :src="this.$env_Url + '/assets/images/app.png'"
        />
      </div>
      <div class="card-body">
        <span class="font-weight-bold d-block mg-b-20">{{
          application.description
        }}</span>
        <p class="tx-10 tx-color-02 mg-b-5 tx-uppercase">Date last Access</p>
        <p class="tx-12 tx-color-03 mg-b-0">{{ application.dateAccessed }}</p>
      </div>
      <div class="card-footer">
        <div class="flex justify-content-between mt-2">
          <button
            type="button"
            class="btn btn-primary btn-block"
            @click="gotoApp(application.app_url, application.id, application.description)" 
          >
            Go to App
          </button>
        </div>
      </div>
      <!-- col -->

      <!-- col -->
      <div class="d-none d-xl-block wd-100p ht-10"></div>
    </div>
    <!-- row -->
  </div>
  <Dialog
    header="Announcements"
    v-model:visible="displayAnnouncement"
    :style="{ width: '50vw' }"
    :modal="true"
  >
    <div
      class="m-0"
      v-for="announcement in announcements"
      :key="announcement.id"
    >
      <p class="m-0 font-weight-bold">{{ announcement.title }}</p>
      <p>{{ announcement.message }}</p>
    </div>
    <template #footer>
      <p style="position: absolute">
        <Checkbox id="chkread" v-model="checked" :binary="true" /> Don’t show
        this announcement again.
      </p>

      <Button
        label="Ok"
        icon="pi pi-check"
        @click="closeModalAnnouncement"
        autofocus
      />
    </template>
  </Dialog>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>
<script>
export default {
  props: ["user", "applications", "expday"],
  data() {
    return {
      displayModal: true,
      displayAnnouncement: false,
      // expday: this.expday,
      form: {
        username: this.user.username,
        app: "",
      },
      url: this.$env_Url,
      announcements: [],
      checked: true,
    };
  },
  created() {
    this.fetchAnnouncements();
  },
  methods: {
    async closeModalAnnouncement() {
      this.displayAnnouncement = false;
      if (this.checked) {
        const res = await this.submit("post",
        "/announcements/saveUserRead",  this.form);
      }
    },

    // vapt update; added description
    async gotoApp(url, appid, description) {
    
      const tokenizedSystems = ['COASTER', 'IVAN'];
      // ['DRS', 'IRMS', 'IMS']; // LOGIN UPDATE

      this.form.app = appid;
      const res = await this.submit(
        "post",
        "/accessedapp/create", this.form, {
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        }
      );
      
      if (res.status == 200) {
          var form = document.createElement("FORM");
        
        // vapt update; added condition to use alternative method of accessing booking systems
        if (tokenizedSystems.includes(description)) {
          const tokenResult = await this.submit(
              "POST",
              "/auth/token_generator",
              { appUrl: url },
              {
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              }
          );

          if (tokenResult.status === 200) {
              const form = document.createElement("form");

              form.method = "POST";
              form.action = tokenResult.data.url;
              form.target = "_blank";

              const input = document.createElement("input");
              input.type = "hidden";
              input.name = "token";
              input.value = tokenResult.data.token;

              form.appendChild(input);

              document.body.appendChild(form);
              form.submit();
              document.body.removeChild(form);
          }
      } else {
          form.method = "GET";
          form.action = url;
          form.target = url;
          // form.target = "_blank";
          var input = document.createElement("INPUT");

          input.name = "_token";
          input.type = "hidden";
          input.value = document.getElementById("csrf").getAttribute("content");
          form.appendChild(input);

          var username = document.createElement("INPUT");
          username.name = "username";
          username.type = "hidden";
          username.value = this.user.username;
          form.appendChild(username);

          var domainAccount = document.createElement("INPUT");
          domainAccount.name = "domainAccount";
          domainAccount.type = "hidden";
          domainAccount.value = this.user.username;
          form.appendChild(domainAccount);

          var domain = document.createElement("INPUT");
          domain.name = "domain";
          domain.type = "hidden";
          domainAccount.value = this.user.username;
          form.appendChild(domain);

          var password = document.createElement("INPUT");
          password.name = "password";
          password.type = "hidden";
          password.value = this.user.pass;
          form.appendChild(password);

          document.body.appendChild(form);
          window.open("", url);
          form.submit();
        }
      }
    },

    async fetchAnnouncements() {
      const res = await this.callApi(
        "get",
        "/announcements/getAnnouncementsPerUser"
      );
      this.announcements = res.data;
      const isEmpty = Object.keys(res.data).length === 0;
      if (!isEmpty) {
        this.displayAnnouncement = true;
      }
    },
  },
};
</script>