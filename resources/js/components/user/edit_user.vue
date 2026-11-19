

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
              <a :href="users">Users</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Edit User
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Edit User</h4>
      </div>
    </div>
    <div class="tx-13 mg-b-25">
      <div>
        <form>
          <h3>User Details</h3>
          <section>
            <div class="row row-sm" style="padding-bottom: 10px">
              <div class="col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Full Name"
                  v-model="form.name"
                  disabled
                />
              </div>
              <div class="col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Department"
                  v-model="form.dept"
                  disabled
                />
              </div>
            </div>
            <div class="row row-sm">
              <div class="col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Domain"
                  v-model="form.username"
                  disabled
                />
              </div>
            </div>
            <!-- row -->
          </section>
          <h3>Application & Role Selection</h3>
          <section>
            <p>Select applications and choose role per application.</p>
            <div class="row row-sm">
              <div class="col-sm-6">
                <label class="d-block"
                  >Upload scanned copy of the user’s request form</label
                >
                <input type="hidden" v-model="form.imageFile" />
                <div class="custom-file">
                  <input
                    type="file"
                    class="custom-file-input"
                    id="customFile"
                    ref="file"
                    v-on:change="onFileChange()"
                  />
                  <label class="custom-file-label" for="customFile">{{
                    fileLabel
                  }}</label>
                </div>
              </div>
            </div>
            <label class="d-block">Choose application and its role:</label>
            <div class="row row-xs mt-3">
              <div
                class="col-6 col-sm-4 col-md-3 mb-3"
                v-for="application in applications"
                :key="application.id"
                @click="storeAppRole(application.id)"
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
                    v-model="apps[application.id]"
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
                  <div class="card-footer">
                    <!-- <span v-for="selected in roleSelected" :key="selected.appRole"> -->
                    <select
                      v-model="app_role[application.id]"
                      class="custom-select"
                      :disabled="!apps[application.id]"
                      :id="'role' + application.id"
                      @change="
                        getSelectedRoleVal($event);
                        storeAppRole(application.id);
                      "
                    >
                      <option
                        v-for="role in application.roles"
                        :key="role.id"
                        :value="role.id"
                      >
                        {{ role.name }}
                      </option>
                    </select>
                    <!-- </span> -->
                  </div>
                  <input type="hidden" id="appRole" v-model="form.appRole" />
                </div>
              </div>
            </div>
          </section>
        </form>
        <h3>Access Rights</h3>
        <section>
          <p>
            Show the access rights for each role applicable for that user to
            allow customization of the user’s access.
          </p>
          <Accordion>
            <AccordionTab
              v-for="application in applications"
              :key="application.id"
            >
              <template #header>
                <h6>
                  <i class="pi pi-th-large"></i>
                  <span>&nbsp;{{ application.description }}</span>
                  <code class="text-primary">
                    <label
                      style="margin-bottom: 0px !important; font-size: 13px"
                      :id="'role' + application.id + 'desc'"
                    ></label
                  ></code>
                </h6>
              </template>
              <div>
                <div class="table-responsive text-nowrap access-tbl">
                  <input
                    type="hidden"
                    id="userPermissions"
                    v-model="form.userPermissions"
                  />
                  <table class="table table-hover mg-0" style="width: 100%">
                    <thead class="thead-primary">
                      <tr>
                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Permission List
                          </p>
                        </th>
                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            View
                          </p>
                        </th>

                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Create
                          </p>
                        </th>

                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Update
                          </p>
                        </th>

                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Delete
                          </p>
                        </th>

                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Print
                          </p>
                        </th>

                        <th>
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-white
                            "
                          >
                            Upload
                          </p>
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      v-for="module in application.modules"
                      :key="module.id"
                    >
                      <tr>
                        <td width="50%">
                          <p
                            class="
                              mg-0
                              pd-t-5 pd-b-5
                              tx-uppercase tx-semibold tx-primary
                            "
                          >
                            {{ module.name }}
                          </p>
                        </td>

                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_view'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_create'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_edit'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_delete'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_print'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                        <td>
                          <div class="md-checkbox custom-padding">
                            <input
                              type="checkbox"
                              class="md-check"
                              :id="application.id + '_' + module.id + '_upload'"
                              @click="checkPermission($event)"
                            />
                          </div>
                        </td>
                      </tr>
                      <tr
                        v-for="permission in fetchPermissions(
                          module.name,
                          application.id
                        )"
                        :key="permission.app_permission_id"
                      >
                        <template v-if="permission.module_type == module.name">
                          <td>
                            {{ permission.description }}
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_view'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_create'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_edit'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_delete'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_print'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                          <td>
                            <div class="md-checkbox">
                              <input
                                type="checkbox"
                                class="md-check"
                                :id="
                                  permission.app_permission_id +
                                  '_' +
                                  application.id +
                                  '_' +
                                  module.id +
                                  '_upload'
                                "
                                @click="storeID($event)"
                              />
                            </div>
                          </td>
                        </template>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </AccordionTab>
          </Accordion>
        </section>
      </div>
    </div>
    <!-- df-example -->
    <div class="form-group">
      <button class="btn btn-primary btn-sm" @click.prevent="updateUser">
        Update
      </button>
      &nbsp;
      <a :href="users" class="btn btn-outline-secondary btn-sm" type="cancel"
        >Cancel</a
      >
    </div>
  </div>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>

