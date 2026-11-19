
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
              <a :href="shutdownapplications">Scheduled Shutdown</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Manage Scheduled Shutdown
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Manage Scheduled Shutdown</h4>
      </div>
    </div>
    <form>
      <div class="row row-sm">
        <div class="col-lg-6">
          <div class="form-group">
            <label class="d-block">Date *</label>
            <input
              type="date"
              class="form-control"
              name="date"
              id="date"
              required
              v-model="form.scheduled_date"
            />
          </div>
          <div class="form-group">
            <label class="d-block">Time *</label>
            <input
              type="time"
              class="form-control"
              name="time"
              id="time"
              v-model="form.scheduled_time"
              required
            />
          </div>
          <div class="form-group">
            <label class="d-block">Reason *</label>
            <textarea
              class="form-control"
              rows="3"
              v-model="form.reason"
            ></textarea>
          </div>
        </div>
        <div class="col-lg-12">
          <label class="d-block">Choose application:</label>
          <div class="row row-xs mt-3">
            <div
              class="col-12 col-sm-4 col-md-3 mb-3"
              v-for="application in applications"
              :key="application.id"
              @click="storeApp(application.id)"
            >
              <div
                class="card card-select"
                :class="{
                  'bg-primary': apps[application.id],
                  'text-white': apps[application.id],
                }"
              >
                <input
                  :id="application.id"
                  type="checkbox"
                  v-model="application.id"
                />
                <div class="card-header">
                  <img
                    class="mx-auto d-block w-100"
                    :src="this.$env_Url + '/assets/images/app.png'"
                  />
                </div>
                <div class="card-body">
                  <span class="font-weight-bold">{{
                    application.description
                  }}</span>
                </div>
              </div>
            </div>
            <!-- col -->
          </div>
        </div>
        <div class="col-lg-12 mg-t-30">
          <button
            class="btn btn-primary btn-sm"
            @click.prevent="updateShutdownApplication"
          >
            Update
          </button>
          &nbsp;
          <a
            :href="shutdownapplications"
            class="btn btn-outline-secondary btn-sm"
            type="cancel"
            >Cancel</a
          >
        </div>
      </div>
    </form>
  </div>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>
<script  type="application/javascript">
export default {
  props: ["applications", "shutdownapplication"],
  data() {
    return {
      errors: [],
      success: "",
      shutdownapplications: this.$env_Url + "/shutdownapplications/list",
      dashboard: this.$env_Url + "/dashboard",
      form: {
        id: this.shutdownapplication.id,
        scheduled_date: this.shutdownapplication.scheduled_date,
        scheduled_time: this.shutdownapplication.scheduled_time.replace(
          ":00.0000000",
          ""
        ),
        reason: this.shutdownapplication.reason,
        apps: this.shutdownapplication.apps,
      },
      appIds: this.shutdownapplication.apps,
      apps: {},
    };
  },
  mounted() {
    var idArr = this.form.apps.toString().split(",");
    var idArrLenght = idArr.length;
    for (var i = 0; i < idArrLenght; i++) {
      this.apps[idArr[i]] = true;
      document.getElementById(idArr[0]).checked = true;
    }
  },
  methods: {
    async updateShutdownApplication() {
      this.form.apps = "";
      var appidsArr;
      appidsArr = this.appIds.toString().split(",");
      var appidsArrLen = appidsArr.length;

      for (var i = 0; i < appidsArrLen; i++) {
        if (appidsArr[i] != "") {
          if (this.form.apps != "") {
            this.form.apps = this.form.apps + "," + appidsArr[i];
          } else {
            this.form.apps = appidsArr[i];
          }
        }
      }
      const res = await this.submit(
        "post",
        "/shutdownapplications/update",
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
        this.smessage();
      } else {
        this.ermessage(res.data.errors);
      }
    },

    storeApp(id) {
      var appid = this.appIds;
      if (document.getElementById(id).checked) {
        if (appid != "") {
          appid = appid + "," + id;
        } else {
          appid = id;
        }
      } else {
        appid = appid.toString().replace(id, "");
      }
      this.appIds = appid;
    },
  },
};
</script>

