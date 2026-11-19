

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
              Add New User
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Add New User</h4>
      </div>
    </div>
    <div class="tx-13 mg-b-25">
      <div>
        <form>
          <h3>User Verification</h3>
          <section>
            <p class="mg-b-20">Search and verify name from HRIS API</p>

            <div class="row row-sm">
              <div class="search-form col-sm-6">
                <select
                  v-model="selected.employee"
                  class="form-control spinner"
                  placeholder="Search name"
                  @change="onChange($event)"
                  :disabled="this.loading"
                >
                  <option
                    v-for="employee in employees"
                    v-bind:Key="employee"
                    v-bind:value="employee"
                  >
                    {{ employee }}
                  </option>
                </select>
                <button class="btn" type="button" disabled>
                  <i
                    :class="{
                      'pi pi-spin pi-spinner': this.loading,
                      'pi pi-user': !this.loading,
                    }"
                  ></i>
                </button>
              </div>

              <!-- search-form -->
            </div>
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
                    v-on:change="onFileChange"
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
                <div class="card card-select">
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
                  <div class="card-footer">
                    <select
                      v-model="app_role[application.id]"
                      class="custom-select"
                      disabled
                      :id="'role' + application.id"
                      @change="
                        getSelectedRoleVal($event);
                        storeAppRole(application.id);
                      "
                    >
                      <!-- <option :value="0">Select role</option> -->
                      <option
                        v-for="role in application.roles"
                        :key="role.id"
                        :value="role.id"
                      >
                        {{ role.name }}
                      </option>
                      <!-- <option value="application.id">Admin</option>
                      <option value="2">Editor</option> -->
                    </select>
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
                              @change="checkPermission($event)"
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
                                @change="storeID($event)"
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
                                @change="storeID($event)"
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
                                @change="storeID($event)"
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
                                @change="storeID($event)"
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
                                @change="storeID($event)"
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
                                @change="storeID($event)"
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
      <button class="btn btn-primary btn-sm" @click.prevent="createUser">
        Submit
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
const env_Url = process.env.MIX_APP_URL;
export default {
  props: ["applications", "permissions"],
  data() {
    return {
      errors: [],
      success: "",
      users: env_Url + "/users/list",
      dashboard: env_Url + "/dashboard",
      employees: [],
      employeeArr: [],
      selected: {
        employee: "",
      },
      form: {
        name: "",
        username: "",
        dept: "",
        appRole: "",
        userPermissions: "",
        role_id: "2",
      },
      employeeselected: "",
      imageFile: null,
      fileLabel: "Choose File",
      roles: [],
      modules: [],
      permissions: [],
      app_role: [],
      appRoleID: null,
      oldRoleID: "",
      loading: true,
      perm: this.permissions,
      module: {},
    };
  },
  mounted() {
    this.fetchRoles();
    this.fetchModules();
    // this.fetchPermissions();
    
  },
  created() {
    this.fetchEmployees();
  },
  methods: {
    async createUser() {
      this.fileLabel = this.form.username + "_" + this.fileLabel;
      let form = new FormData();
      form.append("imageFile", this.imageFile);
      _.each(this.form, (value, key) => {
        form.append(key, value);
      });
      const res = await this.submit("post", "/users/store", form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        this.smessage();
      } else {
        // this.errors = res.data.errors;
        this.ermessage(res.data.errors);
      }
    },

    onFileChange(e) {
      this.imageFile = this.$refs.file.files[0];
      this.fileLabel = this.imageFile.name;
    },
    async fetchEmployees() {
      const res = await this.callApi("get", "/users/employee-lookup");
      this.employees = res.data;
      this.loading = false;
    },
    async fetchRoles() {
      const res = await this.callApi("get", "/roles/rolesList");
      this.roles = res.data;
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
    // async fetchPermissions(id) {
    //   this.module.id = id;
    //   const res = await this.callApiwParam(
    //     "get",
    //     "/permissions/getPermissions",this.module
    //   );
    //   this.permissions = res.data;
    // },
    onChange(evt) {
      this.employeeArr = evt.target.value.split(":");
      this.form.name = this.employeeArr[0];
      this.form.dept = this.employeeArr[1];
      var fullName = this.employeeArr[0].split(" ");
      fullName = fullName.filter((item) => item !== "");
      this.form.username =
        fullName[0].charAt(0) + fullName[1].charAt(0) + fullName[2];
    },
    getSelectedRoleVal(event) {
      var id = event.target.id;

      var sel = document.getElementById(id);
      document.getElementById(id + "desc").innerHTML =
        " - " + sel.options[sel.selectedIndex].text;
      // console.log(sel.options[sel.selectedIndex].text);
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
      if (document.getElementById(id).checked && roleID != "") {
        this.updateAppRole(id + "," + this.oldRoleID);
        if (this.appRoleID == "") {
          this.appRoleID = id + "," + roleID;
        } else {
          this.appRoleID = this.appRoleID + "|" + id + "," + roleID;
        }
      } else {
        this.updateAppRole(id + "," + roleID);
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
      //  console.log((id.match(/_/g) || []).length);

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
        if (_count == 2) {
          if (user_permissions.includes("," + id)) {
            user_permissions = user_permissions.replace("," + id, "");
          } else if (user_permissions.includes(id + ",")) {
            user_permissions = user_permissions.replace(id + ",", "");
          } else {
            user_permissions = user_permissions.replace(id, "");
          }
        } else if (_count == 3) {
          // console.log('id ' + id);
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
      // console.log(this.form.userPermissions);
    },
  },
};
</script>