<script  type="application/javascript">
// const env_Url = process.env.MIX_APP_URL;
export default {
  props: ["applications", "user", "permissions"],
  data() {
    return {
      errors: [],
      success: "",
      users: this.$env_Url + "/users/list",
      dashboard: this.$env_Url + "/dashboard",
      employees: [],
      employeeArr: [],
      application: [],
      selected: {
        employee: "",
      },
      form: {
        id: this.user.id,
        name: this.user.name,
        username: this.user.username,
        dept: this.user.dept,
        appRole: this.user.appRole,
        appRoleDesc: this.user.approle_desc,
        userPermissions: "",
        role_id: this.user.role_id,
        filePath: this.user.imageFile,
      },
      employeeselected: "",
      imageFile: null,
      fileLabel: "Choose File",
      roles: this.applications.roles,
      modules: [],
      // permissions: [],
      app_role: [],
      appRoleID: null,
      oldRoleID: this.user.appRole,
      isBgPrimary: false,
      textWhite: false,
      // applications: [],
      isSelected: false,
      // app: {
      //   CAMM: false,
      //   EDMS: false,
      //   OMWS: false,
      //   IMS: false,
      //   COASTER: false,
      //   VMS: false,
      //   IRMS: false,
      // },
      apps: {},
      roleSelected: [],
      userAccess: [],
      firstLoad: true,
      perm: this.permissions,
    };
  },
  updated() {
    this.loadDefaultRole();
    this.firstLoad = false;
  },
  created() {
    // this.fetchApplication();
    this.fetchModules();
    // this.fetchPermissions();
    this.loadAppRole();
  },
  mounted() {
    this.loadDefaultRole();
    this.firstLoad = false;
  },
  methods: {
    async updateUser() {
      let form = new FormData();
      form.append("imageFile", this.imageFile);
      _.each(this.form, (value, key) => {
        form.append(key, value);
      });
      const res = await this.submit("post", "/users/update", form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        this.smessage();
        this.filePath = this.fileLabel;
      } else {
        this.ermessage(res.data.errors);
      }
    },

    onFileChange(e) {
      this.imageFile = this.$refs.file.files[0];
      this.fileLabel = this.imageFile.name;

      // this.fileLabel = this.form.username + "_" + this.imageFile.name;
    },
    getSelectedRoleVal(event) {
      var id = event.target.id;

      var sel = document.getElementById(id);
      document.getElementById(id + "desc").innerHTML =
        " - " + sel.options[sel.selectedIndex].text;
      // console.log(sel.options[sel.selectedIndex].text);
    },
    async fetchRoles() {
      const res = await this.callApi("get", "/roles/rolesList");

      var roleArr;
      this.roles = res.data
        .filter(function (obj) {
          roleArr = obj.split(":");

          return obj.selected;
        })
        .map(function (obj) {
          return obj.id;
        });
    },
    async fetchModules() {
      const res = await this.callApi("get", "/modules/getModules");
      this.modules = res.data;
    },
    fetchPermissions(module, app) {
      if (module != undefined) {
        return this.perm.filter(
          (permission) =>
            permission.module_type == module && permission.application == app
        );
      }
    },
    // async fetchPermissions() {
    //   const res = await this.callApi("get", "/permissions/getPermissions");
    //   this.permissions = res.data;
    // },

    async fetchApplication() {
      const res = await this.callApi("get", "/modules/applicationsList");
      this.applications = res.data;
      this.roles = this.applications.roles;
    },
    async fetchAccessRights() {
      const res = await this.callApiwParam(
        "get",
        "/users/getAccessRights",
        this.form
      );
      this.applications = res.data;
    },
    loadAppRole() {
      // var appRoleArrDesc;
      // var application_role_desc;
      // appRoleArrDesc = this.form.appRoleDesc.split("|");
      // var appRoleArrDescLenght = appRoleArrDesc.length;
      // for (var i = 0; i < appRoleArrDescLenght; i++) {
      //   application_role_desc = appRoleArrDesc[i].split(":");
      //   if (application_role_desc[0].trim() === "CAMM") {
      //     this.app.CAMM = true;
      //   }
      //   if (application_role_desc[0].trim() === "EDMS") {
      //     this.app.EDMS = true;
      //   }
      //   if (application_role_desc[0].trim() === "IMS") {
      //     this.app.IMS = true;
      //   }
      //   if (application_role_desc[0].trim() === "COASTER") {
      //     this.app.COASTER = true;
      //   }
      //   if (application_role_desc[0].trim() === "VMS") {
      //     this.app.VMS = true;
      //   }
      //   if (application_role_desc[0].trim() === "IRMS") {
      //     this.app.IRMS = true;
      //   }
      // }

      var appRoleArr = this.form.appRole.toString().split("|");
      var appRoleArrLenght = appRoleArr.length;
      var application_role;
      for (var i = 0; i < appRoleArrLenght; i++) {
        application_role = appRoleArr[i].split(",");
        this.apps[application_role[0]] = true;
      }
    },

    loadDefaultRole() {
      var appRoleArr;
      var application_role;
      appRoleArr = this.form.appRole.split("|");
      var appRoleArrLenght = appRoleArr.length;
      for (var i = 0; i < appRoleArrLenght; i++) {
        application_role = appRoleArr[i].split(",");
        var mySelect = document.getElementById("role" + application_role[0]);
        mySelect.value = application_role[1];
        // var sel = document.getElementById( application_role[1]);
        document.getElementById(
          "role" + application_role[0] + "desc"
        ).innerHTML = " - " + mySelect.options[mySelect.selectedIndex].text;
        if (this.firstLoad) {
          this.getUsersPermissions(application_role[0]);
        }
      }
    },
    storeAppRole(id) {
      this.appRoleID = this.form.appRole;
      var roleID = document.getElementById("role" + id).value;

      if (id == 6) {
        this.form.role_id = roleID;
      }
      if (this.appRoleID == null) {
        this.appRoleID = "";
      }

      this.appRoleID = this.appRoleID.toString();
      var roleIDs = this.appRoleID.split("|");
      var roleIDsLenght = roleIDs.length;
      for (var i = 0; i < roleIDsLenght; i++) {
        if (roleIDs[i].includes(id + ",")) {
          this.oldRoleID = roleIDs[i].replace(id + ",", "");
        }
      }
      // console.log('old role id ' + roleID);
      if (document.getElementById(id).checked) {
        if (roleID != "") {
          this.updateAppRole(id + "," + this.oldRoleID);
          if (this.appRoleID == "") {
            this.appRoleID = id + "," + roleID;
          } else {
            this.appRoleID = this.appRoleID + "|" + id + "," + roleID;
          }
        }
      } else {
        if (roleID != "") {
          this.updateAppRole(id + "," + roleID);
        }
      }

      this.form.appRole = this.appRoleID;
      this.oldRoleID = roleID;
    },
    updateAppRole(id) {
      if (this.appRoleID.indexOf("|" + id) > -1) {
        this.appRoleID = this.appRoleID.replace("|" + id, "");
      } else if (this.appRoleID.includes(id + "|")) {
        this.appRoleID = this.appRoleID.replace(id + "|", "");
        // console.log(appRoleID);
      } else {
        this.appRoleID = this.appRoleID.replace(id, "");
      }
    },
    checkPermission(event) {
      var checkboxes = document.getElementsByTagName("input");
      var id = event.target.id;
      const cb = document.getElementById(id);
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == "checkbox") {
          if (checkboxes[i].id.includes(id)) {
            document.getElementById(checkboxes[i].id).checked = cb.checked;
            // console.log( document.getElementById(checkboxes[i].id).id)
            this.storeID(checkboxes[i].id);
          }
        }
      }
    },
    storeID(data) {
      var id;
      if (typeof data == "object") {
        id = data.target.id;
      } else {
        id = data;
      }
      var user_permissions = this.form.userPermissions;
      if (user_permissions == null) {
        user_permissions = "";
      }
      if (document.getElementById(id).checked) {
        if (user_permissions != "") {
          user_permissions = user_permissions + "," + id;
        } else {
          user_permissions = id;
        }
      } else {
        var _count = (id.match(/_/g) || []).length;
        // console.log(_count);
        if (_count == 2) {
          if (user_permissions.includes("," + id)) {
            user_permissions = user_permissions.replace("," + id, "");
          } else if (user_permissions.includes(id + ",")) {
            user_permissions = user_permissions.replace(id + ",", "");
          } else {
            user_permissions = user_permissions.replace(id, "");
          }
        } else if (_count == 3) {
          var re = new RegExp(id, "g");
          var count = (user_permissions.match(re) || []).length;
          for (var i = 1; i <= count; i++) {
            if (user_permissions.includes("," + id)) {
              user_permissions = user_permissions.replace("," + id, "");
            } else if (user_permissions.includes(id + ",")) {
              user_permissions = user_permissions.replace(id + ",", "");
            } else {
              user_permissions = user_permissions.replace(id, "");
            }
          }
        }
      }
      this.form.userPermissions = user_permissions;

    },
    async getUsersPermissions(appID) {
      let access = new FormData();
      access.append("username", this.user.username);
      access.append("appid", appID);

      const res = await this.callApiwParam(
        "post",
        "/users/getAccessRights",
        access
      );
      var chkid = "";
      var oldAction = "";
      var rolePermissionLenght = res.data.length;
      for (var i = 0; i < rolePermissionLenght; i++) {
        chkid = "";
        chkid =
          res.data[i].permission_id +
          "_" +
          res.data[i].application_id +
          "_" +
          res.data[i].module_id +
          "_" +
          res.data[i].action;
        // console.log(chkid);
        if (chkid != "") {
          document.getElementById(
            res.data[i].application_id +
              "_" +
              res.data[i].module_id +
              "_" +
              res.data[i].action
          ).checked = true;
          document.getElementById(chkid).checked = true;
          if (oldAction != res.data[i].action) {
            this.storeID(
              res.data[i].application_id +
                "_" +
                res.data[i].module_id +
                "_" +
                res.data[i].action
            );
          }
          this.storeID(chkid);

          oldAction = res.data[i].action;
        }
      }
      // this.userAccess = res.data;
      // res.data.forEach(function (element, index) {
      //   chkid = "";
      //   chkid =
      //     element.permission_id +
      //     "_" +
      //     element.application_id +
      //     "_" +
      //     element.module_id +
      //     "_" +
      //     element.action;
      //     console.log(chkid);
      //   if (chkid != "") {
      //     document.getElementById(
      //       element.application_id +
      //         "_" +
      //         element.module_id +
      //         "_" +
      //         element.action
      //     ).checked = true;
      //     document.getElementById(chkid).checked = true;
      //     this.storeID(chkid);
      //   }
      // });
    },
  },
};
</script>
