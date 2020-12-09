<template>
  <div class="card">
      <div class="card-body">
        <h1 class="d-none d-sm-block font-weight-lighter text-left">Excel File Data Mapping (Static)</h1>
        <hr>
        <div class="form-row mt-3">
          <div class="col-md-12">
            <label for="input-file-import" class="font-weight-light font-italic"><span class="font-weight-bold">Description:</span> This reads an excel file with the following fields. (Team ID, Name, Phone, Email, Sticky Phone Number ID)
            </label>
          </div>
          <div class="col-md-12 mt-4">
            <label for="input-file-import" class="font-weight-bold">File 
              <span>
                <input type="file" class="form-control-file" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
              </span>
            </label>
            <div v-if="error.message" class="invalid-feedback">
              {{ error.message }}
            </div>
            <button class="btn btn-secondary btn-sm" @click="analyzeData"><i class="fas fa-table"></i>&nbsp; Analyze file </button>
            <div class="progress mt-3" height="30px;" v-if="!dataHasLoaded">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
            <div v-else>
              <div class="mt-3" v-if="number_of_rows">
                No. of Items: {{ number_of_rows }} 
              </div>
              <table class="table table-hover table-sm" v-if="table_data.length != 0">
                <thead>
                    <tr>
                      <!-- <th scope="col" class="ideal-font" v-for="header in table_headers" :key="header.id">{{ header }}</th> -->
                      <th scope="col" class="ideal-font">Team ID</th>
                      <th scope="col" class="ideal-font">Name</th>
                      <th scope="col" class="ideal-font">Phone</th>
                      <th scope="col" class="ideal-font">Email</th>
                      <th scope="col" class="ideal-font">Sticky Phone Number ID</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :id="item.id" v-for="item in displayedData" :key="item.team_id">
                      <td class="ideal-font">{{ item[Object.keys(item)[0]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[1]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[2]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[3]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[4]] }}</td>
                    </tr>
                </tbody>
              </table>
              <nav aria-label="Page navigation example" class="mt-3" v-if="pages.length > 1">
                <ul class="pagination">
                  <li class="page-item">
                    <button type="button" class="page-link" v-if="page != 1" @click="page--"> <i class="fas fa-caret-left"></i> </button>
                  </li>
                  <li class="page-item">
                    <button type="button" class="page-link" v-for="pageNumber in pages.slice(page-1, page+5)" :key="pageNumber" @click="page = pageNumber"> {{pageNumber}} </button>
                  </li>
                  <li class="page-item">
                    <button type="button" @click="page++" v-if="page < pages.length" class="page-link"> <i class="fas fa-caret-right"></i> </button>
                  </li>
                </ul>
              </nav>	
              <button class="btn btn-secondary btn-sm mt-3" v-if="table_data.length != 0" @click="importData"><i class="fas fa-file-import"></i>&nbsp; Import file </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
  export default {
     data() {
        return {
          error: {},
          import_file: '',
          number_of_rows: '',
          table_headers: [],
          table_data: [],
          dataHasLoaded: true,

          page: 1,
			    perPage: 5,
          pages: [],		
          numberOfPages: '',
        }
      },
      computed: {
        displayedData () {
          return this.paginate(this.table_data);
        }
      },
      watch: {
        table_data () {
          this.setPages();
        }
      },
      filters: {
        trimWords(value){
          return value.split(" ").splice(0,20).join(" ") + '...';
        }
      },
      methods: {
        /*
        * *******************Pagination*********************
        */
        setPages () {
          this.numberOfPages = Math.ceil(this.table_data.length / this.perPage);
          for (let index = 1; index <= this.numberOfPages; index++) {
            this.pages.push(index);
          }
        },
        
        paginate (data) {
          let page = this.page;
          let perPage = this.perPage;
          let from = (page * perPage) - perPage;
          let to = (page * perPage);
          return  data.slice(from, to);
        },
        /*
        * **************************************************
        */
        onFileChange(e) {
          this.import_file = e.target.files[0];
        },

        analyzeData() {
          this.clearData();
          this.dataHasLoaded = false;
          let formData = new FormData();
          formData.append('import_file', this.import_file);

          axios.post('/api/contact/read', formData, {
              headers: { 'content-type': 'multipart/form-data' }
            })
            .then(res => {
                this.number_of_rows = res.data.numberOfRows;
                this.table_headers = res.data.headers;
                this.table_data = res.data.dataFromFile;

                this.dataHasLoaded = true;
            })
            .catch(error => {
                // code here when an upload is not valid
                this.error = error.response.data
                console.log('check error: ', this.error)
                this.dataHasLoaded = true;
            });
        },
        importData(){
          this.dataHasLoaded = false;
          let formData = new FormData();
          formData.append('import_file', this.import_file);

          axios.post('/api/contact/import', formData, {
              headers: { 'content-type': 'multipart/form-data' }
            })
            .then(res => {
                Broadcast.$emit('ToastMessage', {
                    message: 'File Imported Successfully'
                });
                this.$router.go();
            })
            .catch(error => {
                // code here when an upload is not valid
                this.error = error.response.data
                console.log('check error: ', this.error)
                this.dataHasLoaded = true;
            });
        },
        clearData(){
          this.table_data = []
          this.number_of_rows = ''
          this.table_headers = []
          this.table_data = []
          this.pages = []
          this.numberOfPages = ''
        }
      }
  }
</script>
<style>
  button.page-link {
    display: inline-block;
  }
  button.page-link {
      font-size: 12px;
      color: #3b3b3b;
      font-weight: 500;
  }
  .offset{
    width: 500px !important;
    margin: 20px auto;  
  }
</style>