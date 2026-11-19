

<template>
  <div class="container pd-x-0">
      <div class="d-sm-flex
          align-items-center
          justify-content-between
          mg-b-20 mg-lg-b-25 mg-xl-b-30">
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
              <li class="breadcrumb-item active" aria-current="page">
                Role Access Rights
              </li>
            </ol>
          </nav>
          <h4 class="mg-b-0 tx-spacing--1">Role Access Rights</h4>
        </div>
      </div>

      <div class="tx-13 mg-b-25">
        <div>
          <form>

            <section>              
              <div class="row row-sm">
                <div class="col-lg-6"> 
                  <div class="form-group">
                    <label class="d-block">Role *</label>  
                    <select                
                        class="custom-select"            
                        @change="getData($event)"
                        >
                        <option selected>Select Role</option>
                        <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"          
                        >
                        {{ role.name }}
                        </option>
                    </select>  
                  </div>
                </div>
              </div>              
            </section>

            <h3>Role Access Rights</h3>
            <section>
              <p>Show the access rights for each role.</p>
                    <div>
                      <div class="table-responsive text-nowrap access-tbl">
                        <input
                          type="hidden"
                          id="rolePermissions"
                          v-model="form.rolePermissions"
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
                          <tbody v-for="module in modules" :key="module.id">
                            <tr>
                              <td width="50%">
                                <p
                                  class="
                                    mg-0
                                    pd-t-5 pd-b-5
                                    tx-uppercase tx-semibold tx-primary
                                  "
                                >
                                  {{ module.description }}
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
                              v-for="permission in permissions"
                              :key="permission.id"
                            >
                              <template v-if="permission.module_type == module.description">
                                <td>
                                  {{ permission.description }}
                                </td>
                                <td>
                                  <div class="md-checkbox">
                                    <input
                                      type="checkbox"
                                      class="md-check"
                                      :id="
                                        permission.id +
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
                                        permission.id +
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
                                        permission.id +
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
                                        permission.id +
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
                                        permission.id +
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
                                        permission.id +
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
            </section>
          </form>

        </div>
      </div>


    <div class="form-group">
      <button class="btn btn-primary btn-sm" @click.prevent="createRoleAccess">
        Submit
      </button>
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
      dashboard: this.$env_Url + "/dashboard",

      form: {
          rolePermissions: "",
          role_id: "",
          app_id: this.application.id,
      },
      roles: [],
      modules: [],
      permissions: [],      
    };
  },
  mounted() {
  },
  created() {
    this.fetchRoles();
    this.fetchModules();
    this.fetchPermissions();

  },
  methods: {
    async createRoleAccess() {
      const res = await this.submit("post", "/roleaccessrights/store", this.form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        this.smessage();
      } else {
        this.ermessage(res.data.errors);
      }
    },    

    getData(event){
      this.form.role_id = event.target.value
      this.getRolesPermissions(this.form.role_id);
    },

    async getRolesPermissions(roleID) {
      document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false);
      let access = new FormData();
      access.append("roleid", roleID);

      const res = await this.callApiwParam(
        "post",
        "/roleaccessrights/getAccessRights",
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
    },    
     
    async fetchRoles() {
        const res = await this.callApi("get", "/roles/rolesList_selected");
        this.roles = res.data;
    }, 

    async fetchModules() {
      const res = await this.callApi("get", "/modules/getModule_selected");
      this.modules = res.data;
    },    

    async fetchPermissions() {
      const res = await this.callApi("get", "/permissions/getPermission_selected");
      this.permissions = res.data;
    },    

    checkPermission(event) {
      var checkboxes = document.getElementsByTagName("input");
      var id = event.target.id;
      const cb = document.getElementById(id);
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == "checkbox") {
          if (checkboxes[i].id.includes(id)) {
            document.getElementById(checkboxes[i].id).checked = cb.checked;
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

      var role_permissions = this.form.rolePermissions;
      if (role_permissions == null) {
        role_permissions = "";
      }
      if (document.getElementById(id).checked) {
        if (role_permissions != "") {
          role_permissions = role_permissions + "," + id;
        } else {
          role_permissions = id;
        }
      } else {
        var _count = (id.match(/_/g) || []).length;
        if (_count == 2) {
          if (role_permissions.includes("," + id)) {
            role_permissions = role_permissions.replace("," + id, "");
          } else if (role_permissions.includes(id + ",")) {
            role_permissions = role_permissions.replace(id + ",", "");
          } else {
            role_permissions = role_permissions.replace(id, "");
          }
        } else if (_count == 3) {
          
          var re = new RegExp(id, "g");
          var count = (role_permissions.match(re) || []).length;
          for (var i = 1; i <= count; i++) {
            if (role_permissions.includes("," + id)) {
              role_permissions = role_permissions.replace("," + id, "");
            } else if (role_permissions.includes(id + ",")) {
              role_permissions = role_permissions.replace(id + ",", "");
            } else {
              role_permissions = role_permissions.replace(id, "");
            }
          }
        }
      }
      this.form.rolePermissions = role_permissions;
      
    },    


  },

};
</script>
