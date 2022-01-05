<template>
  <section class="content">
    <div class="container-fluid">
      <button
          type="button"
          class="btn btn-sm btn-primary mb-2"
          @click="newModal"
      >
          <i class="fa fa-plus-square"></i>
          Add New
      </button>
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Company List</h3>
                <div class="card-tools">
                    <!-- SEARCH FORM -->
                    <form class="form-inline ml-3" @submit.prevent="searchCompanies()">
                      <div class="form-check mr-3">
                          <input v-model="fav"  name="fav" ref="fav" type="checkbox" class="form-check-input">
                          <label class="form-check-label" >Favourite</label>
                        </div>
                      <div class="input-group input-group-sm">
                        <input
                          name="search"
                          class="form-control form-control-navbar"
                          type="search"
                          placeholder="Search"
                          aria-label="Search"
                          ref="search"
                        />
                        <div class="input-group-append">
                          <button class="btn btn-navbar" type="submit">
                            Filter
                          </button>
                        </div>
                      </div>
                    </form>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Address</th>
                      <th>Phone</th>
                      <th>Favourite</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="company in companies.data" :key="company.id">

                      <td>{{company.id}}</td>
                      <td>{{company.company_name}}</td>
                      <td>{{company.address }}</td>
                      <td>{{company.phone}}</td>
                      <td>{{company.is_favorite}}</td>
                      <td>
                        
                        <a href="#" @click="editModal(company)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteCompany(company.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="companies" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Company</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateCompany() : createCompany()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input v-model="form.company_name" type="text" name="company_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('company_name') }">
                            <has-error :form="form" field="company_name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input v-model="form.address" type="text" name="address"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                            <has-error :form="form" field="address"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input v-model="form.phone" type="number" name="phone"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                            <has-error :form="form" field="phone"></has-error>
                        </div>
                        <div class="form-check">
                          <input v-model="form.is_favorite" name="is_favorite" type="checkbox" class="form-check-input">
                          <label class="form-check-label" >Favourite</label>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                companies : {},
                search :"",
                fav : false,
                form: new Form({
                    id : '',
                    company_name: '',
                    address: '',
                    phone:'',
                    is_favorite:false
                }),
                
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();
              
              axios.get('api/company?page=' + page).then(({ data }) => (this.companies = data.data));

              this.$Progress.finish();
          },
          loadCompanies(){

            // if(this.$gate.isAdmin()){
              axios.get("api/company").then(({ data }) => (this.companies = data.data));
            // }
          },
          searchCompanies() {
            var query = [];
            if(this.$refs.search.value)
            {
              this.search = this.$refs.search.value;
              query.push("name="+this.search);
            }
            if(this.fav)
            {
               query.push("favorite=true");
            }
            console.log(this.fav);
            axios.get("api/company?"+query.join('&')).then(({ data }) => (this.companies = data.data));
          },
          editModal(company){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(company);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createCompany(){
              this.$Progress.start();

              this.form.post('api/company')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadCompanies();

                } else {
                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });

                  this.$Progress.failed();
                }
              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },
          updateCompany(){
              this.$Progress.start();
              this.form.put('api/company/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadCompanies();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
          deleteCompany(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('api/company/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadCompanies();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();

            this.loadCompanies();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          
        },
    }
</script>
