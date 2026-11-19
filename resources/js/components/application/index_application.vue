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
          <ol class="breadcrumb breadcrumb-style1 mg-b-5">
            <li class="breadcrumb-item" aria-current="page">
              <a :href="dashboard">CAMM</a>
            </li> 
            <li class="breadcrumb-item active" aria-current="page">
              Applications
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Manage Applications</h4>
      </div>
    </div>

    <ConfirmDialog></ConfirmDialog>
    <div class="row row-sm">
      <!-- Start Pages -->

      <div class="col-md-12">
        <Toolbar class="mb-4">
          <template #start>
            <Button
              label="Export"
              icon="pi pi-upload"
              class="p-button-help p-button-sm mr-2"
              @click="exportCSV($event)"
            />
            <Button
              label="New"
              icon="pi pi-plus"
              class="p-button-success p-button-sm mr-2"
              @click="addNew()"
            />
          </template>
          <template #end>
            <div class="search-form mg-r-10">
              <input
                name="search"
                type="search"
                id="search"
                class="form-control"
                placeholder="Search"
                v-model="filters['global'].value"
              />
              <button class="btn filter" id="btnSearch">
                <i data-feather="search"></i>
              </button>
            </div>
          </template>
        </Toolbar>
        <div class="table-list mg-b-10">
          <div class="table-responsive-lg">
            <DataTable
              ref="dt"
              :value="applicationLists"
              :paginator="true"
              :rows="10"
              stripedRows
              removableSort
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              :rowsPerPageOptions="[10, 20, 50]"
              responsiveLayout="scroll"
              :loading="loading1"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              v-model:filters="filters"
              filterDisplay="menu"
              :globalFilterFields="[
                'description',  
                'server_name',              
                'database_name',
                'dbusername',
                'dbpass',
                'app_url',
                'status',
              ]"
            >
              <template #empty> No record found. </template>
              <template #loading> Loading data. Please wait. </template>

              <Column field="description" header="Description" :sortable="true"></Column>
              <Column field="server_name" header="Server Name" :sortable="true"></Column>
              <Column field="database_name" header="DB Name" :sortable="true"></Column>
              <Column field="dbusername" header="DB Username" :sortable="true"></Column>
              <Column field="dbpass" header="DB Password" :sortable="true"></Column>
              <Column field="app_url" header="App URL" :sortable="true"></Column>              
              <Column field="status" header="Status" hidden></Column>
              <Column field="active" header="Status" :sortable="true" :exportable="false">
                <template #body="slotProps">
                    <span v-if="slotProps.data.active == '1'">Active</span>
                    <span v-else style="color:red">Inactive</span>
                </template>                
              </Column>
              

              <Column
                :exportable="false"
                style="min-width: 8rem"
                header="Actions"
              >
                <template #body="slotProps">
                  <Button
                    v-bind:title='editMsg'
                    icon="pi pi-pencil"
                    class="p-button-rounded p-button-success mr-2"
                    @click="editApplication(slotProps)"
                  />

                </template>
              </Column>
            </DataTable>
          </div>
        </div>
      </div>
      <!-- End Pages -->
    </div>
  </div>

  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
</template>
<script>
import { FilterMatchMode, FilterOperator } from "primevue/api";
export default {
  props: ["applications"],
  data() {
    return {
      dashboard: this.$env_Url + "/dashboard",
      filters: null,
      applicationLists: this.applications,
      form: {
        id: 0,
      },
      editMsg: "Edit Application",

    };
  },
  created() {
    this.initFilters();
  },
  methods: {
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
    clearFilter() {
      this.filters["global"].value = null;
    },
    addNew() {
      window.location.href = this.$env_Url + "/applications/create";
    },

    editApplication(data) {
      let src = data.data.id,
        alt = data.data.id;
      window.location.href = this.$env_Url + "/applications/edit/" + alt;
    },

    exportCSV() {
      this.$refs.dt.exportCSV();
    },

    async fetchApplications() {
      const res = await this.getDataFromDB(
        "get",
        "/applications/getApplications"
      );
      this.applicationLists = res.data;    },
  },
};
</script>